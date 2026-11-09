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

        <!-- Ambil session pilihan di luar loop -->
        @php
            $selectedItems = session('selected_items', $cart->pluck('id')->toArray());
        @endphp

        <!-- BUNGKUS DENGAN FORM UNTUK AUTO-SUBMIT CHECKBOX (PHP MURNI) -->
        <form action="{{ route('cart.update_selection') }}" method="POST" id="formPilihProduk">
            @csrf
            <div class="card border-0 shadow-sm p-3 p-md-4" style="border-radius: 14px;">
                @foreach($cart as $item)
                    @php
                        $product = is_object($item) && isset($item->product) ? $item->product : null;
                        $namaProduk = $product ? $product->name : ($item['name'] ?? $item->name ?? 'Buket');
                        $hargaProduk = $product ? $product->price : ($item['price'] ?? $item->price ?? 0);
                        $gambarProduk = $product ? $product->image : ($item['image'] ?? $item->image ?? null);
                        $qty = is_object($item) ? ($item->quantity ?? 1) : ($item['quantity'] ?? $item['qty'] ?? 1);
                        $customRincian = is_object($item) ? ($item->custom_text ?? null) : ($item['custom_text'] ?? null);

                        // Ambil ID keranjang (pastikan menggunakan ID dari database)
                        $cartId = is_object($item) ? ($item->id ?? 0) : ($item['id'] ?? $loop->index);

                        $subtotal = $hargaProduk * $qty;

                        // Cek apakah ID ini ada di session
                        $isChecked = in_array($cartId, $selectedItems);

                        // Jika dicentang, tambahkan ke total
                        if($isChecked) {
                            $totalKeranjang += $subtotal;
                        }
                    @endphp

                    <div class="row align-items-center border-bottom py-3">
                        <!-- CHECKBOX PHP MURNI (AUTO SUBMIT SAAT DIKLIK) -->
                        <div class="col-1 col-md-1 text-center">
                            <input type="checkbox" name="selected_items[]" value="{{ $cartId }}"
                                   class="form-check-input" style="transform: scale(1.5); cursor: pointer;"
                                   onchange="document.getElementById('formPilihProduk').submit();"
                                   {{ $isChecked ? 'checked' : '' }}>
                        </div>

                        <div class="col-3 col-md-2">
                            @if(!empty($gambarProduk))
                                <img src="{{ asset('storage/' . $gambarProduk) }}" alt="{{ $namaProduk }}" class="img-fluid rounded" style="max-height: 90px;">
                            @else
                                <div class="rounded d-flex align-items-center justify-content-center" style="height: 75px; background: #faf6f0;">
                                    <i class="bi bi-flower2" style="font-size: 2rem; color: #e9897d;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-8 col-md-5">
                            <h5 class="fw-bold mb-1" style="color: #1e3d2f;">{{ $namaProduk }}</h5>
                            <p class="text-muted small mb-1">{{ $customRincian ?: 'Produk buket' }}</p>
                            <span class="small">Jumlah: {{ $qty }}</span>
                        </div>
                        <div class="col-6 col-md-3 text-md-end mt-2 mt-md-0 fw-bold" style="color: #1e3d2f;">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </div>
                        <div class="col-6 col-md-1 text-end mt-2 mt-md-0">
                            <!-- Tombol Hapus menggunakan JavaScript alert sederhana agar tidak bentrok dengan form checkbox -->
                            <button type="button" onclick="if(confirm('Hapus buket ini?')) { document.getElementById('formHapus{{ $cartId }}').submit(); }" class="btn btn-sm btn-outline-danger" title="Hapus Buket">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center py-3">
                    <span class="fw-bold">Subtotal Terpilih</span>
                    <span class="fw-bold" style="color: #1e3d2f;">Rp {{ number_format($totalKeranjang, 0, ',', '.') }}</span>
                </div>
            </div>
        </form>

        <!-- FORM HAPUS DIPISAH DARI FORM CHECKBOX (AGAR TIDAK BENTROK) -->
        @foreach($cart as $item)
            @php $cartIdHapus = is_object($item) ? ($item->id ?? 0) : ($item['id'] ?? $loop->index); @endphp
            <form id="formHapus{{ $cartIdHapus }}" action="{{ route('cart.remove', $cartIdHapus) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-success">
                <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
            </a>
            <a href="{{ route('checkout.index') }}" class="btn text-white" style="background-color: #1e3d2f;">
                Lanjut ke Checkout <i class="bi bi-arrow-right ms-1"></i>
            </a>
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
