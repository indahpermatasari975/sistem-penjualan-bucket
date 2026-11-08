@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm p-4 p-md-5 mx-auto text-center" style="max-width: 720px; border-radius: 16px;">
        <div class="display-1 mb-3" style="color: #198754;">✅</div>
        <h2 class="fw-bold" style="color: #1e3d2f;">Pesanan Berhasil Dibuat!</h2>
        <p class="text-muted">Terima kasih, pembayaran Anda telah berhasil kami terima.</p>

        <div class="rounded-3 p-4 my-3 text-start" style="background-color: #faf6f0;">
            <p class="mb-2"><strong>Nomor Invoice:</strong> {{ $order->invoice_number }}</p>
            <p class="mb-2"><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p class="mb-2"><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
            <p class="fw-bold mb-1">Status Pembayaran</p>
            <p class="mb-0 text-success">
                <i class="bi bi-check-circle-fill me-1"></i> Pembayaran berhasil diselesaikan via Midtrans. Pesanan Anda akan segera kami proses dan dikirimkan sesuai alamat tujuan.
            </p>
        </div>

        <a href="{{ route('pesanan.index') }}" class="btn text-white mx-auto px-4" style="background-color: #1e3d2f;">
            <i class="bi bi-bag-check me-1"></i> Lihat Pesanan Saya
        </a>
    </div>
</div>
@endsection
