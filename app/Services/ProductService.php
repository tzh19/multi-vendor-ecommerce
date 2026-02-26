<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function validateProduct(Request $request, $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($user->role === 'admin') {
            $rules['vendor_id'] = 'required|exists:vendors,id';
        }

        $validated = $request->validate($rules);

        if ($user->role === 'vendor') {
            $vendor = $user->vendor;
            if (!$vendor) {
                return back()->withErrors(['error' => 'Vendor profile not found.']);
            }

            // Add vendor_id directly to validated array
            $validated['vendor_id'] = $vendor->id;
        }

        return $validated;
    }

    // Helper method to handle image upload
    public function uploadImage($image)
    {
        if ($image) {
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');
            return $path;
        }
        return null;
    }

    // Method to create a product
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

}
