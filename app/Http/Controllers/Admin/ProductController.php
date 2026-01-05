<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $query = Product::query()->latest();

        // Vendor sees only their products
        if ($user->isVendor()) {
            $query->where('vendor_id', $user->id);
        }

        // Admin sees all (no filter)
        // Customer should never reach this route (route middleware)
        $products = $query->paginate(10)->through(fn ($product) => [
                   'id' => $product->id,
                   'product_id' => $product->product_id,
                   'category_id' => $product->category_id,
                   'name' => $product->name,
                   'description' => $product->description,
                   'price' => $product->formatted_price,
                   'stock' => $product->formatted_stock,
                   'image' => $product->image,
                   'created_at' => $product->created_at->format('d M, Y'),

                   // 👇 Policy-driven UI permissions
                   'can' => [
                    'update' => $user->can('update', $product),
                    'delete' => $user->can('delete', $product),
                ],
               ]);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::where('active', 1)->get(['id', 'store_name']);
        $categories = Category::get(['id', 'name']);

        return Inertia::render('Admin/Products/Create', [
            'vendors' => $vendors,
            'categories' => $categories
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'vendor_id'   => $validated['vendor_id'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'image'       => $imagePath,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
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
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $vendors = Vendor::where('active', 1)->get(['id', 'store_name']);
        $categories = Category::get(['id', 'name']);

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'vendors' => $vendors,
            'categories' => $categories
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'vendor_id' => $validated['vendor_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $product);

        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');

    }
}
