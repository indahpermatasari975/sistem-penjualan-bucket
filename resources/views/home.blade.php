
@extends('layouts.app')

@section('title', 'Beranda - Buket House')

@push('styles')
<style>
    :root {
        --green: #1e3d2f;
        --green-light: #315a45;
        --pink: #ef8987;
        --pink-soft: #fce5e2;
        --cream: #fff9f5;
        --text: #35453b;
    }

    /* DASAR HALAMAN */
    .home-page {
        width: 100%;
        overflow-x: clip;
        background: var(--cream);
    }

    .home-page img {
        max-width: 100%;
    }

    /* HERO */
    .hero-section {
        background: linear-gradient(120deg, #fff9f5, #fbe5e1);
        padding: 65px 0;
        overflow: hidden;
    }

    .hero-label {
        display: inline-block;
        background: var(--pink-soft);
        color: #b95f65;
        padding: 9px 17px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(36px, 5vw, 65px);
        font-weight: 800;
        color: var(--green);
        line-height: 1.2;
    }

    .hero-title span {
        color: var(--pink);
        font-style: italic;
    }

    .hero-description {
        color: #68736b;
        font-size: 14px;
        line-height: 1.9;
        max-width: 490px;
        margin: 20px 0 28px;
    }

    .hero-image-wrapper {
        width: 100%;
        height: 390px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 15px 15px rgba(0,0,0,.10));
    }

    .hero-placeholder {
        background: var(--pink-soft);
        border-radius: 25px;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: var(--green);
    }

    .hero-placeholder i {
        font-size: 90px;
        color: var(--pink);
    }

    .btn-katalog {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--green);
        color: white;
        padding: 14px 27px;
        border-radius: 35px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: .3s;
    }

    .btn-katalog:hover {
        background: var(--green-light);
        color: white;
        transform: translateY(-3px);
    }

    /* KEUNGGULAN */
    .feature-section {
        position: relative;
        margin-top: -25px;
        z-index: 2;
    }

    .feature-box {
        background: white;
        border-radius: 20px;
        padding: 22px 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,.06);
    }

    .feature-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 13px;
        padding: 10px;
    }

    .feature-item i {
        color: var(--green);
        font-size: 30px;
    }

    .feature-item h6 {
        color: var(--green);
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .feature-item small {
        color: #888;
        font-size: 11px;
    }

    /* JUDUL */
    .section-title {
        font-family: 'Playfair Display', serif;
        color: var(--green);
        font-weight: 800;
        font-size: clamp(27px, 3vw, 38px);
    }

    .section-title span {
        color: var(--pink);
        font-style: italic;
    }

    .section-description {
        color: #888;
        font-size: 13px;
    }

    /* KATALOG */
    .catalog-section {
        padding: 75px 0 30px;
    }

    .filter-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-bottom: 35px;
    }

    .filter-btn {
        display: inline-block;
        background: white;
        color: var(--green);
        border: 1px solid #eadbd6;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: .3s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--green);
        color: white;
        border-color: var(--green);
    }

    /* PRODUK */
    .product-card {
        height: 100%;
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,.05);
        transition: .3s;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0,0,0,.10);
    }

    .product-link {
        display: block;
        height: 100%;
        text-decoration: none;
        color: inherit;
    }

    .product-image-wrapper {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #f8e9e5;
    }

    .product-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: .4s;
    }

    .product-card:hover .product-image {
        transform: scale(1.06);
    }

    .product-placeholder {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pink);
        font-size: 60px;
    }

    .product-info {
        padding: 16px;
    }

    .product-category {
        display: inline-block;
        background: var(--pink-soft);
        color: #b95f65;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .product-name {
        color: var(--green);
        font-size: 14px;
        font-weight: 700;
        margin: 12px 0;
        min-height: 40px;
    }

    .product-price {
        color: #d47777;
        font-size: 16px;
        font-weight: 700;
    }

    .product-stock {
        color: #888;
        font-size: 11px;
    }

    .btn-detail {
        display: block;
        text-align: center;
        background: var(--green);
        color: white;
        padding: 10px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 14px;
    }

    /* PRODUK KOSONG */
    .empty-products {
        background: white;
        padding: 60px 20px;
        text-align: center;
        border-radius: 20px;
    }

    .empty-products i {
        color: var(--pink);
        font-size: 55px;
    }

    /* PAGINATION */
    .pagination {
        justify-content: center;
        gap: 5px;
    }

    .pagination .page-link {
        color: var(--green);
        border-radius: 8px !important;
    }

    .pagination .active .page-link {
        background: var(--green);
        border-color: var(--green);
        color: white;
    }

    /* RESPONSIVE */
    @media (max-width: 767px) {
        .hero-section {
            padding: 45px 0;
        }

        .hero-image-wrapper {
            height: 280px;
            margin-top: 25px;
        }

        .hero-title {
            font-size: 39px;
        }

        .feature-item {
            justify-content: flex-start;
        }

        .feature-box .border-start,
        .feature-box .border-end {
            border: none !important;
        }

        .catalog-section {
            padding-top: 55px;
        }

        .product-info {
            padding: 11px;
        }

        .product-name {
            font-size: 12px;
        }

        .product-price {
            font-size: 13px;
        }
    }
</style>
@endpush


@section('content')

<div class="home-page">

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">

                    <div class="hero-label">
                        <i class="bi bi-flower1"></i>
                        RANGKAIAN KECIL, KESAN BESAR
                    </div>

                    <h1 class="hero-title">
                        Buket untuk
                        Momen <span>Istimewa</span> Anda
                    </h1>

                    <p class="hero-description">
                        Temukan berbagai pilihan buket yang dirangkai
                        dengan penuh cinta untuk merayakan setiap
                        cerita dan momen berharga Anda.
                    </p>

                    <a href="#katalog" class="btn-katalog">
                        <i class="bi bi-bag"></i>
                        Lihat Katalog
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

                <div class="col-lg-6">

                    <div class="hero-image-wrapper">

                        @if(file_exists(public_path('images/hero-buket.png')))

                            <img
                                src="{{ asset('images/hero-buket.png') }}"
                                class="hero-image"
                                alt="Koleksi Buket House"
                            >

                        @else

                            <div class="hero-placeholder">
                                <i class="bi bi-flower2"></i>
                                <h5>Buket House</h5>
                            </div>

                        @endif

                    </div>

                </div>

            </div>
        </div>
    </section>


    <!-- KEUNGGULAN TOKO -->
    <section class="feature-section">

        <div class="container">

            <div class="feature-box">

                <div class="row">

                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="bi bi-truck"></i>
                            <div>
                                <h6>Pengiriman</h6>
                                <small>Seluruh Indonesia</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <h6>Pembayaran Aman</h6>
                                <small>Aman dan Mudah</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="bi bi-award"></i>
                            <div>
                                <h6>Buket Berkualitas</h6>
                                <small>Harga Terbaik</small>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- KATALOG PRODUK -->
    <section class="catalog-section" id="katalog">

        <div class="container">

            <div class="text-center mb-4">

                <h2 class="section-title">
                    Koleksi <span>Buket</span> Kami
                </h2>

                <p class="section-description">
                    Pilih buket favorit Anda dari koleksi terbaik kami.
                </p>

            </div>


            <!-- FILTER KATEGORI -->
            <div class="filter-list">

                <a
                    href="{{ route('home', request()->only('search')) }}#katalog"
                    class="filter-btn {{ !request('category') ? 'active' : '' }}"
                >
                    Semua Kategori
                </a>

                @foreach($categories as $category)

                    <a
                        href="{{ route('home', array_merge(
                            request()->only('search'),
                            ['category' => $category->id]
                        )) }}#katalog"
                        class="filter-btn {{ request('category') == $category->id ? 'active' : '' }}"
                    >
                        {{ $category->display_name ?? $category->name }}
                    </a>

                @endforeach

            </div>


            <!-- PENCARIAN -->
            @if(request('search'))

                <div class="alert alert-light border mb-4">

                    Hasil pencarian:
                    <strong>{{ request('search') }}</strong>

                    <a href="{{ route('home') }}#katalog"
                       class="float-end text-danger text-decoration-none">
                        Hapus pencarian
                    </a>

                </div>

            @endif


            <!-- DAFTAR PRODUK -->
            <div class="row g-4">

                @forelse($products as $product)

                    <div class="col-lg-3 col-md-4 col-6">

                        <a
                            href="{{ Auth::check()
                                ? route('products.detail', $product->id)
                                : route('login') }}"
                            class="product-link"
                        >

                            <div class="product-card">

                                <div class="product-image-wrapper">

                                    @if($product->image)

                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            class="product-image"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="product-placeholder">
                                            <i class="bi bi-flower2"></i>
                                        </div>

                                    @endif

                                </div>


                                <div class="product-info">

                                    <span class="product-category">
                                        {{ $product->category->name ?? 'Buket' }}
                                    </span>

                                    <h6 class="product-name">
                                        {{ $product->name }}
                                    </h6>

                                    <div class="product-price">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>

                                    <div class="product-stock mt-2">
                                        <i class="bi bi-box-seam"></i>
                                        Stok: {{ $product->stock }}
                                    </div>

                                    <div class="btn-detail">
                                        <i class="bi bi-eye"></i>
                                        Lihat Detail
                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="empty-products">

                            <i class="bi bi-bag-x"></i>

                            <h5 class="mt-3 fw-bold">
                                Produk Belum Tersedia
                            </h5>

                            <p class="text-muted">
                                Belum ada produk yang sesuai dengan pilihan Anda.
                            </p>

                            <a href="{{ route('home') }}"
                               class="btn-katalog">
                                Lihat Semua Produk
                            </a>

                        </div>

                    </div>

                @endforelse

            </div>


            <!-- PAGINATION -->
            <div class="mt-5">
                {{ $products->links() }}
            </div>

        </div>

    </section>

</div>

@endsection
