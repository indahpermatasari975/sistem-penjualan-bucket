
@extends('layouts.app')

@section('title', 'Rakit Buket Custom - Buket House')

@section('content')
<div class="container mt-5 mb-5">

    <!-- Header Halaman -->
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1e3d2f; font-size: 2.5rem;">
            Rakit Buket <span style="color: #e9897d;">Custom</span>
        </h2>
        <p class="text-muted">
            Kreasikan buket impianmu sendiri dengan memilih bunga, uang, boneka, snack, dan warna buket!
        </p>
    </div>

    <div class="row g-4">

        <!-- KOLOM KIRI: PRATINJAU -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top"
                style="top: 90px; border-radius: 16px; background-color: #ffffff;">

                <h5 class="fw-bold mb-3" style="color: #1e3d2f;">
                    <i class="bi bi-eye text-danger me-2"></i>
                    Pratinjau Buket Custom
                </h5>

                <div class="border rounded-3 overflow-hidden shadow-sm bg-light"
                    style="aspect-ratio: 1 / 1;">

                    @if ($product && $product->image)
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="Pratinjau"
                            class="w-100 h-100 object-fit-cover">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-flower2"
                                style="font-size: 8rem; color: #e9897d;"></i>
                        </div>
                    @endif

                </div>

                <!-- Ringkasan Pilihan -->
                <div class="mt-4 p-3 rounded-3"
                    style="background-color: #faf6f0; border: 1px dashed #d6cec2;">

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

                    <div class="small mb-1">
                        <span class="text-muted">Snack:</span>
                        <strong id="ringkasSnack" class="text-dark">-</strong>
                    </div>

                    <div class="small mb-1">
                        <span class="text-muted">Warna Kertas:</span>
                        <strong id="ringkasWarna" class="text-dark">-</strong>
                    </div>

                    <div class="small">
                        <span class="text-muted">Pita:</span>
                        <strong id="ringkasPita" class="text-dark">-</strong>
                    </div>

                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM CUSTOM -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4"
                style="border-radius: 16px; background-color: #ffffff;">

                <h4 class="fw-bold mb-1" style="color: #1e3d2f;">
                    Pilih Elemen Buket Anda
                </h4>

                <p class="text-muted small mb-4">
                    Pilih komponen yang mau ditambahkan saja.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Periksa Kembali Pilihan Anda!
                        </strong>

                        <ul class="mb-0 mt-1 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ route('custom.process') }}"
                    method="POST"
                    id="formRakitBuket"
                    data-harga-dasar="0">

                    @csrf

                    @if ($product)
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @endif

                    <!-- 1. BUNGA -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-flower2 text-success me-1"></i>
                            1. Pilihan Bunga <span class="text-danger">*</span>
                        </label>

                        <select class="form-select"
                            name="bunga_isi"
                            id="bunga_isi"
                            onchange="hitungTotal()"
                            required>

                            <option value="Tanpa Bunga"
                                {{ old('bunga_isi', 'Tanpa Bunga') == 'Tanpa Bunga' ? 'selected' : '' }}
                                data-price="0">
                                -- Tanpa Bunga --
                            </option>

                            @foreach ($customOptions['Bunga'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['price'] }}"
                                    {{ old('bunga_isi') == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}
                                    (+Rp {{ number_format($option['price'], 0, ',', '.') }}/tangkai)

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- JUMLAH TANGKAI -->
                    <div class="mb-3" id="wrapper_jumlah_tangkai">

                        <label class="form-label fw-bold">
                            <i class="bi bi-flower1 text-success me-1"></i>
                            Jumlah Tangkai Bunga
                        </label>

                        <select class="form-select"
                            name="jumlah_tangkai"
                            id="jumlah_tangkai"
                            onchange="hitungTotal()">

                            <option value="0"
                                {{ old('jumlah_tangkai') == '0' ? 'selected' : '' }}>
                                0 Tangkai (Tanpa Bunga)
                            </option>

                            @foreach ([1, 2, 3, 5, 7, 10, 12, 15, 20, 25, 30] as $tangkai)
                                <option
                                    value="{{ $tangkai }}"
                                    {{ old('jumlah_tangkai', '1') == $tangkai ? 'selected' : '' }}>

                                    {{ $tangkai }} Tangkai

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 2. UANG -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-cash-stack text-success me-1"></i>
                            2. Uang
                        </label>

                        <select class="form-select"
                            name="uang_nominal"
                            id="uang_nominal"
                            onchange="hitungTotal()">

                            <option value="Tanpa Uang"
                                {{ old('uang_nominal', 'Tanpa Uang') == 'Tanpa Uang' ? 'selected' : '' }}
                                data-price="0">
                                -- Tanpa Uang --
                            </option>

                            @foreach ($customOptions['Uang'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['value'] }}"
                                    {{ old('uang_nominal') == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 3. JUMLAH LEMBAR UANG -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-receipt text-success me-1"></i>
                            3. Banyak Lembar Uang
                        </label>

                        <select class="form-select"
                            name="jumlah_lembar"
                            id="jumlah_lembar"
                            onchange="hitungTotal()">

                            <option value="0"
                                {{ old('jumlah_lembar', '0') == '0' ? 'selected' : '' }}
                                data-price="0">
                                -- Tanpa Lembar Uang --
                            </option>

                            @foreach ($customOptions['Banyak Lembar Uang'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['value'] }}"
                                    {{ old('jumlah_lembar') == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 4. BONEKA -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-heart text-danger me-1"></i>
                            4. Boneka
                        </label>

                        <select class="form-select"
                            name="tambahan_boneka"
                            id="tambahan_boneka"
                            onchange="hitungTotal()">

                            @foreach ($customOptions['Boneka'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['price'] }}"
                                    {{ old('tambahan_boneka', 'Tanpa Boneka') == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}

                                    @if ($option['price'] > 0)
                                        (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                    @endif

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 5. SNACK -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-cookie text-warning me-1"></i>
                            5. Pilihan Snack
                        </label>

                        <select class="form-select"
                            name="tambahan_snack"
                            id="tambahan_snack"
                            onchange="hitungTotal()">

                            @foreach ($customOptions['Snack'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['price'] }}"
                                    {{ old('tambahan_snack', 'Tanpa Snack') == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}

                                    @if ($option['price'] > 0)
                                        (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                    @endif

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 6. WARNA KERTAS -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-palette text-primary me-1"></i>
                            6. Warna Kertas Buket <span class="text-danger">*</span>
                        </label>

                        <select class="form-select"
                            name="warna_buket"
                            id="warna_buket"
                            onchange="hitungTotal()"
                            required>

                            @foreach ($customOptions['Warna Buket'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['price'] }}"
                                    {{ old('warna_buket', $option['value']) == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}
                                    (+Rp {{ number_format($option['price'], 0, ',', '.') }})

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 7. PILIHAN PITA -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-bookmark-heart text-danger me-1"></i>
                            7. Pilihan Pita
                        </label>

                        <select class="form-select"
                            name="warna_pita"
                            id="warna_pita"
                            onchange="hitungTotal()">

                            @foreach ($customOptions['Pita'] as $option)
                                <option
                                    value="{{ $option['value'] }}"
                                    data-price="{{ $option['price'] }}"
                                    {{ old('warna_pita', 'Senada dengan Buket') == $option['value'] ? 'selected' : '' }}>

                                    {{ $option['name'] }}

                                    @if ($option['price'] > 0)
                                        (+Rp {{ number_format($option['price'], 0, ',', '.') }})
                                    @else
                                        (Gratis)
                                    @endif

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- 8. PESAN UCAPAN -->
                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            <i class="bi bi-card-text text-secondary me-1"></i>
                            8. Pesan Ucapan
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="pesan_ucapan"
                            id="pesan_ucapan"
                            value="{{ old('pesan_ucapan') }}"
                            maxlength="255"
                            placeholder="Contoh: Selamat Wisuda!">

                    </div>

                    <!-- 9. REQUEST KHUSUS -->
                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            <i class="bi bi-pencil-square text-secondary me-1"></i>
                            9. Request Khusus
                        </label>

                        <textarea
                            class="form-control"
                            name="request_khusus"
                            id="request_khusus"
                            rows="2"
                            maxlength="1000"
                            placeholder="Catatan tambahan...">{{ old('request_khusus') }}</textarea>

                    </div>

                    <!-- TOTAL HARGA -->
                    <div class="card border-0 mb-4 p-3 shadow-sm"
                        style="background-color: #faf6f0; border-left: 6px solid #1e3d2f !important;">

                        <span class="text-muted small">Estimasi Total Harga:</span>

                        <h2 class="fw-bold mb-0"
                            id="tampilanTotalHarga"
                            style="color: #1e3d2f;">
                            Rp 0
                        </h2>

                        <input type="hidden"
                            name="total_price"
                            id="inputTotalPrice"
                            value="0">

                    </div>

                    <!-- TOMBOL AKSI -->
                    <div class="row g-2">

                        <div class="col-sm-6">
                            <button
                                type="submit"
                                name="action"
                                value="keranjang"
                                class="btn btn-outline-success btn-lg w-100 py-3 fw-bold">

                                <i class="bi bi-cart-plus me-1"></i>
                                Masukkan Keranjang

                            </button>
                        </div>

                        <div class="col-sm-6">
                            <button
                                type="submit"
                                name="action"
                                value="checkout"
                                class="btn btn-lg w-100 py-3 fw-bold text-white shadow-sm"
                                style="background-color: #1e3d2f;">

                                <i class="bi bi-credit-card me-1"></i>
                                Beli Sekarang

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
    function ambilHarga(selectId) {
        const select = document.getElementById(selectId);

        if (!select || select.selectedIndex < 0) {
            return 0;
        }

        return Number(
            select.options[select.selectedIndex].getAttribute('data-price')
        ) || 0;
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function hitungTotal() {

        // 1. BUNGA
        const selectBunga = document.getElementById('bunga_isi');
        const hargaBungaPerTangkai = ambilHarga('bunga_isi');

        const selectTangkai = document.getElementById('jumlah_tangkai');

        const jumlahTangkai = selectBunga.value === 'Tanpa Bunga'
            ? 0
            : Math.max(1, Number(selectTangkai.value) || 1);

        const hargaBunga = hargaBungaPerTangkai * jumlahTangkai;

        // 2. UANG
        const selectUang = document.getElementById('uang_nominal');

        const nominalUang = selectUang.value === 'Tanpa Uang'
            ? 0
            : Number(selectUang.value) || 0;

        const selectLembar = document.getElementById('jumlah_lembar');

        const jumlahLembar = nominalUang > 0
            ? Number(selectLembar.value) || 0
            : 0;

        const hargaUang = nominalUang * jumlahLembar;

        // 3. BONEKA
        const selectBoneka = document.getElementById('tambahan_boneka');
        const hargaBoneka = ambilHarga('tambahan_boneka');

        // 4. SNACK
        const selectSnack = document.getElementById('tambahan_snack');
        const hargaSnack = ambilHarga('tambahan_snack');

        // 5. WARNA KERTAS
        const selectWarna = document.getElementById('warna_buket');
        const hargaWarna = ambilHarga('warna_buket');

        // 6. PITA
        const selectPita = document.getElementById('warna_pita');
        const hargaPita = ambilHarga('warna_pita');

        // 7. TOTAL HARGA
        // Harga produk dasar tidak ditambahkan.
        const totalHarga =
            hargaBunga +
            hargaUang +
            hargaBoneka +
            hargaSnack +
            hargaWarna +
            hargaPita;

        // 8. TAMPILKAN TOTAL
        document.getElementById('tampilanTotalHarga').innerText =
            formatRupiah(totalHarga);

        document.getElementById('inputTotalPrice').value = totalHarga;

        // 9. RINGKASAN BUNGA
        document.getElementById('ringkasBunga').innerText =
            selectBunga.value === 'Tanpa Bunga'
                ? 'Tanpa Bunga'
                : selectBunga.value + ' (' + jumlahTangkai + ' tangkai)';

        // 10. RINGKASAN UANG
        document.getElementById('ringkasUang').innerText =
            nominalUang === 0 || jumlahLembar === 0
                ? 'Tanpa Uang'
                : formatRupiah(nominalUang) + ' (' + jumlahLembar + ' lembar)';

        // 11. RINGKASAN BONEKA
        document.getElementById('ringkasBoneka').innerText =
            selectBoneka.value + ' (' + formatRupiah(hargaBoneka) + ')';

        // 12. RINGKASAN SNACK
        document.getElementById('ringkasSnack').innerText =
            selectSnack.value + ' (' + formatRupiah(hargaSnack) + ')';

        // 13. RINGKASAN WARNA KERTAS
        document.getElementById('ringkasWarna').innerText =
            selectWarna.value + ' (' + formatRupiah(hargaWarna) + ')';

        // 14. RINGKASAN PITA
        document.getElementById('ringkasPita').innerText =
            selectPita.value + ' (' + formatRupiah(hargaPita) + ')';
    }

    // Jalankan perhitungan saat halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', function() {

        // Jika tanpa bunga, jumlah tangkai dibuat 0
        const selectBunga = document.getElementById('bunga_isi');
        const selectTangkai = document.getElementById('jumlah_tangkai');

        if (selectBunga.value === 'Tanpa Bunga') {
            selectTangkai.value = '0';
        }

        hitungTotal();
    });
</script>

@endsection
