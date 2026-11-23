
@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')

<style>
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .order-title {
        font-family: 'Playfair Display', serif;
        color: #1e3d2f;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .order-subtitle {
        color: #788078;
        font-size: 13px;
        margin: 0;
    }

    .order-icon {
        color: #ef8987;
        margin-right: 8px;
    }

    /* Kartu statistik */
    .order-stat {
        background: white;
        border: 1px solid #e8e2d8;
        border-radius: 13px;
        padding: 18px;
        height: 100%;
        transition: 0.3s;
    }

    .order-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
    }

    .order-stat .stat-name {
        color: #788078;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .order-stat .stat-number {
        color: #1e3d2f;
        font-size: 27px;
        font-weight: 700;
        margin-top: 8px;
    }

    .order-stat .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 19px;
    }

    .stat-total {
        border-left: 4px solid #315d49;
    }

    .stat-waiting {
        border-left: 4px solid #efb184;
    }

    .stat-process {
        border-left: 4px solid #65a6c9;
    }

    .stat-delivery {
        border-left: 4px solid #a58bd5;
    }

    .stat-finish {
        border-left: 4px solid #69ad82;
    }

    .icon-total {
        background: #eaf0e7;
        color: #315d49;
    }

    .icon-waiting {
        background: #fff1df;
        color: #c17a35;
    }

    .icon-process {
        background: #e4f3fb;
        color: #3985aa;
    }

    .icon-delivery {
        background: #f0eafb;
        color: #8565bd;
    }

    .icon-finish {
        background: #e4f3e8;
        color: #378653;
    }

    /* Filter pesanan */
    .order-filter {
        background: white;
        border: 1px solid #e8e2d8;
        border-radius: 13px;
        padding: 18px;
        margin-bottom: 22px;
    }

    .order-filter label {
        font-size: 11px;
        font-weight: 600;
        color: #788078;
        margin-bottom: 7px;
        text-transform: uppercase;
    }

    .order-filter .form-control,
    .order-filter .form-select {
        border: 1px solid #e8e2d8;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
    }

    .order-filter .form-control:focus,
    .order-filter .form-select:focus {
        border-color: #4a8069;
        box-shadow: 0 0 0 3px rgba(74,128,105,0.12);
    }

    .btn-filter {
        background: linear-gradient(135deg, #315d49, #4a8069);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
    }

    .btn-filter:hover {
        background: #1e3d2f;
        color: white;
    }

    .btn-reset {
        border: 1px solid #e8e2d8;
        color: #788078;
        border-radius: 8px;
        padding: 10px 13px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reset:hover {
        background: #f8f4ef;
        color: #1e3d2f;
    }

    /* Tabel pesanan */
    .order-table-card {
        background: white;
        border: 1px solid #e8e2d8;
        border-radius: 13px;
        overflow: hidden;
    }

    .order-table-title {
        padding: 18px 20px;
        border-bottom: 1px solid #eee8df;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-table-title h5 {
        font-family: 'Playfair Display', serif;
        color: #1e3d2f;
        font-size: 17px;
        margin: 0;
        font-weight: 700;
    }

    .order-table-title small {
        color: #788078;
        font-size: 12px;
    }

    .order-table {
        margin: 0;
        min-width: 1050px;
    }

    .order-table thead th {
        background: #f1f5ef;
        color: #315d49;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 15px 13px;
        border-bottom: 1px solid #e8e2d8;
        white-space: nowrap;
    }

    .order-table tbody td {
        padding: 14px 13px;
        font-size: 12px;
        color: #46534b;
        vertical-align: middle;
        border-bottom: 1px solid #f1eee8;
    }

    .order-table tbody tr:hover {
        background: #fffaf6;
    }

    .invoice-number {
        color: #315d49;
        font-weight: 700;
    }

    .customer-name {
        color: #263c32;
        font-weight: 600;
    }

    .customer-phone {
        color: #929b93;
        font-size: 11px;
    }

    .order-price {
        color: #1e3d2f;
        font-weight: 700;
        white-space: nowrap;
    }

    /* Status pesanan */
    .status-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-menunggu {
        background: #fff1dc;
        color: #b7791f;
    }

    .status-diproses {
        background: #e1f2fa;
        color: #28789d;
    }

    .status-dikirim {
        background: #eee8fa;
        color: #7754ad;
    }

    .status-selesai {
        background: #e2f3e6;
        color: #278044;
    }

    .status-pembayaran-lunas {
        background: #e2f3e6;
        color: #278044;
    }

    .status-pembayaran-belum {
        background: #fff1dc;
        color: #b7791f;
    }

    .status-pembayaran-batal {
        background: #fde8e8;
        color: #c03939;
    }

    .status-pembayaran-lain {
        background: #eeeeee;
        color: #666;
    }

    /* Tombol detail */
    .btn-detail {
        background: white;
        border: 1px solid #315d49;
        color: #315d49;
        border-radius: 7px;
        padding: 7px 12px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
    }

    .btn-detail:hover {
        background: #315d49;
        color: white;
    }

    /* Data kosong */
    .empty-order {
        text-align: center;
        padding: 55px 20px !important;
        color: #929b93 !important;
    }

    .empty-order i {
        font-size: 42px;
        color: #d8dfd7;
        display: block;
        margin-bottom: 12px;
    }

    /* Pagination */
    .order-pagination {
        padding: 18px;
        border-top: 1px solid #eee8df;
    }

    @media (max-width: 768px) {
        .order-title {
            font-size: 23px;
        }

        .order-stat {
            padding: 14px;
        }

        .order-stat .stat-number {
            font-size: 23px;
        }

        .order-filter {
            padding: 14px;
        }
    }
</style>


{{-- JUDUL HALAMAN --}}
<div class="order-header">

    <div>
        <h2 class="order-title">
            <i class="bi bi-bag-check order-icon"></i>
            Kelola Pesanan
        </h2>

        <p class="order-subtitle">
            Pantau dan kelola semua pesanan pelanggan dengan mudah.
        </p>
    </div>

    <a href="{{ route('admin.orders.index', request()->query()) }}" class="btn-accent">
        <i class="bi bi-arrow-clockwise"></i> Refresh Data
    </a>

</div>


{{-- STATISTIK PESANAN --}}
<div class="row g-3 mb-4">

    {{-- Total Pesanan --}}
    <div class="col-6 col-lg">
        <div class="order-stat stat-total">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <div class="stat-name">Total Pesanan</div>
                    <div class="stat-number">{{ $totalPesanan }}</div>
                    <small class="text-muted">Semua pesanan</small>
                </div>

                <div class="stat-icon icon-total">
                    <i class="bi bi-bag-check"></i>
                </div>

            </div>

        </div>
    </div>

    {{-- Menunggu --}}
    <div class="col-6 col-lg">
        <div class="order-stat stat-waiting">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <div class="stat-name">Menunggu</div>
                    <div class="stat-number">{{ $menunggu }}</div>
                    <small class="text-muted">Belum diproses</small>
                </div>

                <div class="stat-icon icon-waiting">
                    <i class="bi bi-clock-history"></i>
                </div>

            </div>

        </div>
    </div>

    {{-- Diproses --}}
    <div class="col-6 col-lg">
        <div class="order-stat stat-process">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <div class="stat-name">Diproses</div>
                    <div class="stat-number">{{ $diproses }}</div>
                    <small class="text-muted">Sedang dibuat</small>
                </div>

                <div class="stat-icon icon-process">
                    <i class="bi bi-box-seam"></i>
                </div>

            </div>

        </div>
    </div>

    {{-- Dikirim --}}
    {{-- Selesai --}}
    <div class="col-6 col-lg">
        <div class="order-stat stat-finish">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <div class="stat-name">Selesai</div>
                    <div class="stat-number">{{ $selesai }}</div>
                    <small class="text-muted">Pesanan selesai</small>
                </div>

                <div class="stat-icon icon-finish">
                    <i class="bi bi-check-circle"></i>
                </div>

            </div>

        </div>
    </div>

</div>


{{-- PENCARIAN DAN FILTER --}}
<div class="order-filter">

    <form action="{{ route('admin.orders.index') }}" method="GET">

        <div class="row g-3 align-items-end">

            {{-- Pencarian --}}
            <div class="col-md-5">

                <label>
                    <i class="bi bi-search me-1"></i>
                    Cari Pesanan
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Nomor invoice atau nama pelanggan..."
                    value="{{ request('search') }}"
                >

            </div>

            {{-- Filter Status --}}
            <div class="col-md-3">

                <label>
                    <i class="bi bi-funnel me-1"></i>
                    Status Pesanan
                </label>

                <select name="order_status" class="form-select">

                    <option value="">Semua Status</option>

                    <option value="Menunggu"
                        {{ request('order_status') == 'Menunggu' ? 'selected' : '' }}>
                        Menunggu
                    </option>

                    <option value="Diproses"
                        {{ request('order_status') == 'Diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option value="Dikirim"
                        {{ request('order_status') == 'Dikirim' ? 'selected' : '' }}>
                        Dikirim
                    </option>

                    <option value="Selesai"
                        {{ request('order_status') == 'Selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>

            </div>

            <div class="col-md-2">
                <label><i class="bi bi-calendar3 me-1"></i>Tanggal Pesanan</label>
                <input type="date" name="order_date" class="form-control" value="{{ request('order_date') }}">
            </div>

            {{-- Tombol --}}
            <div class="col-md-2 d-flex gap-2">

                <button type="submit" class="btn-filter">
                    <i class="bi bi-funnel me-1"></i>
                    Filter
                </button>

                @if(request()->hasAny(['search', 'order_status', 'order_date']))
                    <a href="{{ route('admin.orders.index') }}"
                        class="btn-reset"
                        title="Reset Filter">

                        <i class="bi bi-arrow-counterclockwise"></i>

                    </a>
                @endif

            </div>

        </div>

    </form>

</div>


{{-- TABEL PESANAN --}}
<div class="order-table-card">

    <div class="order-table-title">

        <div>
            <h5>
                <i class="bi bi-list-ul me-2"></i>
                Daftar Pesanan Masuk
            </h5>

            <small>
                Data pesanan pelanggan Buket House
            </small>
        </div>

        <span class="badge rounded-pill text-bg-light border">
            {{ $orders->total() }} Pesanan
        </span>

    </div>


    <div class="table-responsive">

        <table class="table order-table align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Pesanan</th>
                    <th>Jadwal Pengiriman</th>
                    <th>Total Harga</th>
                    <th>Status Pesanan</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($orders as $key => $order)

                    @php
                        // Status pengerjaan pesanan
                        $statusPesanan = $order->order_status ?? 'Menunggu';

                        $classStatus = [
                            'Menunggu' => 'status-menunggu',
                            'Diproses' => 'status-diproses',
                            'Dikirim' => 'status-dikirim',
                            'Selesai' => 'status-selesai',
                        ];

                        // Status pembayaran
                        if ($order->status == 'Lunas') {
                            $classPembayaran = 'status-pembayaran-lunas';
                        } elseif ($order->status == 'Belum Dibayar' || $order->status == 'menunggu') {
                            $classPembayaran = 'status-pembayaran-belum';
                        } elseif ($order->status == 'Dibatalkan') {
                            $classPembayaran = 'status-pembayaran-batal';
                        } else {
                            $classPembayaran = 'status-pembayaran-lain';
                        }
                    @endphp

                    <tr>

                        {{-- Nomor --}}
                        <td>
                            {{ $orders->firstItem() + $key }}
                        </td>

                        {{-- Invoice --}}
                        <td>
                            <span class="invoice-number">
                                {{ $order->invoice_number }}
                            </span>
                        </td>

                        {{-- Pelanggan --}}
                        <td>
                            <div class="customer-name">
                                {{ $order->user?->name ?? $order->recipient_name }}
                            </div>

                            <div class="customer-phone">
                                <i class="bi bi-telephone me-1"></i>
                                {{ $order->phone }}
                            </div>
                        </td>

                        {{-- Tanggal Pesanan --}}
                        <td>
                            {{ $order->created_at->format('d/m/Y') }}

                            <br>

                            <small class="text-muted">
                                {{ $order->created_at->format('H:i') }} WIB
                            </small>
                        </td>

                        {{-- Jadwal Pengiriman --}}
                        <td>

                            @if($order->delivery_date)

                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d M Y') }}
                                </span>

                                <br>

                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $order->delivery_time ? substr($order->delivery_time, 0, 5) : '-' }}
                                </small>

                                <br>

                                <small class="text-muted">
                                    {{ $order->delivery_method ?? '-' }}
                                </small>

                            @else

                                <span class="text-muted">Belum ditentukan</span>

                            @endif

                        </td>

                        {{-- Total Harga --}}
                        <td>
                            <span class="order-price">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Status Pesanan --}}
                        <td>
                            <span class="status-badge {{ $classStatus[$statusPesanan] ?? 'status-pembayaran-lain' }}">
                                {{ $statusPesanan }}
                            </span>
                        </td>

                        {{-- Status Pembayaran --}}
                        <td>
                            <span class="status-badge {{ $classPembayaran }}">
                                {{ $order->status ?? 'Belum Dibayar' }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td>

                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                class="btn-detail">

                                <i class="bi bi-eye me-1"></i>
                                Detail

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="empty-order">

                            <i class="bi bi-inbox"></i>

                            <strong>Belum Ada Pesanan</strong>

                            <p class="mb-0 mt-2">
                                Pesanan pelanggan akan muncul di halaman ini.
                            </p>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    @if($orders->hasPages())
    <div class="order-pagination">
        <div class="pagination-info">
        <div class="pagination-links">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif

</div>

@endsection
