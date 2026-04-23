<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
            </a>
            {{ __('Detail Pesanan') }} #{{ $order->queue_number }}
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Order Stats -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title fw-bold mb-0 text-dark text-uppercase small tracking-wider">Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0">Menu</th>
                                    <th class="px-4 py-3 border-0 text-center">Harga</th>
                                    <th class="px-4 py-3 border-0 text-center">Qty</th>
                                    <th class="px-4 py-3 border-0 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->menu_name }}" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;">
                                            @elseif($item->menu && $item->menu->image)
                                                <img src="{{ asset('storage/' . $item->menu->image) }}" alt="{{ $item->menu->name }}" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item->menu_name ?? ($item->menu->name ?? 'Menu tidak ditemukan') }}</div>
                                                <div class="small text-muted">{{ $item->category_name ?? ($item->menu->category->name ?? '-') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($order->status == 'pending')
                                            <form action="{{ route('admin.orders.update-item', $order) }}" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" class="form-control form-control-sm text-center" style="width: 60px;">
                                                <button type="submit" class="btn btn-sm btn-light border">Update</button>
                                            </form>
                                        @else
                                            <span class="fw-bold">{{ $item->quantity }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-end fw-bold">Total Pembayaran</td>
                                    <td class="px-4 py-3 text-end fw-bold text-primary h5 mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 small text-uppercase tracking-wider">Status Pembayaran</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="payment_status" value="unpaid">
                            <button type="submit" class="btn {{ $order->payment_status === 'unpaid' ? 'btn-warning' : 'btn-outline-warning' }} px-4 shadow-sm fw-bold">Belum Lunas</button>
                        </form>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="payment_status" value="paid">
                            <button type="submit" class="btn {{ $order->payment_status === 'paid' ? 'btn-success' : 'btn-outline-success' }} px-4 shadow-sm fw-bold">Lunas</button>
                        </form>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="payment_status" value="cancelled">
                            <button type="submit" class="btn {{ $order->payment_status === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }} px-4 shadow-sm fw-bold">Batal</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Status Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4 small text-uppercase tracking-wider">Status Pesanan</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="btn {{ $order->status === 'pending' ? 'btn-secondary' : 'btn-outline-secondary' }} px-4 shadow-sm fw-bold">Menunggu</button>
                        </form>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" class="btn {{ $order->status === 'processing' ? 'btn-info text-white' : 'btn-outline-info' }} px-4 shadow-sm fw-bold">Proses</button>
                        </form>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn {{ $order->status === 'completed' ? 'btn-primary' : 'btn-outline-primary' }} px-4 shadow-sm fw-bold">Selesai</button>
                        </form>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn {{ $order->status === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }} px-4 shadow-sm fw-bold">Batal</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light px-4 border shadow-sm"> Kembali ke Daftar</a>
            </div>
        </div>

        <!-- Order Information Sidebar -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title fw-bold mb-0 text-dark small text-uppercase tracking-wider">Info Pelanggan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Nama</span>
                            <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">No. HP</span>
                            <span class="text-dark">{{ $order->customer_phone }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">No. Pesanan</span>
                            <span class="text-dark font-monospace">{{ $order->order_number }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Metode Bayar</span>
                            <span class="badge bg-info bg-opacity-10 text-info text-uppercase">{{ $order->payment_method }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Waktu</span>
                            <span class="text-dark">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="text-muted mb-2">Status Pembayaran</div>
                            @if($order->payment_status === 'paid')
                                <div class="alert alert-success border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99z"/>
                                    </svg>
                                    <span class="fw-bold">Lunas</span>
                                </div>
                            @elseif($order->payment_status === 'cancelled')
                                <div class="alert alert-danger border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill me-2" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    <span class="fw-bold">Batal</span>
                                </div>
                            @else
                                <div class="alert alert-warning border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle-fill me-2" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                    <span class="fw-bold">Belum Lunas</span>
                                </div>
                            @endif
                        </li>
                        <li class="list-group-item px-0 py-3">
                            <div class="text-muted mb-2">Status Pesanan</div>
                            @if($order->status === 'completed')
                                <div class="alert alert-primary border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all me-2" viewBox="0 0 16 16">
                                        <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                    </svg>
                                    <span class="fw-bold">Selesai</span>
                                </div>
                            @elseif($order->status === 'processing')
                                <div class="alert alert-info border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat me-2" viewBox="0 0 16 16">
                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-7.134 0a.25.25 0 0 0-.192.41l1.966 2.36a.25.25 0 0 0 .384 0l1.966-2.36a.25.25 0 0 0-.192-.41H4.4zm0 1 a 1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                    <span class="fw-bold">Proses</span>
                                </div>
                            @elseif($order->status === 'cancelled')
                                <div class="alert alert-danger border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill me-2" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                    <span class="fw-bold">Batal</span>
                                </div>
                            @else
                                <div class="alert alert-secondary border-0 py-2 d-flex align-items-center mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history me-2" viewBox="0 0 16 16">
                                        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.353l.56-.828c.193.13.373.272.54.421l-.661.76zm1.332.964s-.12-.132-.34-.336l.751-.66c.214.243.34.408.34.408l-.751.688zM14 5.514v-.522l.99-.101a8.11 8.11 0 0 1 .01 1.119l-.99-.092V5.514zm0 2.044v.453l.993.078c.01-.194.01-.39 0-.585l-.993.054v.053zm0 2.052v.456l.993.078c.01-.194.01-.39 0-.585l-.993.054v.053zm0 2.052v.456l.993.078c.01-.194.01-.39 0-.585l-.993.054v.053zM13 13h-1v1h1c.442 0 .8-.358.8-.8V13h-1.8zm-2 0H9v1h2v-1zm-2 0H7v1h2v-1zm-2 0H5v1h2v-1zm-2 0H3c-.442 0-.8-.358-.8.8V14h1.8v-1zM2 12V10h-1v2c0 .442.358.8.8.8H2v-1.8zm0-2V8h-1v2h1zm0-2V6h-1v2h1zm0-2V4h-1v2h1zm1.022-4.041a7.003 7.003 0 0 0-.985.299l.219.976c.383-.086.76-.2 1.126-.342l-.36-.933zm1.37-.71a7.01 7.01 0 0 0-.439.353l.56-.828c.193-.13.373-.272.54-.421l-.661.76zM8 2a6 6 0 1 1 0 12A6 6 0 0 1 8 2z"/>
                                    </svg>
                                    <span class="fw-bold">Pending</span>
                                </div>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
