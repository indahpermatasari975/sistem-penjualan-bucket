<?php

// Konfigurasi Midtrans Payment Gateway
return [
    // Server Key Midtrans Sandbox (didapat dari dashboard Midtrans -> Pengaturan -> Access Keys)
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-XXXXX-GANTI-DENGAN-KEY-KAMU'),

    // Client Key Midtrans Sandbox (untuk script frontend Snap JS)
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-XXXXX-GANTI-DENGAN-KEY-KAMU'),

    // Mode Production: set false untuk testing (Sandbox), set true untuk live transaksi asli
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Sanitasi data otomatis
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    // 3D Secure untuk keamanan pembayaran kartu kredit
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];

