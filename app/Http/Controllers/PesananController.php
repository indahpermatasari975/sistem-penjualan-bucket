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
        // 1. Ambil semua pesanan user
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. SINKRONISASI OTOMATIS KE MIDTRANS
        foreach ($orders as $order) {
            if ($order->status == 'Belum Dibayar' && $order->snap_token) {

                // Konfigurasi Midtrans
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
                \Midtrans\Config::$curlOptions = [
                    CURLOPT_HTTPHEADER => [],
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                ];

                try {
                    // Tanya status pesanan ke API Midtrans
                    $status = (array) \Midtrans\Transaction::status($order->invoice_number);

                    // Jika statusnya settlement (lunas) atau capture
                    if ($status['transaction_status'] == 'settlement' || $status['transaction_status'] == 'capture') {
                        $order->update(['status' => 'Lunas']); // Ubah di database
                    }
                } catch (\Exception $e) {
                    // Abaikan jika transaksi belum ada di Midtrans
                }
            }
        }

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
