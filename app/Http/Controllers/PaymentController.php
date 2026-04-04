<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function qris(Order $order)
    {
        if ($order->payment_url) {
            return redirect()->away($order->payment_url);
        }

        $apiKey = 'API-e99676456b8a87ac4d35a411ece85932caa51b45654754f5';
        
        $response = Http::withHeaders([
            'X-API-Key' => $apiKey,
            'Content-Type' => 'application/json'
        ])->post('https://www.bayar.gg/api/create-payment.php', [
            'amount' => $order->total_amount,
            'description' => 'Pembayaran Pesanan ' . $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'payment_method' => 'qris',
            'redirect_url' => route('front.success', $order->id)
        ]);

        if ($response->successful() && isset($response['success']) && $response['success']) {
            $order->update([
                'invoice_id' => $response['payment']['invoice_id'] ?? null,
                'payment_url' => $response['payment_url'] ?? null
            ]);
            
            return redirect()->away($response['payment_url']);
        }

        return redirect()->route('front.success', $order->id)->with('error', 'Gagal membuka QRIS otomatis. Silakan bayar di kasir.');
    }
}
