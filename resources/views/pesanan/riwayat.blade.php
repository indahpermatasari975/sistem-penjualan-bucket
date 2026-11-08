@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mt-5 mb-5">
    <h2 class="fw-bold mb-4" style="color: #1e3d2f;">Pesanan Saya</h2>

    @if(count($orders) > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background-color: #1e3d2f; color: white;">
                        <tr>
                            <th class="px-3 py-3">No Invoice</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-3 fw-semibold">{{ $order->invoice_number }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($order->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($order->status == 'Belum Dibayar' || $order->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                                    @elseif($order->status == 'diproses')
                                        <span class="badge bg-primary">Diproses</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($order->status == 'dikirim')
                                        <span class="badge bg-info text-dark">Dikirim</span>
                                    @elseif($order->status == 'Dibatalkan')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('pesanan.show', $order->id) }}" class="btn btn-sm btn-outline-success">
                                        Detail <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm p-5 text-center">
            <i class="bi bi-bag-x mb-3" style="font-size: 3.5rem; color: #e9897d;"></i>
            <h5 class="fw-bold" style="color: #1e3d2f;">Belum ada pesanan</h5>
            <p class="text-muted mb-3">Pesanan Anda akan tampil di halaman ini.</p>
            <a href="{{ route('home') }}" class="btn text-white mx-auto" style="background-color: #1e3d2f;">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
