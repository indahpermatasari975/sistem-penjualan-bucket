
@extends('layouts.app')

@section('title', 'Detail Pesanan - Buket House')

@section('content')

<div class="container mt-5 mb-5">

    <!-- HEADER HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1" style="color: #1e3d2f;">
                Detail Pesanan
            </h2>

            <p class="text-muted mb-0">
                Invoice:
                <strong>{{ $order->invoice_number }}</strong>
            </p>
        </div>

        <a href="{{ route('pesanan.index') }}" class="btn btn-outline-success">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    <div class="row g-4">

        <!-- KOLOM KIRI: DAFTAR BARANG -->
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm p-4 h-100"
                 style="border-radius: 14px;">

                <h5 class="fw-bold border-bottom pb-3 mb-3"
                    style="color: #1e3d2f;">

                    <i class="bi bi-box-seam text-success me-2"></i>
                    Barang yang Dibeli

                </h5>

                @foreach($order->items as $item)

                    <div class="d-flex mb-3 border-bottom pb-3">

                        <div class="flex-grow-1">

                            <h6 class="fw-bold mb-1">
                                {{ $item->product_name }}
                            </h6>

                            @if($item->custom_details)

                                <p class="text-muted small mb-2">
                                    {{ $item->custom_details }}
                                </p>

                            @endif

                            <div class="d-flex justify-content-between text-muted small">

                                <span>
                                    {{ $item->quantity }}
                                    x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </span>

                                <span class="fw-bold text-dark">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>

                            </div>

                        </div>

                    </div>

                @endforeach

                <!-- TOTAL PEMBAYARAN -->
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">

                    <span class="fw-bold fs-6">
                        Total Pembayaran
                    </span>

                    <h4 class="fw-bold mb-0" style="color: #1e3d2f;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </h4>

                </div>

            </div>

        </div>

        <!-- KOLOM KANAN -->
        <div class="col-lg-5">

            <!-- INFORMASI PENGIRIMAN -->
            <div class="card border-0 shadow-sm p-4 mb-4"
                 style="border-radius: 14px;">

                <h5 class="fw-bold border-bottom pb-3 mb-3"
                    style="color: #1e3d2f;">

                    <i class="bi bi-geo-alt text-danger me-2"></i>
                    Informasi Pengiriman

                </h5>

                <table class="table table-borderless table-sm mb-0">

                    <tr>
                        <td class="text-muted" style="width: 35%;">
                            Penerima
                        </td>

                        <td class="fw-semibold">
                            : {{ $order->recipient_name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            Telepon
                        </td>

                        <td class="fw-semibold">
                            : {{ $order->phone }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            Alamat
                        </td>

                        <td class="fw-semibold">
                            : {{ $order->address }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            Metode
                        </td>

                        <td class="fw-semibold">
                            : {{ $order->delivery_method ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            Tanggal
                        </td>

                        <td class="fw-semibold">
                            :

                            @if($order->delivery_date)

                                {{ \Carbon\Carbon::parse($order->delivery_date)->locale('id')->translatedFormat('d F Y') }}

                            @else

                                -

                            @endif

                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            Jam
                        </td>

                        <td class="fw-semibold">
                            :

                            @if($order->delivery_time)

                                {{ substr($order->delivery_time, 0, 5) }}

                            @else

                                -

                            @endif

                        </td>
                    </tr>

                    @if($order->note)

                        <tr>
                            <td class="text-muted">
                                Catatan
                            </td>

                            <td class="fw-semibold">
                                : {{ $order->note }}
                            </td>
                        </tr>

                    @endif

                </table>

            </div>

            <!-- INFORMASI PEMBAYARAN -->
            <div class="card border-0 shadow-sm p-4"
                 style="border-radius: 14px;">

                <h5 class="fw-bold border-bottom pb-3 mb-3"
                    style="color: #1e3d2f;">

                    <i class="bi bi-credit-card text-primary me-2"></i>
                    Informasi Pembayaran

                </h5>

                <table class="table table-borderless table-sm mb-0">

                    <tr>
                        <td class="text-muted" style="width: 35%;">
                            Metode
                        </td>

                        <td class="fw-semibold">
                            : {{ $order->payment_method }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            Status
                        </td>

                        <td>

                            @if($order->status == 'Lunas')

                                <span class="badge bg-success">
                                    Lunas
                                </span>

                            @elseif($order->status == 'Belum Dibayar')

                                <span class="badge bg-warning text-dark">
                                    Belum Dibayar
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    {{ $order->status }}
                                </span>

                            @endif

                        </td>
                    </tr>

                </table>

                <!-- TOMBOL BAYAR -->
                @if($order->status == 'Belum Dibayar' && $order->snap_token)

                    <a href="{{ route('pembayaran.show', $order->id) }}"
                       class="btn text-white w-100 mt-3"
                       style="background-color: #1e3d2f;">

                        Bayar Sekarang

                    </a>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection
