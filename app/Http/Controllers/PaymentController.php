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

        // Set Midtrans Config
        \Midtrans\Config::$serverKey = config('app.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ),
            'customer_details' => array(
                'first_name' => $order->customer_name,
                'phone' => $order->customer_phone,
            ),
            'callbacks' => array(
                'finish' => route('front.success', $order->id)
            )
        );

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            
            $order->update([
                'payment_url' => $paymentUrl
            ]);
            
            return redirect()->away($paymentUrl);
        } catch (\Exception $e) {
            return redirect()->route('front.success', $order->id)->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        // Set Midtrans Config
        \Midtrans\Config::$serverKey = config('app.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            \Illuminate\Support\Facades\Log::info('Midtrans Webhook Received: ', $request->all());
            $notification = new \Midtrans\Notification();

            $transaction = $notification->transaction_status ?? null;
            $type = $notification->payment_type ?? null;
            $orderId = $notification->order_id ?? null;
            $fraud = $notification->fraud_status ?? null;

            if (!$orderId) {
                return response()->json(['message' => 'Notification received, but no order ID found'], 200);
            }

            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found, but acknowledged'], 200);
            }

            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    // $order->update(['payment_status' => 'challenge']);
                } else if ($fraud == 'accept') {
                    $order->update(['payment_status' => 'paid']);
                }
            } else if ($transaction == 'settlement') {
                $order->update(['payment_status' => 'paid']);
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $order->update(['payment_status' => 'unpaid']);
            }

            return response()->json(['message' => 'Webhook processed successfully']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Acknowledged with error: ' . $e->getMessage()], 200);
        }
    }
}
