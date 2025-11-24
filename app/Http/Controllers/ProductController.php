<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::query()
        ->when(
            $search,
            fn ($q) =>
            $q->where('name', 'like', "%{$search}%")
        )
        ->with('vendor')
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only('search'),
        ]);
    }
}
