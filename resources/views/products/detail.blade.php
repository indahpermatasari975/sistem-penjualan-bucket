@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row g-5 align-items-start">

            <!-- KOLOM KIRI: GAMBAR PRODUK -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 14px;">
                    <div style="aspect-ratio: 1 / 1; width: 100%; overflow: hidden; background: #faf6f0; border-radius: 10px;" class="d-flex align-items-center justify-content-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="bi bi-flower2" style="font-size: 8rem; color: #e9897d;"></i>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: INFO PRODUK -->
            <div class="col-md-7">
                <!-- 1. Badge Kategori -->
                <span class="badge mb-2 text-white px-3 py-2" style="background-color: #1e3d2f; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                    {{ strtoupper($product->category->name) }}
                </span>

                <!-- 2. Judul & Harga -->
                <h2 class="fw-bold mb-1" style="color: #1e3d2f;">{{ $product->name }}</h2>
                <h3 class="fw-bold mb-4" style="color: #e9897d;">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>

                <!-- 3. Deskripsi Produk (Bagian Atas) -->
                <div class="mb-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Deskripsi Produk</h6>
                    <p class="text-muted mb-3" style="line-height: 1.7; font-size: 0.9rem;">
                        {{ $product->description }}
                    </p>

                    <!-- 4. List Isi & Ukuran (Dibuat Statis agar selalu rapi seperti referensi) -->
                    <!-- Catatan: Anda bisa memindahkan teks ini ke database jika ingin dinamis per produk -->
                    <div class="text-muted" style="font-size: 0.9rem;">

                        <p class="mb-1">Isi :</p>
                        <ul class="mb-3" style="padding-left: 1.2rem; line-height: 1.6;">
                            <li>1 boneka teddy putih paka toga (ukuran sedang)</li>
                            <li>Bunga mawar pink artificial</li>
                            <li>Bunga putih (baby breath) sebagai pemanis</li>
                            <li>Kertas wrapping warna pink + celot</li>
                            <li>Pita satin pink besar</li>
                        </ul>

                        <p class="mb-1">Ukuran: tinggi sekitar 45cm, lebar 30cm</p>

                        <p class="mt-2 mb-3">Cocok untuk: Kado wisuda, kado kelulusan</p>

                        <p class="mb-1">Catatan:</p>
                        <ul class="mb-0" style="padding-left: 1.2rem; line-height: 1.6;">
                            <li>Bunga artificial, jadi awet dan gak perlu air</li>
                            <li>Bisa request tambah uang asli snap (tambah biaya)</li>
                            <li>Boneka bisa request warna lain (selama stok ada)</li>
                        </ul>
                    </div>
                </div>

                <!-- 5. Banner Rakit Custom -->
                <div class="p-3 mb-4 rounded-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3" style="background-color: #ffffff; border: 1px solid #e9897d;">
                    <div>
                        <strong class="d-block mb-1" style="color: #1e3d2f; font-size: 0.85rem;">
                            <i class="bi bi-pencil-square text-danger me-1"></i> Ingin Rangkai Sendiri Buket Anda?
                        </strong>
                        <small class="text-muted" style="font-size: 0.75rem;">Pilih warna wrapping, bunga, boneka, dan ucapan sesuai selera.</small>
                    </div>
                    <a href="{{ route('custom.index', $product->id) }}" class="btn btn-sm text-white px-4 py-2 fw-semibold text-nowrap" style="background-color: #e9897d; border-radius: 20px; font-size: 0.8rem;">
                        Rakit Custom
                    </a>
                </div>

                <!-- 6. Tombol Aksi -->
                <form action="{{ route('checkout.direct') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="row g-3 mb-3">
                        <!-- Tombol Keranjang -->
                        <div class="col-sm-6">
                            <button type="submit" formaction="{{ route('cart.add') }}" name="action" value="keranjang" class="btn btn-lg w-100 py-3 fw-bold" style="border: 1px solid #1e3d2f; color: #1e3d2f; background: white; font-size: 0.95rem;">
                                <i class="bi bi-cart-plus me-1"></i> Masukkan ke Keranjang
                            </button>
                        </div>

                        <!-- Tombol Pesan -->
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-lg w-100 py-3 fw-bold text-white shadow-sm" style="background-color: #1e3d2f; border-color: #1e3d2f; font-size: 0.95rem;">
                                <i class="bi bi-bag-check me-1"></i> Pesan Sekarang
                            </button>
                        </div>
                    </div>
                </form>

                <!-- 7. Link Kembali -->
                <div class="mt-2">
                    <a href="{{ route('home') }}" class="btn btn-link text-decoration-none px-0 text-muted" style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog Produk
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
