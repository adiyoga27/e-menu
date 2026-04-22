<x-app-layout>
    <x-slot name="header">
        {{ __('Pesanan Masuk') }}
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-2 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark">Daftar Antrian</h5>
                    <!-- Filter Status -->
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0">Antrian</th>
                                    <th class="px-4 py-3 border-0">Info Pelanggan</th>
                                    <th class="px-4 py-3 border-0 text-center">Total</th>
                                    <th class="px-4 py-3 border-0 text-center">Pembayaran</th>
                                    <th class="px-4 py-3 border-0 text-center">Status</th>
                                    <th class="px-4 py-3 border-0 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="display-6 fw-bold text-primary">{{ $order->queue_number }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-dark">{{ $order->customer_name }}</div>
                                        <div class="small text-muted">{{ $order->order_number }} • {{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center fw-bold text-dark">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="small fw-bold text-uppercase {{ $order->payment_method == 'qris' ? 'text-primary' : 'text-warning' }} mb-1">{{ $order->payment_method }}</div>
                                        @if($order->payment_status === 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success px-2">Lunas</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-2 text-dark">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($order->status === 'completed')
                                            <span class="badge bg-success px-3">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary px-3">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
