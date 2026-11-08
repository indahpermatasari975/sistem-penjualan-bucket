@extends('layouts.app')

@section('title', 'Pembayaran Pesanan - Buket House')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm p-4 p-md-5 text-center" style="border-radius: 16px;">
                
                <!-- Ikon Header -->
                <div class="mb-3">
                    <i class="bi bi-wallet2 text-success" style="font-size: 3.5rem;"></i>
                </div>

                <h3 class="fw-bold" style="color: #1e3d2f;">Selesaikan Pembayaran</h3>
                <p class="text-muted small">Pesanan Anda telah dibuat. Silakan klik tombol di bawah untuk membayar melalui Midtrans.</p>

                <!-- Box Rincian Pesanan -->
                <div class="rounded-3 p-3 my-3 text-start" style="background-color: #faf6f0;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">No. Invoice:</span>
                        <strong class="text-dark">{{ $order->invoice_number }}</strong>
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

                @if($order->status == 'Lunas')
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle-fill me-1"></i> Pesanan ini sudah <strong>Lunas</strong>!
                    </div>
                    <a href="{{ route('pesanan.show', $order->id) }}" class="btn text-white w-100 py-2 mt-2" style="background-color: #1e3d2f;">
                        Lihat Rincian Pesanan
                    </a>
                @elseif($order->snap_token)
                    <!-- Tombol Munculkan Pop-up Midtrans Snap -->
                    <button id="pay-button" class="btn btn-lg text-white w-100 py-3 fw-bold shadow-sm mt-3" style="background-color: #1e3d2f; border-radius: 10px;">
                        <i class="bi bi-credit-card-2-front me-2"></i> Bayar Sekarang via Midtrans
                    </button>
                    <p class="text-muted small mt-2">Mendukung GoPay, QRIS, BCA, BNI, BRI, Mandiri, Indomaret, Alfamart, dll.</p>
                @else
                    <div class="alert alert-warning mt-3">
                        Token pembayaran belum tersedia atau pembayaran menggunakan metode COD.
                    </div>
                    <a href="{{ route('pesanan.show', $order->id) }}" class="btn btn-outline-success w-100 py-2 mt-2">
                        Lihat Pesanan
                    </a>
                @endif

                <a href="{{ route('pesanan.index') }}" class="btn btn-link text-decoration-none text-muted mt-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan Saya
                </a>

            </div>
        </div>
    </div>
</div>

@if($order->snap_token && $order->status != 'Lunas')
    <!-- Script Snap JS Midtrans Sandbox -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        // Fungsi untuk membuka pop-up Midtrans Snap
        function bayarMidtrans() {
            window.snap.pay('{{ $order->snap_token }}', {
                // Saat pembayaran sukses
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                    window.location.href = "{{ route('pesanan.show', $order->id) }}";
                },
                // Saat pembayaran pending (misal menunggu transfer bank)
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda...");
                    console.log(result);
                    window.location.href = "{{ route('pesanan.show', $order->id) }}";
                },
                // Saat pembayaran gagal
                onError: function(result) {
                    alert("Pembayaran gagal! Silakan coba lagi.");
                    console.log(result);
                },
                // Saat user menutup pop-up tanpa menyelesaikan pembayaran
                onClose: function() {
                    alert('Anda menutup pop-up pembayaran sebelum selesai.');
                }
            });
        }

        // Buka saat tombol diklik
        document.getElementById('pay-button').onclick = function() {
            bayarMidtrans();
        };

        // Buka otomatis saat halaman pertama kali terbuka
        window.onload = function() {
            // Memberi jeda 500ms agar halaman tampil terlebih dahulu
            setTimeout(bayarMidtrans, 500);
        };
    </script>
@endif
@endsection

