<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $items = collect($cart)->map(function ($item, $productId) {
            return [
                'product_id' => (int) $productId,
                'name' => $item['name'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
                'line_total' => (float) $item['price'] * (int) $item['quantity'],
            ];
        })->values();

        return Inertia::render('cart/Index', [
            'items' => $items,
            'total' => $items->sum('line_total'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $cart = $request->session()->get('cart', []);

        $id = (string) $product->id;
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $data['quantity'];
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $data['quantity'],
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = $request->session()->get('cart', []);
        $id = (string) $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $data['quantity'];
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function destroy(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[(string) $product->id]);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }
}