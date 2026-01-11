<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;

class VendorOrderController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        $orders = Order::whereHas('items.product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->with(['user'])
        ->with(['items' => function ($query) use ($vendor) {
            $query->whereHas('product', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })->with('product');
        }])
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

        return Inertia::render('Vendor/Orders/Index', [
            'orders' => $orders,
        ]);

    }

    public function show(Order $order)
    {
        $vendor = auth()->user()->vendor;

        $order->load(['user', 'items.product']);

        $containsVendorProducts = $order->items->contains(function ($item) use ($vendor) {
            return $item->product->vendor_id === $vendor->id;
        });


        if (! $containsVendorProducts) {
            abort(403, 'Unauthorized: This order does not belong to you');
        }

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

        return inertia('Vendor/Orders/Show', [
            'order' => $transformedOrder,
        ]);

    }

    public function updateStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:processing,confirmed,shipped,completed,cancelled',
        ]);


        abort_if(
            ! $order->belongsToVendor(auth()->id()),
            403,
            'Unauthorized action.'
        );

        $allowedTransitions = [
            'processing' => ['confirmed', 'cancelled'],
            'confirmed'  => ['shipped'],
            'shipped'    => ['completed'],
            'completed'  => [],
            'cancelled'  => [],
        ];

        $currentStatus = $order->status;
        $newStatus = $request->status;

        abort_if(
            ! in_array($newStatus, $allowedTransitions[$currentStatus] ?? []),
            422,
            'Invalid status transition.'
        );

        $order->update([
            'status' => $newStatus,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

}
