<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('vendor')
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }
}
