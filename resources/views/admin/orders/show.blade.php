
@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')

<style>
    .detail-wrapper {
        background: #fffaf6;
        border: 1px solid #d8e5dc;
        border-radius: 14px;
        padding: 15px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .detail-header h4 {
        color: #1e4b39;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 21px;
        margin: 0;
    }

    .detail-header h4 i {
        color: #ed8987;
        margin-right: 8px;
    }

    .detail-back {
        color: #557566;
        font-size: 22px;
        text-decoration: none;
    }

    .detail-card {
        height: 100%;
        background: #fff;
        border: 1px solid #e9e5df;
        border-radius: 10px;
        padding: 12px;
    }

    .detail-card h6 {
        font-size: 13px;
        font-weight: 700;
        color: #1e3d2f;
        margin-bottom: 10px;
    }

    .detail-card p {
        font-size: 12px;
        color: #4e5c58;
        margin-bottom: 6px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .detail-card hr {
        margin: 10px 0;
        border-color: #e9e5df;
    }

    .detail-info {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        font-size: 11px;
        margin-bottom: 7px;
        color: #52615b;
    }

    .detail-info strong {
        color: #263c32;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .product-detail {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .product-detail img {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 9px;
        border: 1px solid #eee;
        flex-shrink: 0;
    }

    .product-detail .product-name {
        font-size: 12px;
        font-weight: 700;
        color: #263c32;
        margin-bottom: 4px;
    }

    .product-detail .product-price {
        font-size: 11px;
        color: #65736c;
    }

    .product-subtotal {
        font-size: 11px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 7px;
        color: #53635b;
    }

    .total-payment {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        border-top: 1px solid #eee;
        padding-top: 10px;
        margin-top: 10px;
        font-size: 13px;
        font-weight: 700;
    }

    .total-payment strong {
        color: #16834c;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #e6f4e8;
        color: #16834c;
    }

    .status-select {
        border: 1px solid #dedbd5;
        border-radius: 9px;
        padding: 9px 10px;
        font-size: 12px;
        color: #34483d;
    }

    .btn-simpan {
        background: #e98289;
        color: white;
        border: none;
        border-radius: 9px;
        padding: 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-simpan:hover {
        background: #d96f79;
        color: white;
    }

    .btn-invoice {
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        border: 1px solid #438768;
        color: #28734e;
        border-radius: 9px;
        padding: 9px;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-invoice:hover {
        background: #e9f5ed;
        color: #1e4b39;
    }

    @media (max-width: 991px) {
        .detail-card {
            margin-bottom: 5px;
        }
    }
</style>

<div class="detail-wrapper">

    {{-- Judul Detail --}}
    <div class="detail-header">
        <h4>
            <i class="bi bi-clipboard2-check"></i>
            Detail Pesanan
        </h4>

        <a href="{{ route('admin.orders.index') }}"
           class="detail-back"
           title="Kembali ke daftar pesanan">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>

    <div class="row g-2">

        {{-- 1. INFORMASI PELANGGAN --}}
        <div class="col-xl-3 col-md-6">
            <div class="detail-card">

                <h6>Informasi Pelanggan</h6>

                <p>
                    <i class="bi bi-person me-2"></i>
                    {{ $order->recipient_name }}
                </p>

                <p>
                    <i class="bi bi-telephone me-2"></i>
                    {{ $order->phone }}
                </p>

                <p>
                    <i class="bi bi-envelope me-2"></i>
                    {{ $order->user?->email ?? 'Email tidak tersedia' }}
                </p>

                <hr>

                <h6>Informasi Pesanan</h6>

                <div class="detail-info">
                    <span>Invoice</span>
                    <strong>{{ $order->invoice_number }}</strong>
                </div>

                <div class="detail-info">
                    <span>Tanggal Pesanan</span>
                    <strong>
                        {{ $order->created_at?->format('d M Y, H:i') }} WIB
                    </strong>
                </div>

                <div class="detail-info">
                    <span>Metode Pembayaran</span>
                    <strong>{{ $order->payment_method }}</strong>
                </div>

                <div class="detail-info">
                    <span>Metode Pengiriman</span>
                    <strong>{{ $order->delivery_method ?? '-' }}</strong>
                </div>

                <div class="detail-info">
                    <span>Jadwal Pengiriman</span>
                    <strong>
                        @if($order->delivery_date)
                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                            <br>
                            {{ $order->delivery_time ?? '-' }}
                        @else
                            Belum ditentukan
                        @endif
                    </strong>
                </div>

            </div>
        </div>

        {{-- 2. DAFTAR PESANAN --}}
        <div class="col-xl-3 col-md-6">
            <div class="detail-card">

                <h6>Daftar Pesanan</h6>

                @forelse($order->items as $item)

                    <div class="product-detail">

                        @if($item->product?->image)
                            <img
                                src="{{ asset('storage/' . $item->product->image) }}"
                                alt="{{ $item->product_name }}"
                            >
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                 style="width:65px;height:65px;flex-shrink:0;">
                                <i class="bi bi-flower2 fs-3 text-success"></i>
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <div class="product-name">
                                {{ $item->product_name }}
                            </div>

                            <div class="product-price">
                                {{ $item->quantity }} x
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <strong style="font-size:11px;white-space:nowrap;">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </strong>

                    </div>

                    @if($item->custom_details)
                        <p class="text-muted">
                            Detail: {{ $item->custom_details }}
                        </p>
                    @endif

                @empty

                    <p class="text-muted">
                        Rincian produk tidak tersedia.
                    </p>

                @endforelse

                <div class="product-subtotal">
                    <span>Subtotal Produk</span>
                    <strong>
                        Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}
                    </strong>
                </div>

                <div class="total-payment">
                    <span>Total Pembayaran</span>
                    <strong>
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </strong>
                </div>

            </div>
        </div>

        {{-- 3. ALAMAT PENGIRIMAN --}}
        <div class="col-xl-3 col-md-6">
            <div class="detail-card">

                <h6>Alamat Pengiriman</h6>

                <p>
                    <i class="bi bi-geo-alt me-2"></i>
                    {{ $order->address }}
                </p>

                <hr>

                <h6>Catatan Pengiriman</h6>

                <p>
                    <i class="bi bi-journal-text me-2"></i>
                    {{ $order->note ?: 'Tidak ada catatan pengiriman.' }}
                </p>

                <hr>

                <h6>Status Pembayaran</h6>

                @if(strtolower($order->status) == 'lunas')
                    <span class="status-badge">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Lunas
                    </span>
                @elseif(strtolower($order->status) == 'belum dibayar')
                    <span class="status-badge"
                          style="background:#fff0d9;color:#c47713;">
                        <i class="bi bi-clock me-1"></i>
                        Belum Dibayar
                    </span>
                @else
                    <span class="status-badge">
                        {{ $order->status ?? '-' }}
                    </span>
                @endif

            </div>
        </div>

        {{-- 4. UPDATE STATUS DAN CETAK INVOICE --}}
        <div class="col-xl-3 col-md-6">
            <div class="detail-card">

                <h6>Update Status Pesanan</h6>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <label for="order_status" class="form-label small">
                        Status Pengiriman
                    </label>

                    <select name="order_status"
                            id="order_status"
                            class="form-select status-select mb-3"
                            required>

                        @foreach(['Menunggu', 'Diproses', 'Dikirim', 'Selesai'] as $status)
                            <option value="{{ $status }}"
                                @selected(($order->order_status ?? 'Menunggu') === $status)>
                                {{ $status }}
                            </option>
                        @endforeach

                    </select>

                    @error('order_status')
                        <div class="text-danger small mb-2">
                            {{ $message }}
                        </div>
                    @enderror

                    <button type="submit" class="btn btn-simpan w-100">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Perubahan
                    </button>

                </form>

                <hr>

                <h6>Cetak Invoice</h6>

                <a href="{{ route('admin.orders.invoice', $order->id) }}"
                   target="_blank"
                   class="btn-invoice">

                    <i class="bi bi-printer me-2"></i>
                    Cetak Invoice

                </a>

            </div>
        </div>

    </div>
</div>

@endsection
