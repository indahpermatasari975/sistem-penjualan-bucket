<?php

return [
    // Server Key dari Dashboard Midtrans
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-XXXXX'),

    // Client Key dari Dashboard Midtrans
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-XXXXX'),

    // Mode Sandbox (false) atau Production (true)
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Keamanan
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
