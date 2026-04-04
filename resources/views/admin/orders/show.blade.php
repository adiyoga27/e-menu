<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pesanan {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Status -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <span class="text-gray-500 text-sm font-medium">Nomor Antrian</span>
                    <span class="text-5xl font-black text-indigo-600">{{ $order->queue_number }}</span>
                    <div class="mt-4 w-full">
                        @if($order->status == 'pending')
                            <form action="{{ route('admin.orders.complete', $order) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-colors">Selesaikan Pesanan</button>
                            </form>
                        @else
                            <div class="bg-green-100 text-green-800 text-center py-2 rounded-lg font-bold shadow-sm">Pesanan Telah Selesai</div>
                        @endif
                    </div>
                </div>

                <!-- Info Customer -->
                <div class="col-span-1 md:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-bold text-lg">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nomor HP</p>
                            <p class="font-bold text-lg">{{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu Order</p>
                            <p class="font-bold text-md">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Terakhir Update</p>
                            <p class="font-bold text-md">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-50 p-4 rounded-xl border gap-4">
                        <div class="w-full md:w-auto">
                            <p class="text-sm text-gray-500 mb-1">Status Pembayaran (<span class="font-bold uppercase">{{ $order->payment_method }}</span>)</p>
                            <form action="{{ route('admin.orders.payment', $order) }}" method="POST" class="flex items-center space-x-2">
                                @csrf @method('PUT')
                                <select name="payment_status" class="rounded border-gray-300 text-sm focus:ring-indigo-500">
                                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded-md text-sm hover:bg-blue-600 shadow-sm transition-colors">Update Bar</button>
                            </form>
                        </div>
                        <div class="text-left md:text-right w-full md:w-auto border-t md:border-t-0 pt-3 md:pt-0 border-gray-200">
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                            <p class="font-bold text-2xl text-orange-600 leading-none">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Item Pesanan</h3>
                    <p class="text-sm text-gray-500">Admin dapat mengubah jumlah pesanan (mengurangi/menghapus) jika stok menu tidak cukup. Biaya akan disesuaikan otomatis.</p>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Harga @</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-900">{{ $item->menu ? $item->menu->name : 'Menu Dihapus' }}</div>
                                <div class="text-xs text-gray-500 sm:hidden">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($order->status == 'pending')
                                <form action="{{ route('admin.orders.update-item', $order) }}" method="POST" class="flex items-center justify-center space-x-1 sm:space-x-2">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" class="w-14 sm:w-16 text-center text-sm rounded border-gray-300 p-1">
                                    <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 px-2 py-1.5 rounded shadow-sm">Ubah</button>
                                </form>
                                @else
                                <span class="font-bold">{{ $item->quantity }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right hidden sm:table-cell">
                                <div class="text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
