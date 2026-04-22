<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FrontController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->with(['menus' => function($q) {
            $q->where('is_ready', true);
        }])->get();

        return view('front.index', compact('categories'));
    }

    public function checkoutForm()
    {
        return view('front.checkout');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cashier,qris',
            'cart' => 'required|string', 
        ]);

        $cart = json_decode($request->cart, true);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        $today = Carbon::today();
        $lastOrder = Order::whereDate('created_at', $today)->orderBy('queue_number', 'desc')->first();
        $queueNumber = $lastOrder ? $lastOrder->queue_number + 1 : 1;

        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $totalAmount = 0;
        foreach ($cart as $item) {
            $menu = Menu::find($item['id']);
            if($menu) {
                $totalAmount += $menu->price * $item['qty'];
            }
        }

        $order = Order::create([
            'order_number' => $orderNumber,
            'queue_number' => $queueNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            $menu = Menu::find($item['id']);
            if($menu) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['qty'],
                    'price' => $menu->price,
                    'subtotal' => $menu->price * $item['qty'],
                ]);
            }
        }

        if ($request->payment_method === 'qris') {
            return redirect()->route('payment.qris', $order->id);
        }

        return redirect()->route('front.success', $order->id);
    }

    public function success(Order $order)
    {
        return view('front.success', compact('order'));
    }
}
