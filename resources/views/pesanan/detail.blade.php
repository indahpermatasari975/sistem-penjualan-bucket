@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1e3d2f;">Detail Pesanan</h2>
            <span class="text-muted">{{ $order->invoice_number }}</span>
        </div>
        <a href="{{ route('pesanan.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3" style="color: #1e3d2f;">Barang yang Dibeli</h5>
                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between gap-3 border-bottom py-3">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $item->product_name }}</h6>
                            <div class="text-muted small">{{ $item->custom_details }}</div>
                            <div class="small">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="fw-semibold text-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between pt-3 fw-bold">
                    <span>Total Pembayaran (termasuk ongkir)</span>
                    <span style="color: #1e3d2f;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: #1e3d2f;">Informasi Pengiriman</h5>
                <p class="mb-1"><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                <p class="mb-1"><strong>Telepon:</strong> {{ $order->phone }}</p>
                <p class="mb-0"><strong>Alamat:</strong><br>{{ $order->address }}</p>
            </div>
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3" style="color: #1e3d2f;">Pembayaran</h5>
                <p class="mb-1"><strong>Metode:</strong> {{ $order->payment_method }}</p>
                <p class="mb-2"><strong>Status:</strong> 
                    @if($order->status == 'Lunas')
                        <span class="badge bg-success">Lunas</span>
                    @elseif($order->status == 'Belum Dibayar' || $order->status == 'menunggu')
                        <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                    @elseif($order->status == 'Dibatalkan')
                        <span class="badge bg-danger">{{ $order->status }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $order->status }}</span>
                    @endif
                </p>

                @if(($order->status == 'Belum Dibayar' || $order->status == 'menunggu') && $order->snap_token)
                    <a href="{{ route('pembayaran.show', $order->id) }}" class="btn text-white w-100 mt-2 fw-semibold" style="background-color: #1e3d2f;">
                        <i class="bi bi-credit-card me-1"></i> Bayar Sekarang via Midtrans
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
