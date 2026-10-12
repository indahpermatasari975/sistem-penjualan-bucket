@extends('layouts.admin')
@section('title', $product->name)

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-eye"></i> Detail Bucket</h2>
        <p class="page-subtitle">Informasi lengkap produk</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="btn-accent"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    {{-- IMAGE --}}
    <div class="col-lg-5">
        <div class="card" style="overflow: hidden;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-100" style="max-height: 500px; object-fit: cover;">
            @else
                <div class="bucket-placeholder" style="height: 400px; font-size: 5rem;"><i class="bi bi-flower2"></i></div>
            @endif
        </div>
    </div>

    {{-- INFO --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <span class="category-pill">{{ $product->category->name }}</span>
                <h1 style="font-family: 'Playfair Display', serif; margin: 12px 0;">{{ $product->name }}</h1>
                <p style="color: var(--primary); font-weight: 700; font-size: 1.8rem; margin-bottom: 20px;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                {{-- INFO BOXES --}}
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div style="background: var(--cream); padding: 16px; border-radius: 10px;">
                            <div style="font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px;">Stok Tersedia</div>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary-dark); margin-top: 4px;">{{ $product->stock }} unit</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background: var(--cream); padding: 16px; border-radius: 10px;">
                            <div style="font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px;">Status</div>
                            <div style="margin-top: 6px;">
                                @if($product->stock > 10)
                                    <span class="stock-badge high" style="position: static;">✅ Tersedia</span>
                                @elseif($product->stock > 0)
                                    <span class="stock-badge low" style="position: static;">⚠️ Terbatas</span>
                                @else
                                    <span class="stock-badge out" style="position: static;">❌ Habis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- DESKRIPSI dengan format POINT + CATATAN --}}
                <h5><i class="bi bi-card-text"></i> Deskripsi</h5>
                <div class="description-content">
                    @php
                        $description = $product->description ?? 'Tidak ada deskripsi.';

                        // Langkah 1: Pisahkan paragraf pembuka dengan bullet pertama
                        $parts = preg_split('/\s*•\s*/u', $description);
                        $opening = trim($parts[0] ?? '');
                        $rawPoints = array_slice($parts, 1);

                        $normalPoints = [];
                        $notes = [];

                        foreach ($rawPoints as $point) {
                            $text = trim($point);
                            if ($text === '') continue;

                            // Cek apakah ada "(Catatan: ...)" di TENGAH poin
                            if (preg_match('/\(Catatan\s*:\s*(.+?)\)/i', $text, $matches)) {
                                // Ambil teks sebelum "(Catatan:"
                                $beforeNote = trim(preg_replace('/\s*\(Catatan\s*:.*$/i', '', $text));
                                if ($beforeNote !== '') {
                                    $normalPoints[] = $beforeNote;
                                }
                                // Simpan catatan
                                $notes[] = 'Catatan: ' . trim($matches[1]);
                            }
                            // Cek apakah "Catatan:" ada di awal poin (setelah •)
                            elseif (preg_match('/^\(?Catatan\s*:/i', $text)) {
                                $cleanNote = preg_replace('/^\(/', '', $text);
                                $cleanNote = preg_replace('/\)$/', '', $cleanNote);
                                $notes[] = trim($cleanNote);
                            }
                            else {
                                $normalPoints[] = $text;
                            }
                        }
                    @endphp

                    {{-- Paragraf Pembuka --}}
                    @if($opening)
                        <p style="color: var(--muted); line-height: 1.7; margin-bottom: 16px;">
                            {{ $opening }}
                        </p>
                    @endif

                    {{-- Poin-poin Utama --}}
                    @if(count($normalPoints) > 0)
                        <ul class="product-points">
                            @foreach($normalPoints as $point)
                                @php
                                    $hasColon = str_contains($point, ':');
                                    $label = '';
                                    $value = $point;
                                    if ($hasColon) {
                                        [$label, $value] = explode(':', $point, 2);
                                        $label = trim($label);
                                        $value = trim($value);
                                    }
                                @endphp
                                <li>
                                    @if($hasColon && $label)
                                        <strong style="color: var(--primary-dark);">{{ $label }}:</strong>
                                        {{ $value }}
                                    @else
                                        {{ $point }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Catatan sebagai Paragraf Terpisah --}}
                    @if(count($notes) > 0)
                        @foreach($notes as $note)
                            <div class="product-note">
                                <i class="bi bi-info-circle-fill"></i>
                                <div>
                                    @php
                                        $hasColon = str_contains($note, ':');
                                        $label = '';
                                        $value = $note;
                                        if ($hasColon) {
                                            [$label, $value] = explode(':', $note, 2);
                                            $label = trim($label);
                                            $value = trim($value);
                                        }
                                    @endphp
                                    @if($hasColon && $label)
                                        <strong>{{ $label }}:</strong> {{ $value }}
                                    @else
                                        {{ $note }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <hr>
                <hr>

                {{-- DELETE --}}
                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus bucket ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger" style="border-radius: 10px;">
                        <i class="bi bi-trash"></i> Hapus Bucket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- STYLE KHUSUS UNTUK POINT & CATATAN --}}
<style>
    .description-content {
        margin-top: 12px;
    }

    .product-points {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .product-points li {
        position: relative;
        padding: 10px 12px 10px 36px;
        margin-bottom: 8px;
        background: var(--cream);
        border-left: 3px solid var(--accent);
        border-radius: 8px;
        color: var(--ink);
        line-height: 1.6;
        font-size: 0.92rem;
        transition: all 0.2s;
    }
    .product-points li:hover {
        background: #f5ede4;
        transform: translateX(4px);
    }
    .product-points li::before {
        content: '🌸';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.9rem;
    }

    .product-note {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        background: #fff8e7;
        border-left: 4px solid #f0b95a;
        border-radius: 8px;
        padding: 14px 16px;
        margin-top: 12px;
        color: #7a5a1e;
        font-size: 0.9rem;
        line-height: 1.6;
        box-shadow: 0 2px 8px rgba(240, 185, 90, 0.1);
    }
    .product-note i {
        color: #f0b95a;
        font-size: 1.1rem;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .product-note strong {
        color: #8a5a10;
    }
</style>
@endsection
