<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TVMonitorController extends Controller
{
    /**
     * Tampilkan halaman monitor TV.
     */
    public function index()
    {
        return view('tv.monitor');
    }

    /**
     * Ambil data antrean terbaru untuk update realtime.
     */
    public function getQueue()
    {
        $orders = Order::whereIn('status', ['pending', 'processing'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'order_number' => str_pad($order->queue_number, 3, '0', STR_PAD_LEFT), // Gunakan queue_number berformat 3 digit
                    'customer_name' => $order->customer_name,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at->diffForHumans(),
                ];
            });

        return response()->json($orders);
    }
}
