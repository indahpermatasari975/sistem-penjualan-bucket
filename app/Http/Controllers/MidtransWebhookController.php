<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    // Menerima notifikasi otomatis (Webhook) dari server Midtrans
    public function handle(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        // Opsi cURL untuk Windows/Laragon (menghindari error SSL)
        Config::$curlOptions = [
            CURLOPT_HTTPHEADER => [],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ];

        try {
            // 2. Tangkap data notifikasi dari Midtrans
            $notif = new Notification();
            $orderId           = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;

            // 3. Cari pesanan di database
            $order = Order::where('invoice_number', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
            }

            // 4. Update status pesanan berdasarkan notifikasi
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->update(['status' => 'Lunas']);
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->update(['status' => 'Lunas']); // Pembayaran sukses (QRIS, VA, dll)
            } elseif ($transactionStatus == 'pending') {
                $order->update(['status' => 'Belum Dibayar']);
            } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
                $order->update(['status' => 'Dibatalkan']);
            } elseif ($transactionStatus == 'expire') {
                $order->update(['status' => 'Kadaluarsa']);
            }

            // 5. Respon sukses ke Midtrans
            return response()->json(['status' => 'success', 'message' => 'Webhook diproses.'], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
