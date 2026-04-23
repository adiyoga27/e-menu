<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.menu')->latest();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.menu');
        return view('admin.orders.show', compact('order'));
    }

    public function updateItem(Request $request, Order $order)
    {
        // For editing quantity
        $request->validate([
            'item_id' => 'required|exists:order_items,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $item = $order->items()->findOrFail($request->item_id);
        
        if ($request->quantity == 0) {
            $item->delete();
        } else {
            $item->update([
                'quantity' => $request->quantity,
                'subtotal' => $request->quantity * $item->price
            ]);
        }

        // Recalculate total
        $newTotal = $order->items()->sum('subtotal');
        $order->update(['total_amount' => $newTotal]);

        return redirect()->back()->with('success', 'Kuantitas pesanan diupdate. Total berubah menjadi Rp ' . number_format($newTotal, 0, ',', '.'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid,cancelled',
        ]);

        $data = [];
        if ($request->has('status')) {
            $data['status'] = $request->status;
        }
        if ($request->has('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }

        if (!empty($data)) {
            $order->update($data);
            return redirect()->back()->with('success', 'Status pesanan diperbarui.');
        }

        return redirect()->back();
    }

    public function report(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : now()->endOfDay();

        $query = Order::with('items')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $orders = (clone $query)->latest()->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->where('payment_status', 'paid')->sum('total_amount'),
        ];

        $dailyStats = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders, $date) {
            return (object) [
                'date' => $date,
                'total_orders' => $dayOrders->count(),
                'total_revenue' => $dayOrders->where('payment_status', 'paid')->sum('total_amount'),
                'orders' => $dayOrders // Include orders for detail display
            ];
        })->values();

        return view('admin.orders.report', compact('orders', 'startDate', 'endDate', 'summary', 'dailyStats'));
    }

    public function exportReport(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : now()->endOfDay();

        $orders = Order::with('items')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Headers
        $headers = ['No', 'Tanggal', 'No. Pesanan', 'Nama Pelanggan', 'Item', 'Total Tagihan', 'Metode Bayar', 'Status Bayar'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $column++;
        }

        $row = 2;
        foreach ($orders as $index => $order) {
            $items = $order->items->map(function($item) {
                return $item->menu_name . ' (' . $item->quantity . 'x)';
            })->implode(', ');

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $order->created_at->format('Y-m-d H:i'));
            $sheet->setCellValue('C' . $row, $order->order_number);
            $sheet->setCellValue('D' . $row, $order->customer_name);
            $sheet->setCellValue('E' . $row, $items);
            $sheet->setCellValue('F' . $row, $order->total_amount);
            $sheet->setCellValue('G' . $row, strtoupper($order->payment_method));
            $sheet->setCellValue('H' . $row, strtoupper($order->payment_status));
            $row++;
        }

        // Auto width for columns A to H
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Laporan_Penjualan_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"');
        $writer->save('php://output');
        exit;
    }
}
