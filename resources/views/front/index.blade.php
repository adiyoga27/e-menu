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
                                <div class="flex items-center justify-between mt-3 h-10">
                                    <span class="font-bold text-orange-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    
                                    <template x-if="getQty({{ $menu->id }}) === 0">
                                        <button @click="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})" 
                                            class="bg-orange-50 text-orange-500 hover:bg-orange-500 hover:text-white rounded-full p-2 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-orange-300 shadow-sm hover:shadow">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </button>
                                    </template>
                                    
                                    <template x-if="getQty({{ $menu->id }}) > 0">
                                        <div class="flex items-center gap-1 bg-orange-50 rounded-full p-1 border border-orange-100 shadow-sm">
                                            <button @click="decreaseQty({{ $menu->id }})" class="w-7 h-7 flex items-center justify-center bg-white rounded-full text-orange-600 hover:bg-orange-100 transition-colors shadow-sm focus:outline-none">-</button>
                                            <span class="font-bold w-5 text-center text-orange-600 text-sm" x-text="getQty({{ $menu->id }})"></span>
                                            <button @click="increaseQty({{ $menu->id }})" class="w-7 h-7 flex items-center justify-center bg-orange-500 rounded-full text-white hover:bg-orange-600 transition-colors shadow-sm focus:outline-none">+</button>
                                        </div>
                                    </template>
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
        <div class="bg-gray-900 rounded-full shadow-2xl p-4 flex items-center justify-between text-white cursor-pointer hover:bg-gray-800 transition-colors" @click="proceedToCheckout()">
            <div class="flex items-center">
                <div class="bg-orange-500 w-10 h-10 rounded-full flex items-center justify-center font-bold relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-text="totalItems" class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex items-center justify-center rounded-full leading-none shadow-sm"></span>
                </div>
                <div class="ml-4">
                    <p class="text-xs text-gray-400 font-medium">Total Tagihan</p>
                    <p class="font-bold text-lg leading-none" x-text="'Rp ' + formatRupiah(totalPrice)"></p>
                </div>
            </div>
            <div class="font-bold px-5 bg-orange-500 hover:bg-orange-600 transition-colors py-2 rounded-full flex items-center gap-2 shadow-sm">
                Lanjut Checkout <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>
    </div>
</div>

<script>
function cartSystem() {
    return {
        activeCategory: {{ $categories->first()->id ?? 'null' }},
        cart: [],
        init() {
            // Restore from local storage on load
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
        addToCart(id, name, price) {
            const index = this.cart.findIndex(item => item.id === id);
            if(index > -1) {
                this.cart[index].qty++;
            } else {
                this.cart.push({ id, name, price, qty: 1 });
            }
            this.saveCart();
        },
        proceedToCheckout() {
            this.saveCart();
            window.location.href = "{{ route('front.checkout.form') }}";
        },
        getQty(id) {
            const item = this.cart.find(i => i.id === id);
            return item ? item.qty : 0;
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
