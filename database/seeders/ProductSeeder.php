<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = Vendor::all();
        $categories = Category::all();

        Product::factory()->count(50)->make()->each(function ($product) use ($vendors, $categories) {
            $product->vendor_id = $vendors->random()->id;
            $product->category_id = $categories->random()->id;
            $product->save();
        });

    }
}
