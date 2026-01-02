<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        abort_if(!$category->is_active, 404);

        $products = $category->products()
        ->where('is_active', true)
        ->paginate(12);

        return Inertia::render('Category/Show', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
