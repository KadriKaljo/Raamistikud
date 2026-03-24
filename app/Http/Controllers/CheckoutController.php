<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('checkout/Index');
    }

    public function pay(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Ostukorv on tühi.');
        }

        $total = collect($cart)->sum(fn ($item) => (float)$item['price'] * (int)$item['quantity']);

        // mock tulemus: success
        $paymentStatus = 'success';

        $order = Order::create([
            'user_id' => $request->user()?->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'payment_method' => $data['payment_method'],
            'payment_status' => $paymentStatus,
            'total_amount' => $total,
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => (int) $productId,
                'quantity' => (int) $item['quantity'],
                'unit_price' => (float) $item['price'],
                'line_total' => (float)$item['price'] * (int)$item['quantity'],
            ]);
        }


        if ($paymentStatus === 'success') {

            $request->session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Makse õnnestus, tellimus kinnitatud.');
        }

        if ($paymentStatus === 'failed') {
            return redirect()->route('checkout.index')->with('error', 'Makse ebaõnnestus.');
        }

        return redirect()->route('checkout.index')->with('info', 'Makse ootel.');
    }
}