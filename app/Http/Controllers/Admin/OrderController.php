<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Menampilkan daftar pesanan
    public function index(Request $request)
    {
        // Menghitung jumlah pesanan berdasarkan status pengerjaan
        $totalPesanan = Order::count();

        $menunggu = Order::where('order_status', 'Menunggu')->count();

        $diproses = Order::where('order_status', 'Diproses')->count();

        $dikirim = Order::where('order_status', 'Dikirim')->count();

        $selesai = Order::where('order_status', 'Selesai')->count();

        // Mengambil data pesanan
        $query = Order::with(['items.product', 'user']);

        // Mencari berdasarkan invoice atau nama penerima
        if ($request->filled('search')) {
            $cari = $request->search;

            $query->where(function ($q) use ($cari) {
                $q->where('invoice_number', 'like', '%' . $cari . '%')
                  ->orWhere('recipient_name', 'like', '%' . $cari . '%')
                  ->orWhereHas('user', function ($userQuery) use ($cari) {
                      $userQuery->where('name', 'like', '%' . $cari . '%');
                  });
            });
        }

        // Filter berdasarkan status pesanan
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->filled('order_date')) {
            $request->validate(['order_date' => 'date']);
            $query->whereDate('created_at', $request->order_date);
        }

        // Mengambil pesanan terbaru dan membaginya per halaman
        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact(
            'orders',
            'totalPesanan',
            'menunggu',
            'diproses',
            'dikirim',
            'selesai'
        ));
    }

    // Menampilkan detail pesanan
    public function show($id)
    {
        $order = Order::with(['items', 'user'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    // Menampilkan invoice dalam format siap cetak.
    public function invoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        return view('admin.orders.invoice', compact('order'));
    }

    // Mengubah status pengerjaan pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:Menunggu,Diproses,Dikirim,Selesai',
        ]);

        $order = Order::findOrFail($id);

        // Hanya mengubah status pengerjaan pesanan
        $order->update([
            'order_status' => $request->order_status
        ]);

        return redirect()->back()->with(
            'success',
            'Status pesanan berhasil diperbarui.'
        );
    }
}
