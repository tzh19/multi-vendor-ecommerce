<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $weeklySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->selectRaw('DATE(created_at) as day, SUM(total_price) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels = $weeklySales->pluck('day')->map(function ($date) {
            return Carbon::parse($date)->format('D'); // Mon, Tue, etc.
        });

        $data = $weeklySales->pluck('total');

        return inertia('Dashboard', [
            'stats' => [
                'totalSales' => $weeklySales->sum('total'),
                'orders' => $weeklySales->count(),
                'activeVendors' => \App\Models\Vendor::count(),
            ],
            'chart' => [
                'labels' => $labels,
                'data' => $data,
            ],
        ]);

    }
}
