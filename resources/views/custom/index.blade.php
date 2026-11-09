@extends('layouts.app')

@section('title', 'Rakit Buket Custom - Buket House')

@section('content')
<div class="container mt-5 mb-5">

    <!-- Header Halaman -->
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1e3d2f; font-size: 2.5rem;">
            Rakit Buket <span style="color: #e9897d;">Custom</span>
        </h2>
        <p class="text-muted">Kreasikan buket impianmu sendiri dengan memilih bunga, uang, boneka, snack, dan warna buket!</p>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: PRATINJAU -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 90px; border-radius: 16px; background-color: #ffffff;">
                <h5 class="fw-bold mb-3" style="color: #1e3d2f;">
                    <i class="bi bi-eye text-danger me-2"></i> Pratinjau Buket Custom
                </h5>

                <div class="border rounded-3 overflow-hidden shadow-sm bg-light" style="aspect-ratio: 1 / 1;">
                    @if ($product && $product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Pratinjau" class="w-100 h-100 object-fit-cover">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-flower2" style="font-size: 8rem; color: #e9897d;"></i>
                        </div>
                    @endif
                </div>

                <!-- Ringkasan Pilihan -->
                <div class="mt-4 p-3 rounded-3" style="background-color: #faf6f0; border: 1px dashed #d6cec2;">
                    <div class="small mb-1"><span class="text-muted">Bunga:</span> <strong id="ringkasBunga" class="text-dark">-</strong></div>
                    <div class="small mb-1"><span class="text-muted">Uang:</span> <strong id="ringkasUang" class="text-dark">-</strong></div>
                    <div class="small mb-1"><span class="text-muted">Boneka:</span> <strong id="ringkasBoneka" class="text-dark">-</strong></div>
                    <!-- Tambahan Ringkasan Snack -->
                    <div class="small mb-1"><span class="text-muted">Snack:</span> <strong id="ringkasSnack" class="text-dark">-</strong></div>
                    <div class="small mb-1"><span class="text-muted">Warna Kertas:</span> <strong id="ringkasWarna" class="text-dark">-</strong></div>
                    <div class="small"><span class="text-muted">Pita:</span> <strong id="ringkasPita" class="text-dark">-</strong></div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM CUSTOM -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background-color: #ffffff;">
                <h4 class="fw-bold mb-1" style="color: #1e3d2f;">Pilih Elemen Buket Anda</h4>
                <p class="text-muted small mb-4">Pilih komponen yang mau ditambahkan saja.</p>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong><i class="bi bi-exclamation-triangle me-2"></i>Periksa Kembali Pilihan Anda!</strong>
                        <ul class="mb-0 mt-1 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('custom.process') }}" method="POST" id="formRakitBuket" data-harga-dasar="{{ $product->price ?? 0 }}">
                    @csrf
                    @if ($product) <input type="hidden" name="product_id" value="{{ $product->id }}"> @endif

                    <!-- 1. Bunga -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-flower2 text-success me-1"></i> 1. Bunga <span class="text-danger">*</span></label>
                        <select class="form-select" name="bunga_isi" id="bunga_isi" onchange="hitungTotal()" required>
                            <option value="Tanpa Bunga" data-price="0">-- Tanpa Bunga --</option>
                            @foreach ($customOptions['Bunga'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}" {{ old('bunga_isi') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Uang -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-cash-stack text-success me-1"></i> 2. Uang</label>
                        <select class="form-select" name="uang_nominal" id="uang_nominal" onchange="hitungTotal()">
                            <option value="Tanpa Uang" data-price="0">-- Tanpa Uang --</option>
                            @foreach ($customOptions['Uang'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}" {{ old('uang_nominal') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Banyak Lembar Uang -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-receipt text-success me-1"></i> 3. Banyak Lembar Uang</label>
                        <select class="form-select" name="jumlah_lembar" id="jumlah_lembar" onchange="hitungTotal()">
                            <option value="0" data-price="0">-- Tanpa Lembar Uang --</option>
                            @foreach ($customOptions['Banyak Lembar Uang'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}" {{ old('jumlah_lembar') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Boneka -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-heart text-danger me-1"></i> 4. Boneka</label>
                        <select class="form-select" name="tambahan_boneka" id="tambahan_boneka" onchange="hitungTotal()">
                            @foreach ($customOptions['Boneka'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}" {{ old('tambahan_boneka') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} {{ $option['price'] > 0 ? '(+Rp ' . number_format($option['price'], 0, ',', '.') . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. SNACK -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-cookie text-warning me-1"></i> 5. Pilihan Snack</label>
                        <select class="form-select" name="tambahan_snack" id="tambahan_snack" onchange="hitungTotal()">
                            <option value="Tanpa Snack" data-price="0">-- Tanpa Snack --</option>
                            @foreach ($customOptions['Snack'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}" {{ old('tambahan_snack') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 6. Warna Buket -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-palette text-primary me-1"></i> 6. Warna Kertas Buket <span class="text-danger">*</span></label>
                        <select class="form-select" name="warna_buket" id="warna_buket" onchange="hitungTotal()" required>
                            @foreach ($customOptions['Warna Buket'] as $option)
                                <option value="{{ $option['value'] }}" data-price="0" {{ old('warna_buket') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 7. Pilihan Pita -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-bookmark-heart text-danger me-1"></i> 7. Pilihan Pita</label>
                        <select class="form-select" name="warna_pita" id="warna_pita" onchange="hitungTotal()">
                            <option value="Senada dengan Buket" {{ old('warna_pita') == 'Senada dengan Buket' ? 'selected' : '' }}>Pita Senada dengan Buket</option>
                            <option value="Pita Satin Putih" {{ old('warna_pita') == 'Pita Satin Putih' ? 'selected' : '' }}>Pita Satin Putih</option>
                            <option value="Pita Satin Emas" {{ old('warna_pita') == 'Pita Satin Emas' ? 'selected' : '' }}>Pita Satin Emas</option>
                            <option value="Pita Satin Pink" {{ old('warna_pita') == 'Pita Satin Pink' ? 'selected' : '' }}>Pita Satin Pink</option>
                            <option value="Pita Satin Hitam" {{ old('warna_pita') == 'Pita Satin Hitam' ? 'selected' : '' }}>Pita Satin Hitam</option>
                            <option value="Pita Satin Maroon" {{ old('warna_pita') == 'Pita Satin Maroon' ? 'selected' : '' }}>Pita Satin Maroon</option>
                        </select>
                    </div>

                    <!-- 8. Pesan Ucapan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-card-text text-secondary me-1"></i> 8. Pesan Ucapan</label>
                        <input type="text" class="form-control" name="pesan_ucapan" id="pesan_ucapan" value="{{ old('pesan_ucapan') }}" placeholder="Contoh: Selamat Wisuda!">
                    </div>

                    <!-- 9. Request Khusus -->
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="bi bi-pencil-square text-secondary me-1"></i> 9. Request Khusus</label>
                        <textarea class="form-control" name="request_khusus" id="request_khusus" rows="2" placeholder="Catatan tambahan...">{{ old('request_khusus') }}</textarea>
                    </div>

                    <!-- Total Harga -->
                    <div class="card border-0 mb-4 p-3 shadow-sm" style="background-color: #faf6f0; border-left: 6px solid #1e3d2f !important;">
                        <span class="text-muted small">Estimasi Total Harga:</span>
                        <h2 class="fw-bold mb-0" id="tampilanTotalHarga" style="color: #1e3d2f;">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</h2>
                        <input type="hidden" name="total_price" id="inputTotalPrice" value="{{ $product->price ?? 0 }}">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <button type="submit" name="action" value="keranjang" class="btn btn-outline-success btn-lg w-100 py-3 fw-bold">
                                <i class="bi bi-cart-plus me-1"></i> Masukkan Keranjang
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" name="action" value="checkout" class="btn btn-lg w-100 py-3 fw-bold text-white shadow-sm" style="background-color: #1e3d2f;">
                                <i class="bi bi-credit-card me-1"></i> Beli Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- LOGIKA JAVASCRIPT -->
<script>
    const formRakit = document.getElementById('formRakitBuket');
    const hargaDasarProduk = Number(formRakit.dataset.hargaDasar);

    function hitungTotal() {
        const selectBunga = document.getElementById('bunga_isi');
        const hargaBunga = Number(selectBunga.options[selectBunga.selectedIndex].getAttribute('data-price')) || 0;

        const selectUang = document.getElementById('uang_nominal');
        const hargaUang = Number(selectUang.options[selectUang.selectedIndex].getAttribute('data-price')) || 0;

        const selectLembar = document.getElementById('jumlah_lembar');
        const hargaLembar = Number(selectLembar.options[selectLembar.selectedIndex].getAttribute('data-price')) || 0;

        const selectBoneka = document.getElementById('tambahan_boneka');
        const hargaBoneka = Number(selectBoneka.options[selectBoneka.selectedIndex].getAttribute('data-price')) || 0;

        // Ambil harga Snack
        const selectSnack = document.getElementById('tambahan_snack');
        const hargaSnack = Number(selectSnack.options[selectSnack.selectedIndex].getAttribute('data-price')) || 0;

        // Total Harga
        const totalHarga = hargaDasarProduk + hargaBunga + hargaUang + hargaLembar + hargaBoneka + hargaSnack;

        document.getElementById('tampilanTotalHarga').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
        document.getElementById('inputTotalPrice').value = totalHarga;

        // Update Ringkasan Kiri
        document.getElementById('ringkasBunga').innerText = selectBunga.value;
        document.getElementById('ringkasWarna').innerText = document.getElementById('warna_buket').value;
        document.getElementById('ringkasPita').innerText = document.getElementById('warna_pita').value;
        document.getElementById('ringkasSnack').innerText = selectSnack.value; // Ringkasan Snack

        if (selectUang.value == 'Tanpa Uang') {
            document.getElementById('ringkasUang').innerText = 'Tanpa Uang';
        } else {
            document.getElementById('ringkasUang').innerText = 'Rp ' + Number(selectUang.value).toLocaleString('id-ID');
        }

        document.getElementById('ringkasBoneka').innerText = selectBoneka.value;
    }

    document.addEventListener('DOMContentLoaded', function() {
        hitungTotal();
    });
</script>
@endsection
