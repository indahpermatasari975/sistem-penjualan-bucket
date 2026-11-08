<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    // Method untuk menerima notifikasi otomatis (Webhook/HTTP Notification) dari server Midtrans
    public function handle(Request $request)
    {
        // 1. Konfigurasi kredensial Midtrans dengan Server Key Sandbox kita
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
        Config::$curlOptions = [
            CURLOPT_HTTPHEADER => [],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ];

        try {
            // 2. Ambil data notifikasi yang dikirimkan oleh Midtrans
            // Library Midtrans otomatis membaca raw JSON body yang dikirim oleh Midtrans
            $notif = new Notification();

            $orderId           = $notif->order_id;           // Nomor invoice pesanan (contoh: INV-20261104-XXXX)
            $transactionStatus = $notif->transaction_status; // Status: settlement, pending, capture, deny, expire, cancel
            $paymentType       = $notif->payment_type;       // Tipe: qris, gopay, bank_transfer, dll.
            $fraudStatus       = $notif->fraud_status;       // Status fraud (biasanya 'accept')

            // 3. Cari data pesanan di database berdasarkan invoice_number
            $order = Order::where('invoice_number', $orderId)->first();

            // Jika pesanan tidak ditemukan di database kita
            if (!$order) {
                return response()->json(['message' => 'Pesanan tidak ditemukan di sistem.'], 404);
            }

            // 4. Logika update status pesanan berdasarkan notifikasi Midtrans
            if ($transactionStatus == 'capture') {
                // Untuk transaksi Kartu Kredit
                if ($fraudStatus == 'challenge') {
                    // Transaksi dicurigai fraud
                    $order->update(['status' => 'Belum Dibayar']);
                } elseif ($fraudStatus == 'accept') {
                    // Transaksi kartu kredit sukses dan aman
                    $order->update(['status' => 'Lunas']);
                }
            } elseif ($transactionStatus == 'settlement') {
                // Pembayaran QRIS, GoPay, E-Wallet, dan Bank Transfer telah SUKSES diterima
                $order->update(['status' => 'Lunas']);
            } elseif ($transactionStatus == 'pending') {
                // Pembeli sudah memilih metode pembayaran namun belum mentransfer uangnya
                $order->update(['status' => 'Belum Dibayar']);
            } elseif ($transactionStatus == 'deny') {
                // Pembayaran ditolak oleh bank atau penyedia pembayaran
                $order->update(['status' => 'Dibatalkan']);
            } elseif ($transactionStatus == 'expire') {
                // Batas waktu pembayaran habis (kadaluarsa)
                $order->update(['status' => 'Kadaluarsa']);
            } elseif ($transactionStatus == 'cancel') {
                // Transaksi dibatalkan oleh pembeli atau merchant
                $order->update(['status' => 'Dibatalkan']);
            }

            // 5. Berikan respon HTTP 200 OK ke Midtrans agar Midtrans tahu webhook berhasil diterima
            return response()->json([
                'status'  => 'success',
                'message' => 'Webhook Midtrans berhasil diproses.',
            ], 200);

        } catch (\Exception $e) {
            // Tangkap jika terjadi error saat memproses notifikasi
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

