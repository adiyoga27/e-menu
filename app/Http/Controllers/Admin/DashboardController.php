<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $revenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        return view('dashboard', compact('totalOrders', 'todayOrders', 'revenue'));
    }
}
