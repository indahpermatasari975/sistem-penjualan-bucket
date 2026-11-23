<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->invoice_number }} - Buket House</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body{background:#f7f4ef;color:#263c32}.invoice{max-width:850px;margin:40px auto;background:#fff;padding:48px;border:1px solid #e8e2d8;border-radius:16px}.brand{color:#1e3d2f}.table thead th{background:#edf3eb;color:#315d49}@media print{body{background:#fff}.invoice{max-width:none;margin:0;padding:0;border:0;border-radius:0}.no-print{display:none!important}}
    </style>
</head>
<body>
<main class="invoice">
    <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
        <div><h1 class="brand"><i class="bi bi-flower1"></i> Buket House</h1><p class="text-muted mb-0">Buket pilihan untuk momen istimewa</p></div>
        <div class="text-end"><h2>INVOICE</h2><strong>{{ $order->invoice_number }}</strong><div>{{ $order->created_at?->format('d M Y, H:i') }} WIB</div></div>
    </div>
    <div class="row mb-4">
        <div class="col-sm-6"><h5>Ditagihkan kepada</h5><strong>{{ $order->recipient_name }}</strong><div>{{ $order->phone }}</div><div>{{ $order->user?->email }}</div><div>{{ $order->address }}</div></div>
        <div class="col-sm-6 text-sm-end"><h5>Informasi Pembayaran</h5><div>Metode: {{ $order->payment_method }}</div><div>Status: <strong>{{ $order->status }}</strong></div><div>Pengiriman: {{ $order->delivery_method ?? '-' }}</div></div>
    </div>
    <div class="table-responsive"><table class="table align-middle">
        <thead><tr><th>Produk</th><th class="text-center">Jumlah</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>@foreach($order->items as $item)<tr><td>{{ $item->product_name }}@if($item->custom_details)<div class="small text-muted">{{ $item->custom_details }}</div>@endif</td><td class="text-center">{{ $item->quantity }}</td><td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td><td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td></tr>@endforeach</tbody>
    </table></div>
    <div class="row justify-content-end"><div class="col-sm-6 col-md-5"><div class="d-flex justify-content-between py-2"><span>Subtotal produk</span><strong>Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</strong></div><div class="d-flex justify-content-between py-2 border-top fs-5"><strong>Total Pembayaran</strong><strong class="text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></div></div></div>
    <p class="text-muted small mt-4">Jadwal pengiriman: {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : '-' }} {{ $order->delivery_time ?? '' }}</p>
    <div class="d-flex gap-2 no-print mt-4"><button class="btn btn-success" onclick="window.print()"><i class="bi bi-printer me-1"></i>Cetak</button><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary">Kembali ke Pesanan</a></div>
</main>
</body>
</html>
