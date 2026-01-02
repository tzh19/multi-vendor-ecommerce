<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()
        ->withCount('products')
        ->orderBy('name')
        ->get(['id', 'name', 'description']);

        return Inertia::render('Home', [
            'categories' => $categories
        ]);
    }
}
