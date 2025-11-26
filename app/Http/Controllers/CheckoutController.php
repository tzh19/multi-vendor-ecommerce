<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = \App\Models\Cart::where('user_id', auth()->user()->id)->with('product')->get();

        return Inertia::render('Checkout/Index', [
            'cartItems' => $cartItems,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'address' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', auth()->user()->id)->with('product')->get();


        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        $shipping = 5;
        $total = $subtotal + $shipping;

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'order_number' => 'ORD-'.strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping,
            'total_price' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status' => 'processing',
            'address' => $request->address
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        Cart::where('user_id', auth()->user()->id)->delete();
        return redirect()->route('checkout.success', $order);

    }

    public function success($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return Inertia::render('Checkout/Success', [
            'order' => $order,
        ]);
    }


}
