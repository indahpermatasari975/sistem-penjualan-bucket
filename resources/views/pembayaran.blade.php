@extends('layouts.app')

@section('title', 'Pembayaran Pesanan - Buket House')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm p-4 p-md-5 text-center" style="border-radius: 16px;">

                <!-- Ikon & Judul -->
                <i class="bi bi-wallet2 text-success" style="font-size: 3.5rem;"></i>
                <h3 class="fw-bold mt-3" style="color: #1e3d2f;">Selesaikan Pembayaran</h3>
                <p class="text-muted small">Silakan klik tombol di bawah untuk membayar melalui Midtrans.</p>

                <!-- Rincian Pesanan -->
                <div class="rounded-3 p-3 my-3 text-start" style="background-color: #faf6f0;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">No. Invoice:</span>
                        <strong>{{ $order->invoice_number }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Penerima:</span>
                        <span class="fw-semibold">{{ $order->recipient_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode:</span>
                        <span class="badge bg-info text-dark">{{ $order->payment_method }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Tagihan:</span>
                        <h4 class="fw-bold mb-0" style="color: #1e3d2f;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <!-- Logika Tombol -->
                @if($order->status == 'Lunas')
                    <div class="alert alert-success">Pesanan ini sudah <strong>Lunas</strong>!</div>
                    <a href="{{ route('pesanan.show', $order->id) }}" class="btn text-white w-100 py-2" style="background-color: #1e3d2f;">Lihat Rincian</a>
                @elseif($order->snap_token)
                    <button id="pay-button" class="btn btn-lg text-white w-100 py-3 fw-bold shadow-sm mt-3" style="background-color: #1e3d2f;">
                        Bayar Sekarang via Midtrans
                    </button>
                @else
                    <div class="alert alert-warning">Token pembayaran belum tersedia atau metode COD.</div>
                    <a href="{{ route('pesanan.show', $order->id) }}" class="btn btn-outline-success w-100 py-2">Lihat Pesanan</a>
                @endif

                <a href="{{ route('pesanan.index') }}" class="btn btn-link text-muted mt-3">Kembali ke Pesanan Saya</a>

            </div>
        </div>
    </div>
</div>

@if($order->snap_token && $order->status != 'Lunas')
    <!-- Script Midtrans -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        function bayarMidtrans() {
            window.snap.pay('{{ $order->snap_token }}', {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    window.location.href = "{{ route('pesanan.show', $order->id) }}";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda...");
                    window.location.href = "{{ route('pesanan.show', $order->id) }}";
                },
                onError: function(result) {
                    alert("Pembayaran gagal! Silakan coba lagi.");
                },
                onClose: function() {
                    alert('Anda menutup pop-up sebelum menyelesaikan pembayaran.');
                }
            });
        }

        // Jalankan saat tombol diklik
        document.getElementById('pay-button').onclick = function() {
            bayarMidtrans();
        };

        // Jalankan otomatis saat halaman terbuka (jeda sedikit agar tampilan muncul dulu)
        window.onload = function() {
            setTimeout(bayarMidtrans, 500);
        };
    </script>
@endif
@endsection
