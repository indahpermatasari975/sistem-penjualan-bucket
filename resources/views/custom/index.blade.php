@extends('layouts.app')

@section('title', 'Rakit Buket Custom - Buket House')

@section('content')
<div class="container mt-5 mb-5">

    <!-- Header Halaman -->
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1e3d2f; font-size: 2.5rem;">
            Rakit Buket <span style="color: #e9897d;">Custom</span>
        </h2>
        <p class="text-muted">Kreasikan buket impianmu sendiri dengan memilih bunga, uang, boneka, dan warna buket!</p>
    </div>

    <!-- Layout 2 Kolom Responsif -->
    <div class="row g-4">

        <!-- ================= A. KOLOM KIRI: PRATINJAU BUKET ================= -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 90px; border-radius: 16px; background-color: #ffffff;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1e3d2f;">
                        <i class="bi bi-eye text-danger me-2"></i> Pratinjau Buket Custom
                    </h5>
                </div>
                <p class="text-muted small mb-3">
                    Foto produk ini menjadi contoh bentuk dasar buket. Pilihan Anda akan memperbarui ringkasan dan estimasi harga.
                </p>

                <div class="border rounded-3 overflow-hidden shadow-sm bg-light" style="aspect-ratio: 1 / 1;">
                    @if ($product && $product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="Pratinjau {{ $product->name }}"
                             class="w-100 h-100 object-fit-cover">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-flower2" style="font-size: 8rem; color: #e9897d;"></i>
                        </div>
                    @endif
                </div>

                <!-- Ringkasan Pilihan -->
                <div class="mt-4 p-3 rounded-3" style="background-color: #faf6f0; border: 1px dashed #d6cec2;">
                    <div class="small mb-1">
                        <span class="text-muted">Bunga:</span>
                        <strong id="ringkasBunga" class="text-dark">-</strong>
                    </div>
                    <div class="small mb-1">
                        <span class="text-muted">Uang:</span>
                        <strong id="ringkasUang" class="text-dark">-</strong>
                    </div>
                    <div class="small mb-1">
                        <span class="text-muted">Boneka:</span>
                        <strong id="ringkasBoneka" class="text-dark">-</strong>
                    </div>
                    <div class="small">
                        <span class="text-muted">Warna buket:</span>
                        <strong id="ringkasWarna" class="text-dark">-</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= B. KOLOM KANAN: FORM RAKIT BUKET CUSTOM ================= -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background-color: #ffffff;">
                <h4 class="fw-bold mb-1" style="color: #1e3d2f;">Pilih Elemen Buket Anda</h4>
                <p class="text-muted small mb-4">Pilih komponen buket untuk melihat ringkasan dan estimasi harga.</p>

                <!-- Form Custom Buket -->
                <form action="{{ route('custom.process') }}" method="POST" id="formRakitBuket" data-harga-dasar="{{ $product->price ?? 0 }}">
                    @csrf
                    @if ($product)
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @endif

                    <!-- 1. Pilih Bunga -->
                    <div class="mb-3">
                        <label for="bunga_isi" class="form-label fw-bold">
                            <i class="bi bi-flower2 text-success me-1"></i> 1. Bunga <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg" name="bunga_isi" id="bunga_isi" onchange="hitungTotal()" required>
                            <option value="Tanpa Uang" data-price="0">-- Tanpa Uang --</option>
                            @foreach ($customOptions['Bunga'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}"
                                        {{ old('bunga_isi', $loop->first ? $option['value'] : '') === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Pilih Uang (Opsional) -->
                    <div class="mb-3">
                        <label for="uang_nominal" class="form-label fw-bold">
                            <i class="bi bi-cash-stack text-success me-1"></i> 2. Uang <span class="text-muted fw-normal"></span>
                        </label>
                        <select class="form-select" name="uang_nominal" id="uang_nominal" onchange="hitungTotal()">
                            <option value="Tanpa Uang" data-price="0">-- Tanpa Uang --</option>
                            @foreach ($customOptions['Uang'] as $option)
                                <option value="{{ $option['value'] }}" data-price="0"
                                        {{ old('uang_nominal', $loop->first ? $option['value'] : '') === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Banyak Lembar Uang (Opsional) -->
                    <div class="mb-3">
                        <label for="jumlah_lembar" class="form-label fw-bold">
                            <i class="bi bi-receipt text-success me-1"></i> 3. Banyak Lembar Uang <span class="text-muted fw-normal"></span>
                        </label>
                        <select class="form-select" name="jumlah_lembar" id="jumlah_lembar" onchange="hitungTotal()">
                            <option value="0" data-price="0">-- Tanpa Lembar Uang --</option>
                            @foreach ($customOptions['Banyak Lembar Uang'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}"
                                        {{ old('jumlah_lembar', $loop->first ? $option['value'] : '') === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Pilih Boneka (Opsional) -->
                    <div class="mb-3">
                        <label for="tambahan_boneka" class="form-label fw-bold">
                            <i class="bi bi-heart text-danger me-1"></i> 4. Boneka <span class="text-muted fw-normal"></span>
                        </label>
                        <select class="form-select" name="tambahan_boneka" id="tambahan_boneka" onchange="hitungTotal()">
                            <option value="Tanpa Boneka" data-price="0">-- Tanpa Boneka --</option>
                            @foreach ($customOptions['Boneka'] as $option)
                                <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}"
                                        {{ old('tambahan_boneka', $option['value'] === 'Tanpa Boneka' ? $option['value'] : '') === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. Warna Buket -->
                    <div class="mb-3">
                        <label for="warna_buket" class="form-label fw-bold">
                            <i class="bi bi-palette text-primary me-1"></i> 5. Warna Buket <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="warna_buket" id="warna_buket" onchange="hitungTotal()" required>
                            @foreach ($customOptions['Warna Buket'] as $option)
                                <option value="{{ $option['value'] }}" data-price="0"
                                        {{ old('warna_buket', $loop->first ? $option['value'] : '') === $option['value'] ? 'selected' : '' }}>
                                    {{ $option['name'] }} (+Rp 0)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 6. Pesan Ucapan -->
                    <div class="mb-3">
                        <label for="pesan_ucapan" class="form-label fw-bold">
                            <i class="bi bi-card-text text-secondary me-1"></i> 6. Pesan Ucapan <span class="text-muted fw-normal">(Kartu Ucapan)</span>
                        </label>
                        <input type="text" class="form-control" name="pesan_ucapan" id="pesan_ucapan" value="{{ old('pesan_ucapan') }}" placeholder="Contoh: Selamat Wisuda ya! Semoga makin sukses selalu.">
                    </div>

                    <!-- 7. Request Khusus -->
                    <div class="mb-4">
                        <label for="request_khusus" class="form-label fw-bold">
                            <i class="bi bi-pencil-square text-secondary me-1"></i> 7. Request Khusus <span class="text-muted fw-normal"></span>
                        </label>
                        <textarea class="form-control" name="request_khusus" id="request_khusus" rows="2" placeholder="Catatan tambahan untuk perangkai...">{{ old('request_khusus') }}</textarea>
                    </div>

                    <!-- Estimasi Total Harga -->
                    <div class="card border-0 mb-4 p-3 shadow-sm" style="background: linear-gradient(135deg, #fcf9f4 0%, #faece9 100%); border-radius: 12px; border-left: 6px solid #1e3d2f !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small d-block">Estimasi Total Harga Buket:</span>
                                <h2 class="fw-bold mb-0" id="tampilanTotalHarga" style="color: #1e3d2f;">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</h2>
                                <small class="text-muted" style="font-size: 0.75rem;">Harga produk + bunga + boneka + biaya jasa lembar uang.</small>
                            </div>
                        </div>
                        <!-- Hidden input untuk dikirim ke Controller -->
                        <input type="hidden" name="total_price" id="inputTotalPrice" value="{{ $product->price ?? 0 }}">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <button type="submit" name="action" value="keranjang" class="btn btn-outline-success btn-lg w-100 py-3 fw-bold">
                                <i class="bi bi-cart-plus me-1"></i> Masukkan ke Keranjang
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" name="action" value="checkout" class="btn btn-lg w-100 py-3 fw-bold text-white shadow-sm" style="background-color: #1e3d2f;">
                                <i class="bi bi-credit-card me-1"></i> Beli Sekarang
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left"></i> Kembali ke Katalog Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= LOGIKA JAVASCRIPT ================= -->
<script>
    // Ambil harga dasar produk
    const hargaDasarProduk = Number(document.getElementById('formRakitBuket').dataset.hargaDasar);

    function hitungTotal() {
        // Ambil elemen dropdown
        const selectBunga = document.getElementById('bunga_isi');
        const selectUang = document.getElementById('uang_nominal');
        const selectJumlahLembar = document.getElementById('jumlah_lembar');
        const selectBoneka = document.getElementById('tambahan_boneka');
        const selectWarna = document.getElementById('warna_buket');

        // Ambil harga dari atribut data-price
        const hargaBunga = Number(selectBunga.selectedOptions[0]?.dataset.price || 0);
        const hargaLembar = Number(selectJumlahLembar.selectedOptions[0]?.dataset.price || 0);
        const hargaBoneka = Number(selectBoneka.selectedOptions[0]?.dataset.price || 0);

        // Hitung total
        const totalHarga = hargaDasarProduk + hargaBunga + hargaLembar + hargaBoneka;

        // Tampilkan total ke layar
        document.getElementById('tampilanTotalHarga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');

        // Masukkan nilai ke input hidden agar terbaca oleh Controller
        document.getElementById('inputTotalPrice').value = totalHarga;

        // Update Teks Ringkasan di Kiri
        document.getElementById('ringkasBunga').textContent = selectBunga.value;

        // Logika khusus untuk Uang (jika memilih "Tanpa Uang")
        if (selectUang.value === 'Tanpa Uang') {
            document.getElementById('ringkasUang').textContent = 'Tanpa Uang';
        } else {
            document.getElementById('ringkasUang').textContent =
                'Rp ' + Number(selectUang.value).toLocaleString('id-ID') + ' (' + selectJumlahLembar.value + ')';
        }

        // Logika khusus untuk Boneka (jika memilih "Tanpa Boneka")
        if (selectBoneka.value === 'Tanpa Boneka') {
            document.getElementById('ringkasBoneka').textContent = 'Tanpa Boneka';
        } else {
            document.getElementById('ringkasBoneka').textContent = selectBoneka.value;
        }

        document.getElementById('ringkasWarna').textContent = selectWarna.value;
    }

    // Jalankan saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', hitungTotal);
</script>
@endsection
