<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class WeeklyOrdersSeeder extends Seeder
{
    public function run()
    {
        // Create 100 orders randomly distributed in last 7 days
        Order::factory()->count(100)->create();
    }
}
