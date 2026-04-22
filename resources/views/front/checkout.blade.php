@extends('layouts.front')

@section('content')
<div x-data="checkoutPage()" class="w-full">
    <div class="max-w-2xl mx-auto mt-6 bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100 overflow-hidden relative pb-10">
        <!-- Decorative Top Bar -->
        <div class="h-2 bg-gradient-to-r from-orange-400 to-red-500 w-full"></div>
        
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('front.index') }}" class="text-gray-400 hover:text-orange-500 bg-gray-50 border border-gray-200 p-2.5 rounded-full shadow-sm hover:bg-orange-50 hover:border-orange-200 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Selesaikan Pembayaran</h2>
                    <p class="text-xs font-medium text-gray-400 mt-0.5">Selesaikan detail pesanan Anda</p>
                </div>
            </div>
            
        </div>
        
        <div class="p-6 sm:p-8">
            <template x-if="cart.length === 0">
                <div class="text-center py-16">
                    <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-sm">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-xl text-gray-800 mb-2">Keranjang masih kosong</h3>
                    <p class="text-gray-500 mb-8 max-w-xs mx-auto">Silakan kembali dan pilih menu makanan favorit Anda terlebih dahulu.</p>
                    <a href="{{ route('front.index') }}" class="inline-block bg-gradient-to-r from-orange-400 to-orange-500 text-white px-8 py-3.5 rounded-xl font-bold shadow-md hover:shadow-lg hover:from-orange-500 hover:to-orange-600 transition-all transform hover:-translate-y-0.5">Lihat Menu Makanan</a>
                </div>
            </template>
            
            <div x-show="cart.length > 0" style="display: none;" class="space-y-8">
                <!-- List Pesanan -->
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                        <span class="bg-orange-100 text-orange-500 p-1.5 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </span>
                        Ringkasan Pesanan
                    </h3>
                    <div class="bg-white rounded-2xl p-2 border border-gray-100 shadow-sm">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex justify-between items-center p-3 sm:p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors rounded-xl">
                                <div class="flex-1 pr-4">
                                    <h4 class="font-bold text-gray-800" x-text="item.name"></h4>
                                    <p class="text-orange-500 text-sm font-semibold mt-0.5" x-text="'Rp ' + formatRupiah(item.price) + ' / item'"></p>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 px-2 py-1.5 rounded-lg border border-gray-200">
                                    <button type="button" @click="decreaseQty(item.id)" class="w-7 h-7 flex items-center justify-center bg-white border border-gray-200 shadow-sm rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition-colors">-</button>
                                    <span class="font-extrabold w-6 text-center text-gray-800 text-sm" x-text="item.qty"></span>
                                    <button type="button" @click="increaseQty(item.id)" class="w-7 h-7 flex items-center justify-center bg-white border border-gray-200 shadow-sm rounded-md text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 transition-colors">+</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Form Checkout -->
                <form id="checkoutForm" action="{{ route('front.checkout') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                    
                    <div class="pt-4 border-t border-dashed border-gray-200">
                        <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                            <span class="bg-orange-100 text-orange-500 p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            Data Pemesan
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Pemesan <span class="text-orange-500">*</span></label>
                                <input type="text" name="customer_name" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-orange-500 focus:ring-orange-500 p-3.5 bg-gray-50 focus:bg-white transition-colors" placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Nomor HP / WhatsApp <span class="text-orange-500">*</span></label>
                                <input type="tel" name="customer_phone" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-orange-500 focus:ring-orange-500 p-3.5 bg-gray-50 focus:bg-white transition-colors" placeholder="Contoh: 08123456789">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-dashed border-gray-200">
                        <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                            <span class="bg-orange-100 text-orange-500 p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </span>
                            Metode Pembayaran <span class="text-orange-500">*</span>
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="cashier" class="peer sr-only" required checked>
                                <div class="rounded-xl border-2 border-gray-100 bg-white p-5 text-center group-hover:border-orange-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-md transition-all relative overflow-hidden">
                                    <div class="absolute inset-0 bg-orange-100 opacity-0 peer-checked:opacity-20 transition-opacity"></div>
                                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <div class="bg-orange-500 rounded-full p-0.5">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <svg class="mx-auto h-8 w-8 mb-3 text-gray-400 peer-checked:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm-5-3v-3m0 0V7m0 3h3m-3 0H9m-2 6h2m6 0h2"></path></svg>
                                    <span class="font-bold text-sm text-gray-600 peer-checked:text-orange-700 block">Bayar di Kasir</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="qris" class="peer sr-only" required>
                                <div class="rounded-xl border-2 border-gray-100 bg-white p-5 text-center group-hover:border-orange-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-md transition-all relative overflow-hidden">
                                    <div class="absolute inset-0 bg-orange-100 opacity-0 peer-checked:opacity-20 transition-opacity"></div>
                                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <div class="bg-orange-500 rounded-full p-0.5">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <svg class="mx-auto h-8 w-8 mb-3 text-gray-400 peer-checked:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    <span class="font-bold text-sm text-gray-600 peer-checked:text-orange-700 block">QRIS</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 mt-6 border-t border-gray-100 bg-gray-50 -mx-6 -mb-8 px-6 pb-8 rounded-b-3xl">
                        <div class="flex justify-between items-end mb-5">
                            <div>
                                <span class="text-gray-500 font-semibold text-sm">Total Tagihan</span>
                                <p class="text-xs text-gray-400 mt-0.5">Termasuk pajak & biaya admin</p>
                            </div>
                            <span class="text-2xl sm:text-3xl font-black text-orange-600 tracking-tight" x-text="'Rp ' + formatRupiah(totalPrice)"></span>
                        </div>
                        <button type="submit" @click="saveCartAndSubmit($event)" class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 focus:ring-4 focus:ring-orange-200 text-white font-extrabold text-lg py-4 px-4 rounded-xl shadow-[0_4px_14px_0_rgb(249,115,22,0.39)] hover:shadow-[0_6px_20px_rgba(249,115,22,0.23)] hover:-translate-y-1 transition-all flex justify-center items-center gap-2 transform active:scale-95" :disabled="cart.length === 0">
                            Konfirmasi & Proses Pesanan <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function checkoutPage() {
    return {
        cart: [],
        init() {
            const stored = localStorage.getItem('eMenuCart');
            if(stored) {
                try {
                    this.cart = JSON.parse(stored);
                } catch(e) {
                    this.cart = [];
                }
            }
        },
        saveCart() {
            localStorage.setItem('eMenuCart', JSON.stringify(this.cart));
        },
        increaseQty(id) {
            const index = this.cart.findIndex(item => item.id === id);
            if(index > -1) {
                this.cart[index].qty++;
                this.saveCart();
            }
        },
        decreaseQty(id) {
            const index = this.cart.findIndex(item => item.id === id);
            if(index > -1) {
                if(this.cart[index].qty > 1) {
                    this.cart[index].qty--;
                } else {
                    this.cart.splice(index, 1);
                }
                this.saveCart();
            }
        },
        saveCartAndSubmit(e) {
            // Clearing the cart state will happen after successful POST. 
            // We can let the server handle the post. Just store it one last time to be safe.
            this.saveCart();
            return true;
        },
        get totalPrice() {
            return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
        },
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    }
}
</script>
@endsection
