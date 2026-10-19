@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Gambar Produk -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4 text-center">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded">
                    @else
                        <i class="bi bi-flower2" style="font-size: 10rem; color: #e9897d;"></i>
                    @endif
                </div>
            </div>

            <!-- Info Produk -->
            <div class="col-md-7">
                <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                <h2 class="fw-bold">{{ $product->name }}</h2>
                <h3 class="text-success fw-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>

                <p class="text-muted" style="white-space: pre-line;">{{ $product->description }}</p>

                <hr>

                <!-- Tombol Aksi -->
                <div class="mt-4">
                    <a href="{{ route('cart.index') }}" class="btn btn-success btn-lg w-100 mb-2">
                        <i class="bi bi-cart-plus"></i> Pesan Sekarang
                    </a>
                    <button class="btn btn-outline-secondary w-100" disabled>Keranjang (Segera Hadir)</button>

                    <a href="{{ route('home') }}" class="btn btn-link mt-3 text-decoration-none">Kembali ke Katalog</a>
                </div>
            </div>
        </div>
    </div>

@endsection
