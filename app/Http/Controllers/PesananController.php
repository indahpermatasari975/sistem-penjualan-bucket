<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // Menampilkan riwayat pesanan pembeli yang sedang login
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pesanan.riwayat', compact('orders'));
    }

    // Menampilkan detail pesanan milik pembeli yang sedang login
    public function show(int $id): View
    {
        $order = Order::with('items')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pesanan.detail', compact('order'));
    }
}
