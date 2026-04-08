<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('cart.index');
    }

    public function pay(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'payment_method' => 'required|in:stripe,mock',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Ostukorv on tühi.');
        }

        $stockError = $this->validateStock($cart);
        if ($stockError !== null) {
            return redirect()->route('cart.index')->with('error', $stockError);
        }

        $total = collect($cart)->sum(fn ($item) => (float) $item['price'] * (int) $item['quantity']);

        $order = Order::create([
            'user_id' => $request->user()?->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'total_amount' => $total,
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => (int) $productId,
                'quantity' => (int) $item['quantity'],
                'unit_price' => (float) $item['price'],
                'line_total' => (float) $item['price'] * (int) $item['quantity'],
            ]);
        }

        if ($data['payment_method'] === 'mock') {
            $this->finalizeSuccessfulOrder($order);
            $request->session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Mock-makse õnnestus, tellimus kinnitatud.');
        }

        $secret = (string) config('services.stripe.secret');
        if ($secret === '') {
            return redirect()->route('checkout.index')->with('error', 'Stripe seadistus puudub (STRIPE_SECRET).');
        }

        try {
            Stripe::setApiKey($secret);

            $lineItems = collect($cart)->map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => (string) $item['name'],
                        ],
                        'unit_amount' => (int) round(((float) $item['price']) * 100),
                    ],
                    'quantity' => (int) $item['quantity'],
                ];
            })->values()->all();

            $session = StripeCheckoutSession::create([
                'mode' => 'payment',
                'line_items' => $lineItems,
                'customer_email' => $data['email'],
                'success_url' => route('checkout.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel', $order),
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'user_id' => (string) ($request->user()?->id ?? ''),
                ],
            ]);

            $order->update([
                'stripe_session_id' => $session->id,
            ]);

            // Inertia/XHR puhul tuleb välissuuna jaoks kasutada Inertia::location(),
            // mitte tavalist redirect()->away(), vastasel juhul tekib CORS fetch viga.
            return Inertia::location($session->url);
        } catch (ApiErrorException $e) {
            $order->update(['payment_status' => 'failed']);

            return redirect()->route('checkout.index')->with('error', 'Stripe maksesessiooni loomine ebaõnnestus.');
        }
    }

    public function success(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        if ($order->payment_method !== 'stripe') {
            return redirect()->route('cart.index');
        }

        $secret = (string) config('services.stripe.secret');
        $sessionId = (string) $request->query('session_id', '');
        if ($secret === '' || $sessionId === '' || $order->stripe_session_id !== $sessionId) {
            return redirect()->route('checkout.index')->with('error', 'Makse kinnitamine ebaõnnestus.');
        }

        try {
            Stripe::setApiKey($secret);
            $session = StripeCheckoutSession::retrieve($sessionId);
        } catch (ApiErrorException $e) {
            return redirect()->route('checkout.index')->with('error', 'Stripe vastuse kontroll ebaõnnestus.');
        }

        if ($session->payment_status === 'paid') {
            if (! $this->finalizeSuccessfulOrder($order)) {
                return redirect()->route('cart.index')->with('error', 'Makse õnnestus, kuid laoseis muutus. Võta ühendust toega.');
            }

            $request->session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Makse õnnestus, tellimus kinnitatud.');
        }

        $order->update(['payment_status' => 'pending']);

        return redirect()->route('checkout.index')->with('info', 'Makse ootel.');
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        if ($order->payment_method === 'stripe' && $order->payment_status === 'pending') {
            $order->update(['payment_status' => 'failed']);
        }

        return redirect()->route('checkout.index')->with('error', 'Makse katkestati või ebaõnnestus.');
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        $userId = $request->user()?->id;
        if ($userId === null || (int) $order->user_id !== (int) $userId) {
            abort(403);
        }
    }

    private function validateStock(array $cart): ?string
    {
        foreach ($cart as $productId => $item) {
            $product = Product::find((int) $productId);
            if (! $product) {
                return 'Üks toode ostukorvis ei ole enam saadaval.';
            }

            $wanted = (int) ($item['quantity'] ?? 0);
            if ($wanted < 1) {
                return 'Vigane tootekogus ostukorvis.';
            }

            if ($product->stock_quantity < $wanted) {
                return "Tootel \"{$product->name}\" pole piisavalt laoseisu. Laos: {$product->stock_quantity} tk.";
            }
        }

        return null;
    }

    private function finalizeSuccessfulOrder(Order $order): bool
    {
        if ($order->payment_status === 'success') {
            return true;
        }

        return DB::transaction(function () use ($order) {
            $items = OrderItem::query()
                ->where('order_id', $order->id)
                ->lockForUpdate()
                ->get();

            foreach ($items as $item) {
                $product = Product::query()->lockForUpdate()->find($item->product_id);
                if (! $product || $product->stock_quantity < $item->quantity) {
                    return false;
                }
            }

            foreach ($items as $item) {
                Product::query()
                    ->whereKey($item->product_id)
                    ->decrement('stock_quantity', (int) $item->quantity);
            }

            $order->update(['payment_status' => 'success']);

            return true;
        });
    }
}
