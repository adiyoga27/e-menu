<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.menu')->latest();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.menu');
        return view('admin.orders.show', compact('order'));
    }

    public function updateItem(Request $request, Order $order)
    {
        // For editing quantity
        $request->validate([
            'item_id' => 'required|exists:order_items,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $item = $order->items()->findOrFail($request->item_id);
        
        if ($request->quantity == 0) {
            $item->delete();
        } else {
            $item->update([
                'quantity' => $request->quantity,
                'subtotal' => $request->quantity * $item->price
            ]);
        }

        // Recalculate total
        $newTotal = $order->items()->sum('subtotal');
        $order->update(['total_amount' => $newTotal]);

        return redirect()->back()->with('success', 'Kuantitas pesanan diupdate. Total berubah menjadi Rp ' . number_format($newTotal, 0, ',', '.'));
    }

    public function markCompleted(Order $order)
    {
        $order->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Pesanan diselesaikan.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate(['payment_status' => 'required']);
        $order->update(['payment_status' => $request->payment_status]);
        return redirect()->back()->with('success', 'Status Pembayaran diupdate.');
    }

    public function report()
    {
        $orders = Order::where('status', 'completed')->latest()->get();
        return view('admin.orders.report', compact('orders'));
    }
}
