<x-app-layout>
    <x-slot name="header">
        {{ __('Laporan Penjualan') }}
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            <!-- Filter -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.report') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-12 col-md-3">
                            <x-input-label value="Tanggal Mulai" />
                            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control shadow-sm">
                        </div>
                        <div class="col-12 col-md-3">
                            <x-input-label value="Tanggal Selesai" />
                            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control shadow-sm">
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">Filter Laporan</button>
                        </div>
                        <div class="col-12 col-md-3">
                            <a href="{{ route('admin.orders.report.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-success w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel" viewBox="0 0 16 16">
                                    <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                </svg>
                                Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart-check" viewBox="0 0 16 16">
                                    <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-white bg-opacity-75 small text-uppercase fw-bold mb-1">Total Pesanan</div>
                                <h3 class="fw-bold mb-0">{{ $summary['total_orders'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                    <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-white bg-opacity-75 small text-uppercase fw-bold mb-1">Total Pendapatan</div>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail List -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">Rincian Per Hari</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0">Tanggal</th>
                                    <th class="px-4 py-3 border-0 text-center">Jumlah Pesanan</th>
                                    <th class="px-4 py-3 border-0 text-end">Pendapatan</th>
                                    <th class="px-4 py-3 border-0 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyStats as $stat)
                                <tr>
                                    <td class="px-4 py-3 fw-medium text-dark">{{ \Carbon\Carbon::parse($stat->date)->format('d F Y') }}</td>
                                    <td class="px-4 py-3 text-center">{{ $stat->total_orders }}</td>
                                    <td class="px-4 py-3 text-end fw-bold text-dark">Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $loop->index }}">
                                            Detail Pesanan
                                        </button>

                                        <!-- Modal Detail Per Hari -->
                                        <div class="modal fade" id="detailModal{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom-0">
                                                        <h5 class="modal-title fw-bold">Detail Pesanan - {{ \Carbon\Carbon::parse($stat->date)->format('d F Y') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <div class="table-responsive text-start">
                                                            <table class="table table-sm align-middle mb-0">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="px-3 py-2 border-0">Order ID</th>
                                                                        <th class="px-3 py-2 border-0">Pelanggan</th>
                                                                        <th class="px-3 py-2 border-0">Item</th>
                                                                        <th class="px-3 py-2 border-0 text-end">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($stat->orders as $order)
                                                                    <tr>
                                                                        <td class="px-3 py-3 font-monospace small"><a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold">{{ $order->order_number }}</a></td>
                                                                        <td class="px-3 py-3 small">{{ $order->customer_name }}</td>
                                                                        <td class="px-3 py-3 small">
                                                                            @foreach($order->items as $item)
                                                                                <div class="text-nowrap">{{ $item->menu_name }} <span class="text-muted">(x{{ $item->quantity }})</span></div>
                                                                            @endforeach
                                                                        </td>
                                                                        <td class="px-3 py-3 text-end fw-bold text-dark">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-modal="dismiss">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
</x-app-layout>
