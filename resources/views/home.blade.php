@extends('layouts.app')

@section('content')
<section class="catalog-intro">
    <div class="row align-items-center position-relative" style="z-index: 1;">
        <div class="col-lg-8">
            <p class="eyebrow mb-2">Rangkaian kecil, kesan besar</p>
            <h1 class="mb-3">Bucket untuk momen istimewa</h1>
            <p class="text-muted mb-0">Temukan bucket pilihan yang dirangkai untuk merayakan setiap cerita.</p>
        </div>
    </div>
</section>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0"><i class="bi bi-flower1"></i> Katalog Bucket</h2>
    @auth
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Bucket</a>
        @endif
    @endauth
</div>

<div class="row">
    @forelse($products as $product)
        <div class="col-sm-6 col-lg-3 mb-4">
            <article class="card bucket-card h-100">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <div class="bucket-placeholder text-center d-flex align-items-center justify-content-center">
                        <i class="bi bi-flower2" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge category-pill mb-2">{{ $product->category->name }}</span>
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($product->description, 50) }}</p>
                    <p class="card-text price mb-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="card-text text-muted small mb-0">Tersedia: {{ $product->stock }}</p>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-flower2" style="font-size: 3rem; color: #9aaa91;"></i>
            <p class="mt-3 text-muted">Koleksi bucket sedang disiapkan.</p>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection
