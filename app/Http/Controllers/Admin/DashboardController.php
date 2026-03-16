<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalUsers' => User::where('role', 'customer')->count(),
            'totalRevenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'recentOrders' => Order::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
