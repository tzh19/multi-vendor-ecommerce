<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = auth()->user()
        ->cartItems()
        ->with('product')
        ->get();

        return Inertia::render('Carts/Index', [
            'cartItems' => $cartItems,
            'cartCount' => $cartItems->sum('quantity'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $productId = $request->product_id;

        $cartItem = $user->cartItems()->firstOrNew(['product_id' => $productId]);
        $cartItem->quantity += 1;
        $cartItem->save();

        return back()->with('success', 'Product added to cart');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::findOrFail($id);

        if ($cart->user_id !==  auth()->user()->id) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Cart removed successfully');
    }
}
