@extends('layouts.front')

@section('header')
    @if($order->payment_method === 'qris' && $order->payment_status !== 'paid')
        <script type="text/javascript"
                src="https://app.midtrans.com/snap/snap.js"
                data-client-key="{{ config('app.midtrans.client_key') }}"></script>
    @endif
@endsection

@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-center p-8 mt-6 sm:mt-10">
    <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h2>
    <p class="text-gray-500 mb-6">Terima kasih, pesanan Anda sedang kami catat.</p>

    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl p-6 mb-6">
        <p class="text-indigo-600 font-semibold mb-1">Nomor Antrian Anda</p>
        <p class="text-6xl font-black text-indigo-700">{{ $order->queue_number }}</p>
    </div>

    <div class="text-left bg-gray-50 rounded-xl p-4 mb-6 text-sm border border-gray-100">
        <div class="flex justify-between mb-3 border-b border-gray-200 pb-2">
            <span class="text-gray-500 font-medium">Order ID:</span>
            <span class="font-bold text-gray-800">{{ $order->order_number }}</span>
        </div>
        <div class="flex justify-between mb-3 border-b border-gray-200 pb-2">
            <span class="text-gray-500 font-medium">Nama Pemesan:</span>
            <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
        </div>
        <div class="flex justify-between mb-3 border-b border-gray-200 pb-2">
            <span class="text-gray-500 font-medium">Total Tagihan:</span>
            <span class="font-bold text-orange-600 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500 font-medium">Status Pembayaran:</span>
            @if($order->payment_status === 'paid')
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Lunas</span>
            @else
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase tracking-wide">Belum Dibayar</span>
            @endif
        </div>
    </div>

    @if($order->payment_method === 'cashier')
    <div class="border-t border-gray-100 pt-6">
        <div class="bg-orange-50 border border-orange-100 rounded-xl p-4">
            <p class="text-orange-800 font-medium text-sm leading-relaxed">Silakan menuju ke kasir untuk melakukan pembayaran dengan menunjukkan detail pesanan ini.</p>
        </div>
    </div>
    @elseif($order->payment_method === 'qris' && $order->payment_status !== 'paid')
    <div class="border-t border-gray-100 pt-6 space-y-3">
        <p class="text-gray-600 font-medium mb-3 text-sm">Jika halaman pembayaran belum terbuka otomatis, klik tombol di bawah ini.</p>
        <button type="button" id="pay-button" class="flex justify-center items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl w-full transition-colors shadow-md">
            Bayar Pesanan Sekarang
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>
    @endif

    <div class="mt-6 pt-6 border-t border-gray-100">
        <a href="{{ route('front.index') }}" class="inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl w-full transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Halaman Utama
        </a>
    </div>
</div>

<script>
    // Clear cart upon successful order
    localStorage.removeItem('eMenuCart');

    @if($order->payment_method === 'qris' && $order->payment_status !== 'paid' && $order->payment_url)
        function triggerSnap() {
            const url = "{{ $order->payment_url }}";
            const snapToken = url.split('/').pop().split('?')[0]; 
            
            if (snapToken && window.snap) {
                window.snap.pay(snapToken, {
                    onSuccess: function(result) { window.location.reload(); },
                    onPending: function(result) { window.location.reload(); },
                    onError: function(result) { alert("Terjadi kesalahan pembayaran."); },
                    onClose: function() { console.log('Snap closed'); }
                });
            } else {
                console.error("Snap token not found or Snap.js not loaded");
            }
        }

        // Auto open on load
        document.addEventListener('DOMContentLoaded', function() {
            // Give a tiny delay to ensure script is ready
            setTimeout(triggerSnap, 1000);
            
            const payButton = document.getElementById('pay-button');
            if(payButton) {
                payButton.onclick = function() {
                    triggerSnap();
                };
            }
        });
    @endif
</script>
@endsection
