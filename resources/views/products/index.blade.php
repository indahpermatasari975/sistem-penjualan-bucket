@extends('layouts.admin')
@section('title', 'Kelola Produk')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-box-seam"></i> Kelola Produk</h2>
        <p class="page-subtitle">Kelola semua bucket bunga toko Anda</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn-accent">
        <i class="bi bi-plus-circle"></i> Tambah Bucket
    </a>
</div>

{{-- Statistik Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card total">
            <div class="stat-label">Total Bucket</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-trend"><i class="bi bi-arrow-up"></i> Terdaftar</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card income">
            <div class="stat-label">Total Nilai</div>
            <div class="stat-value" style="font-size: 1.4rem;">Rp {{ number_format($stats['income'], 0, ',', '.') }}</div>
            <div class="stat-trend"><i class="bi bi-cash-coin"></i> Semua produk</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card low">
            <div class="stat-label">Stok Menipis</div>
            <div class="stat-value">{{ $stats['low_stock'] }}</div>
            <div class="stat-trend" style="color: #b87044;">Perlu restock</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card out">
            <div class="stat-label">Stok Habis</div>
            <div class="stat-value">{{ $stats['out_stock'] }}</div>
            <div class="stat-trend" style="color: #c45a6e;">Segera isi</div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label>Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="🔍 Nama bucket...">
        </div>
        <div class="col-md-3">
            <label>Kategori</label>
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Urutkan</label>
            <select name="sort" class="form-select">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Termurah</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Termahal</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-primary-custom w-100 justify-content-center">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>
</div>

{{-- Product Grid --}}
@if($products->count() > 0)
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <article class="bucket-card">
                <div style="position: relative;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="bucket-img" alt="{{ $product->name }}">
                    @else
                        <div class="bucket-placeholder"><i class="bi bi-flower2"></i></div>
                    @endif
                    @if($product->stock > 10)
                        <span class="stock-badge high">{{ $product->stock }} tersedia</span>
                    @elseif($product->stock > 0)
                        <span class="stock-badge low">Sisa {{ $product->stock }}</span>
                    @else
                        <span class="stock-badge out">Habis</span>
                    @endif
                </div>
                <div class="p-3">
                    <span class="category-pill">{{ $product->category->name }}</span>
                    <h5 style="font-family: 'Playfair Display', serif; margin: 8px 0; font-size: 1.1rem;">{{ $product->name }}</h5>
                    <p class="text-muted small mb-2">{{ Str::limit($product->description ?? 'Tidak ada deskripsi.', 50) }}</p>
                    <p style="color: var(--primary); font-weight: 700; font-size: 1.05rem; margin: 0;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <div class="product-actions">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary flex-fill" title="Lihat"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning flex-fill" title="Edit"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin hapus bucket ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger w-100" title="Hapus"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </article>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">{{ $products->links() }}</div>
@else
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Belum Ada Bucket</h4>
                <p>Mulai tambahkan bucket pertama Anda!</p>
                <a href="{{ route('products.create') }}" class="btn-accent mt-2"><i class="bi bi-plus-circle"></i> Tambah Bucket</a>
            </div>
        </div>
    </div>
@endif
@endsection
