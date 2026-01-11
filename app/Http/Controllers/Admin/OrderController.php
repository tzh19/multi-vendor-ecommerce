<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    // List all orders for admin
    public function index()
    {
        $orders = Order::with('user', 'items.product')
            ->latest()
            ->paginate(20)
            ->through(fn ($order) => [
                'id' => $order->id,
                'customer' => $order->user?->name ?? 'Unknown',
                'total_items' => $order->items->sum('quantity'),
                'subtotal' => number_format($order->subtotal, 2),
                'status' => $order->status,
                'created_at' => $order->created_at->format('d M, Y'),
            ]);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    // Show order details
    public function show(Order $order)
    {
        $order->load('user', 'items.product');


        $transformedOrder = [
                       'id' => $order->id,
                       'customer' => $order->user?->name,
                       'email' => $order->user?->email,
                       'total_items' => $order->items->sum('quantity'),
                       'total' => number_format($order->total, 2),
                       'status' => $order->status,
                       'created_at' => $order->created_at->format('d M, Y'),
                       'items' => $order->items->map(function ($item) {
                           return [
                               'id' => $item->id,
                               'product_name' => $item->product->name,
                               'price' => number_format($item->price, 2),
                               'quantity' => $item->quantity,
                               'subtotal' => number_format($item->price * $item->quantity, 2),
                           ];
                       }),
                   ];

        return Inertia::render('Admin/Orders/Show', [
            'order' => $transformedOrder,
        ]);
    }

    // Update order status (optional)
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:processing,confirmed,shipped,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Order status updated.');
    }
}
