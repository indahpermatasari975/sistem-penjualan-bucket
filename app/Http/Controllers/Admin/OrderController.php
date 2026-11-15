<?php

namespace App\Http\Controllers\Admin; // <-- INI KUNCI UTAMANYA (Wajib ada \Admin)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // 1. Menampilkan daftar pesanan
    public function index()
    {
        // Ambil semua pesanan dari yang terbaru
        $orders = Order::orderBy('created_at', 'desc')->get();

        // Arahkan ke view admin/orders/index.blade.php
        return view('admin.orders.index', compact('orders'));
    }

    // 2. Menampilkan detail pesanan (halaman review)
    public function show($id)
    {
        // Cari pesanan berdasarkan ID
        $order = Order::findOrFail($id);

        // Arahkan ke view admin/orders/show.blade.php
        return view('admin.orders.show', compact('order'));
    }

    // 3. Update status pesanan (diproses, selesai, dll)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Ubah status sesuai pilihan dropdown
        $order->status = $request->status;
        $order->save();

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
