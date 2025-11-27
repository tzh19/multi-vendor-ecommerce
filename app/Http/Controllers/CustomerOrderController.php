<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
        ->where('user_id', auth()->user()->id)
        ->latest()
        ->get();

        return Inertia::render('Customer/Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->user()->id, 403);

        $order->load('items.product');
        return Inertia::render('Customer/Orders/Show', [
            'order' => $order
        ]);
    }
}
