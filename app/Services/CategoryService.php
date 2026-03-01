<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function createCategory($data)
    {
        $category = new Category();
        $category->name = $data['name'];
        $category->description = $data['description'] ?? null;
        $category->slug = Str::slug($data['name']);
        $category->save();

        return $category;
    }

    public function validateCategory(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ];

        $messages = [
            'name.required' => 'Category name is required.',
            'name.unique' => 'Category name has already been taken.',
        ];


        return $request->validate($rules);
    }
}
