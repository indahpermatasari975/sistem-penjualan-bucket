@extends('layouts.app')

@section('title', 'Beranda - Buket House')

@section('content')

<!-- HERO SECTION (Tampilan Depan) -->
<section style="background: linear-gradient(135deg, #fbf8f2 0%, #f8e5df 100%); padding: 100px 0 80px 0;">
    <div class="container">
        <div class="mb-3">
            <span style="color: #1e3d2f; font-weight: bold; letter-spacing: 2px; font-size: 14px;">
                🌸 RANGKAIAN KECIL, KESAN BESAR
            </span>
        </div>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 4rem; font-weight: bold; color: #1e3d2f; line-height: 1.2;">
            Buket untuk <br>
            Momen <span style="color: #e9897d;">Istimewa</span> Anda
        </h1>
        <p class="mt-4 mb-4" style="font-size: 1.2rem; color: #555; max-width: 600px; line-height: 1.6;">
            Temukan buket pilihan yang dirangkai dengan penuh cinta untuk merayakan setiap cerita dan momen berharga Anda.
        </p>
        <a href="#katalog" class="btn btn-lg text-white mt-3 px-5 py-3" style="background-color: #1e3d2f; border-radius: 30px;">
            <i class="bi bi-bag"></i> Lihat Katalog
        </a>
    </div>
</section>

<!-- KATALOG PRODUK -->
<div class="container mb-5 pt-5" id="katalog">

    <!-- Header Katalog -->
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1e3d2f; font-size: 2.5rem;">
            Katalog <span style="color: #e9897d;">Buket</span> Kami
        </h2>
        <p class="text-muted">Pilih buket favorit Anda dari koleksi terbaik kami</p>
    </div>

    <!-- Filter Kategori -->
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
        <a href="{{ route('home') }}" class="btn rounded-pill px-4 py-2 d-flex align-items-center gap-2 {{ !request('category') ? 'text-white' : 'bg-white' }}"
           style="{{ !request('category') ? 'background-color: #315d49; border-color: #315d49;' : 'border: 1px solid #ddd; color: #555;' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i> Semua
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('home', ['category' => $cat->id]) }}"
               class="btn rounded-pill px-4 py-2 d-flex align-items-center gap-2 {{ request('category') == $cat->id ? 'text-white' : 'bg-white' }}"
               style="{{ request('category') == $cat->id ? 'background-color: #315d49; border-color: #315d49;' : 'border: 1px solid #ddd; color: #555;' }}">
                🌸 {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- DAFTAR PRODUK -->
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-md-4">

            {{-- 1. JIKA BELUM LOGIN (PUBLIC) --}}
            @guest
                <a href="{{ route('login') }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- GAMBAR FULL 1:1 -->
                        <div style="aspect-ratio: 1 / 1; width: 100%; overflow: hidden; background: #eee;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="bi bi-flower2" style="font-size: 4rem; color: #e9897d;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                            <h5 class="fw-bold">{{ $product->name }}</h5>
                            <h6 class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </a>
            @endguest

            {{-- 2. JIKA SUDAH LOGIN (USER / ADMIN) --}}
            @auth
                <a href="{{ route('products.detail', $product->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- GAMBAR FULL 1:1 -->
                        <div style="aspect-ratio: 1 / 1; width: 100%; overflow: hidden; background: #eee;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="bi bi-flower2" style="font-size: 4rem; color: #e9897d;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                            <h5 class="fw-bold">{{ $product->name }}</h5>
                            <h6 class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </a>
            @endauth

        </div>
        @endforeach
    </div>
</div>

@endsection
