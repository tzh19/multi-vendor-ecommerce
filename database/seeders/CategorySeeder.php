<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 main categories
        Category::factory(5)->create();

        Category::factory(10)->create([
            'parent_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
        ]);

    }
}
