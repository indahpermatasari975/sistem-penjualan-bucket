@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #1e3d2f;">Keranjang Belanja</h2>
        @if(count($cart) > 0)
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua isi keranjang?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Hapus Semua
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($cart) > 0)
        @php $totalKeranjang = 0; @endphp
        <div class="card border-0 shadow-sm p-3 p-md-4" style="border-radius: 14px;">
            @foreach($cart as $item)
                @php
                    // Ambil relasi produk jika data diambil dari Model Cart database
                    $product = is_object($item) && isset($item->product) ? $item->product : null;
                    $namaProduk = $product ? $product->name : ($item['name'] ?? $item->name ?? 'Buket');
                    $hargaProduk = $product ? $product->price : ($item['price'] ?? $item->price ?? 0);
                    $gambarProduk = $product ? $product->image : ($item['image'] ?? $item->image ?? null);
                    $qty = is_object($item) ? ($item->quantity ?? 1) : ($item['quantity'] ?? $item['qty'] ?? 1);
                    $customRincian = is_object($item) ? ($item->custom_text ?? null) : ($item['custom_text'] ?? null);
                    $cartId = is_object($item) ? $item->id : ($loop->index ?? 0);

                    $subtotal = $hargaProduk * $qty;
                    $totalKeranjang += $subtotal;
                @endphp
                <div class="row align-items-center border-bottom py-3">
                    <div class="col-3 col-md-2">
                        @if(!empty($gambarProduk))
                            <img src="{{ asset('storage/' . $gambarProduk) }}" alt="{{ $namaProduk }}" class="img-fluid rounded" style="max-height: 90px;">
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center" style="height: 75px; background: #faf6f0;">
                                <i class="bi bi-flower2" style="font-size: 2rem; color: #e9897d;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-9 col-md-6">
                        <h5 class="fw-bold mb-1" style="color: #1e3d2f;">{{ $namaProduk }}</h5>
                        <p class="text-muted small mb-1">{{ $customRincian ?: 'Produk buket' }}</p>
                        <span class="small">Jumlah: {{ $qty }}</span>
                    </div>
                    <div class="col-8 col-md-3 text-md-end mt-2 mt-md-0 fw-bold" style="color: #1e3d2f;">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </div>
                    <div class="col-4 col-md-1 text-end mt-2 mt-md-0">
                        <!-- Tombol Hapus Satuan dari Database -->
                        <form action="{{ route('cart.remove', $cartId) }}" method="POST" onsubmit="return confirm('Hapus buket ini dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Buket">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold">Subtotal</span>
                <span class="fw-bold" style="color: #1e3d2f;">Rp {{ number_format($totalKeranjang, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
                </a>
                <a href="{{ route('checkout.index') }}" class="btn text-white" style="background-color: #1e3d2f;">
                    Lanjut ke Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm p-5 text-center mx-auto" style="max-width: 650px; border-radius: 14px;">
            <i class="bi bi-cart-x mb-3" style="font-size: 4rem; color: #e9897d;"></i>
            <h4 class="fw-bold" style="color: #1e3d2f;">Keranjang Anda masih kosong</h4>
            <p class="text-muted">Pilih buket favorit Anda untuk mulai berbelanja.</p>
            <a href="{{ route('home') }}" class="btn text-white mx-auto" style="background-color: #1e3d2f;">
                <i class="bi bi-arrow-left me-1"></i> Kembali Belanja
            </a>
        </div>
    @endif
</div>
@endsection
