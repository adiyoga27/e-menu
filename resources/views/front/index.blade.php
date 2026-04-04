@extends('layouts.front')

@section('content')
<div x-data="cartSystem()" class="w-full">
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Category Tabs -->
    <div class="flex overflow-x-auto space-x-2 py-2 mb-6 scrollbar-hide">
        @foreach($categories as $category)
            <button @click="activeCategory = {{ $category->id }}"
                :class="{'bg-orange-500 text-white shadow-md': activeCategory === {{ $category->id }}, 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== {{ $category->id }}}"
                class="whitespace-nowrap px-5 py-2 rounded-full font-semibold transition-all duration-300 ease-in-out">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menus list -->
    <div class="space-y-6">
        @foreach($categories as $category)
            <div x-show="activeCategory === {{ $category->id }}" x-transition.opacity.duration.300ms style="display: none;">
                @if($category->menus->isEmpty())
                    <p class="text-gray-500 italic text-center py-8">Menu belum tersedia di kategori ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($category->menus as $menu)
                        <div class="flex bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 group p-3">
                            <!-- Menu Image Placeholder if any -->
                            <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gray-200 rounded-xl flex-shrink-0 relative overflow-hidden">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $menu->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2 md:line-clamp-3">{{ $menu->description }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="font-bold text-orange-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    
                                    <button @click="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})" 
                                        class="bg-orange-50 text-orange-500 hover:bg-orange-500 hover:text-white rounded-full p-2 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Floating Cart Bar -->
    <div x-show="cart.length > 0" x-transition.translate.y.duration.500ms class="fixed bottom-0 mt-5 inset-x-0 w-full sm:mx-auto max-w-4xl lg:max-w-4xl xl:max-w-6xl z-40 p-4" style="display: none;">
        <div class="bg-gray-900 rounded-full shadow-2xl p-4 flex items-center justify-between text-white cursor-pointer hover:bg-gray-800 transition-colors" @click="isCheckoutOpen = true">
            <div class="flex items-center">
                <div class="bg-orange-500 w-10 h-10 rounded-full flex items-center justify-center font-bold relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-text="totalItems" class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex items-center justify-center rounded-full leading-none"></span>
                </div>
                <div class="ml-4">
                    <p class="text-xs text-gray-400 font-medium">Total Tagihan</p>
                    <p class="font-bold text-lg leading-none" x-text="'Rp ' + formatRupiah(totalPrice)"></p>
                </div>
            </div>
            <div class="font-semibold px-4 flex items-center gap-2">
                Checkout <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Checkout Modal / Offcanvas -->
    <div x-show="isCheckoutOpen" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-70 flex items-end sm:items-center justify-center lg:p-4" style="display: none;">
        <div x-show="isCheckoutOpen" x-transition.opacity.duration.300ms class="absolute inset-0" @click="isCheckoutOpen = false"></div>
        <div x-show="isCheckoutOpen" x-transition.translate.y.duration.400ms class="bg-white w-full sm:w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl h-[90vh] sm:h-auto sm:max-h-[85vh] relative z-10 flex flex-col shadow-2xl flex-grow overflow-hidden">
            <div class="p-4 border-b flex justify-between items-center bg-white sticky top-0 z-20">
                <h2 class="text-xl font-bold text-gray-800">Keranjang Pesanan</h2>
                <button @click="isCheckoutOpen = false" class="text-gray-500 hover:bg-gray-100 p-2 rounded-full focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-4 pb-[140px]">
                <template x-if="cart.length === 0">
                    <div class="text-center py-10">
                        <p class="text-gray-500">Keranjang masih kosong.</p>
                    </div>
                </template>
                
                <div class="space-y-4 mb-6">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between items-center border-b pb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800" x-text="item.name"></h4>
                                <p class="text-orange-500 text-sm font-medium" x-text="'Rp ' + formatRupiah(item.price) + ' / item'"></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button @click="decreaseQty(item.id)" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200">-</button>
                                <span class="font-bold w-6 text-center" x-text="item.qty"></span>
                                <button @click="increaseQty(item.id)" class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200">+</button>
                            </div>
                        </div>
                    </template>
                </div>

                <form id="checkoutForm" action="{{ route('front.checkout') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pembeli <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 p-3" placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_phone" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 p-3" placeholder="Contoh: 08123456789">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 mt-4">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cashier" class="peer sr-only" required checked>
                                <div class="rounded-xl border border-gray-200 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-600 transition-all">
                                    <svg class="mx-auto h-6 w-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="font-medium text-sm">Bayar di Kasir</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" class="peer sr-only" required>
                                <div class="rounded-xl border border-gray-200 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-600 transition-all">
                                    <svg class="mx-auto h-6 w-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    <span class="font-medium text-sm">QRIS</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer Checkout button sticky -->
            <div class="bg-white border-t p-4 absolute bottom-0 inset-x-0 w-full z-30 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600 font-medium text-sm sm:text-base">Total Pembayaran</span>
                    <span class="text-xl sm:text-2xl font-bold text-orange-600 leading-none" x-text="'Rp ' + formatRupiah(totalPrice)"></span>
                </div>
                <button type="submit" form="checkoutForm" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 sm:py-4 px-4 rounded-xl shadow-lg transition-colors flex justify-center items-center gap-2" :disabled="cart.length === 0">
                    Selesaikan Pesanan <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cartSystem() {
    return {
        activeCategory: {{ $categories->first()->id ?? 'null' }},
        cart: [],
        isCheckoutOpen: false,
        init() {
            // Can persist using localStorage if wanted
            // const stored = localStorage.getItem('cart');
            // if(stored) this.cart = JSON.parse(stored);
        },
        addToCart(id, name, price) {
            const index = this.cart.findIndex(item => item.id === id);
            if(index > -1) {
                this.cart[index].qty++;
            } else {
                this.cart.push({ id, name, price, qty: 1 });
            }
        },
        increaseQty(id) {
            const index = this.cart.findIndex(item => item.id === id);
            if(index > -1) this.cart[index].qty++;
        },
        decreaseQty(id) {
            const index = this.cart.findIndex(item => item.id === id);
            if(index > -1) {
                if(this.cart[index].qty > 1) {
                    this.cart[index].qty--;
                } else {
                    this.cart.splice(index, 1);
                }
            }
            if(this.cart.length === 0) this.isCheckoutOpen = false;
        },
        get totalItems() {
            return this.cart.reduce((total, item) => total + item.qty, 0);
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
