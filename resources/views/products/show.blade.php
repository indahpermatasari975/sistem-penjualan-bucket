@extends('layouts.admin')
@section('title', $product->name)

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-eye"></i> Detail Buket</h2>
        <p class="page-subtitle">Informasi lengkap produk</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="btn-accent"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    {{-- Foto produk --}}
    <div class="col-lg-5">
        <div class="card" style="overflow: hidden;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-100" style="max-height: 500px; object-fit: cover;">
            @else
                <div class="bucket-placeholder" style="height: 400px; font-size: 5rem;"><i class="bi bi-flower2"></i></div>
            @endif
        </div>
    </div>

    {{-- Info produk --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <span class="category-pill">{{ $product->category->name }}</span>
                <h1 style="font-family: 'Playfair Display', serif; margin: 12px 0;">{{ $product->name }}</h1>
                <p style="color: var(--primary); font-weight: 700; font-size: 1.8rem; margin-bottom: 20px;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                {{-- Stok & status --}}
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div style="background: var(--cream); padding: 16px; border-radius: 10px;">
                            <div style="font-size: 0.7rem; color: var(--muted); text-transform: uppercase;">Stok Tersedia</div>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary-dark); margin-top: 4px;">
                                {{ $product->stock }} unit
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background: var(--cream); padding: 16px; border-radius: 10px;">
                            <div style="font-size: 0.7rem; color: var(--muted); text-transform: uppercase;">Status</div>
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

                {{-- Deskripsi --}}
                <h5><i class="bi bi-card-text"></i> Deskripsi</h5>

                @php
                    // Ambil deskripsi, pecah per baris
                    $desc = $product->description ?? '';
                    $lines = preg_split('/\r\n|\r|\n/', $desc);

                    $pembuka = [];
                    $isi = [];
                    $info = [];
                    $catatan = [];
                    $mode = 'pembuka';

                    foreach ($lines as $line) {
                        $line = trim($line);
                        if ($line == '') continue;

                        // Kalau baris "Isi:" atau "Isi Buket:"
                        if (stripos($line, 'Isi') === 0 && strpos($line, ':') !== false) {
                            $mode = 'isi';
                            continue;
                        }
                        // Kalau baris "Catatan:"
                        if (stripos($line, 'Catatan') === 0 && strpos($line, ':') !== false) {
                            $mode = 'catatan';
                            continue;
                        }
                        // Kalau baris mulai dengan "-" atau "•"
                        if (preg_match('/^[-•]\s*(.+)/u', $line, $m)) {
                            $item = trim($m[1]);
                            if ($mode == 'isi') {
                                $isi[] = $item;
                            } elseif ($mode == 'catatan') {
                                $catatan[] = $item;
                            } else {
                                $info[] = $item;
                            }
                            continue;
                        }
                        // Kalau baris ada ":" (kayak "Ukuran: ..." atau "Cocok untuk: ...")
                        if (strpos($line, ':') !== false && $mode != 'isi' && $mode != 'catatan') {
                            $info[] = $line;
                            continue;
                        }
                        // Kalau lagi mode isi/catatan tapi gak ada dash
                        if ($mode == 'isi') { $isi[] = $line; continue; }
                        if ($mode == 'catatan') { $catatan[] = $line; continue; }

                        // Default masuk pembuka
                        $pembuka[] = $line;
                    }
                @endphp

                {{-- Pembuka --}}
                @foreach($pembuka as $p)
                    <p style="color: var(--muted); line-height: 1.7; margin-bottom: 12px;">{{ $p }}</p>
                @endforeach

                {{-- Isi Buket --}}
                @if(count($isi) > 0)
                    <div style="margin-top: 16px;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--primary-dark); text-transform: uppercase; margin-bottom: 8px;">
                            <i class="bi bi-bag-heart"></i> Isi Buket
                        </div>
                        <ul class="isi-list">
                            @foreach($isi as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Info (Ukuran, Cocok untuk, dll) --}}
                @if(count($info) > 0)
                    <div style="margin-top: 16px;">
                        @foreach($info as $row)
                            @if(strpos($row, ':') !== false)
                                @php
                                    $parts = explode(':', $row, 2);
                                    $label = trim($parts[0]);
                                    $value = trim($parts[1]);
                                @endphp
                                <div class="info-row">
                                    <span class="info-label">{{ $label }}</span>
                                    <span class="info-value">{{ $value }}</span>
                                </div>
                            @else
                                <p style="color: var(--muted); margin: 6px 0;">{{ $row }}</p>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- Catatan --}}
                @if(count($catatan) > 0)
                    <div class="catatan-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <div style="width: 100%;">
                            <strong style="display:block; margin-bottom: 6px;">Catatan:</strong>
                            <ul class="catatan-list">
                                @foreach($catatan as $note)
                                    <li>{{ $note }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if(empty(trim($desc)))
                    <p style="color: var(--muted); font-style: italic;">Belum ada deskripsi.</p>
                @endif

                <hr>

                {{-- Tombol hapus --}}
                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin hapus buket ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" style="border-radius: 10px;">
                        <i class="bi bi-trash"></i> Hapus Buket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* List isi bucket */
    .isi-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .isi-list li {
        position: relative;
        padding: 8px 12px 8px 34px;
        margin-bottom: 6px;
        background: var(--cream);
        border-left: 3px solid var(--accent);
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .isi-list li::before {
        content: '🌸';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.85rem;
    }

    /* Baris info (Ukuran, Cocok untuk) */
    .info-row {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px dashed #e8e2d8;
        font-size: 0.9rem;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: var(--muted);
        min-width: 110px;
    }
    .info-value {
        color: var(--ink);
        font-weight: 600;
    }

    /* Box catatan */
    .catatan-box {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        background: #fff8e7;
        border-left: 4px solid #f0b95a;
        border-radius: 8px;
        padding: 14px 16px;
        margin-top: 16px;
        color: #7a5a1e;
        font-size: 0.9rem;
        line-height: 1.6;
    }
    .catatan-box i {
        color: #f0b95a;
        font-size: 1.1rem;
        margin-top: 2px;
    }
    .catatan-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .catatan-list li {
        position: relative;
        padding-left: 16px;
        margin-bottom: 4px;
    }
    .catatan-list li::before {
        content: '•';
        position: absolute;
        left: 2px;
        color: #d4a574;
        font-weight: 700;
    }
</style>
@endsection
