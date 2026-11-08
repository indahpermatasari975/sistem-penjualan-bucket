@extends('layouts.app')

@section('title', 'Pembayaran & Checkout - Buket House')

@section('content')
<!-- Panggil font khusus untuk judul -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<div class="container mt-5 mb-5">

    <!-- JUDUL HALAMAN -->
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1e3d2f; font-size: 2.3rem;">
            Pembayaran & <span style="color: #e9897d;">Checkout</span>
        </h2>
        <p class="text-muted" style="font-size: 0.9rem;">Periksa kembali detail pesanan Anda dan lengkapi informasi pengiriman.</p>
    </div>

    <!-- Notifikasi Sukses/Gagal (Jika Ada) -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        <!-- ========================================== -->
        <!-- KOLOM KIRI: FORM PENGIRIMAN & PEMBAYARAN   -->
        <!-- ========================================== -->
        <div class="col-lg-7">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf

                <!-- CARD 1: Alamat Pengiriman -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 14px;">
                    <h5 class="fw-bold mb-3" style="color: #1e3d2f;">
                        <i class="bi bi-geo-alt text-danger me-2"></i> Alamat & Informasi Pengiriman
                    </h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Lengkap Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penerima" class="form-control @error('nama_penerima') is-invalid @enderror" value="{{ old('nama_penerima', Auth::user()->name ?? '') }}" required>
                        @error('nama_penerima') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                        <input type="tel" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890" required>
                        @error('telepon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota" required>{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <div class="small mt-2" id="ongkirInfo" aria-live="polite">Masukkan Alamat Dulu</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Catatan Kurir (Opsional)</label>
                        <input type="text" name="catatan_kurir" class="form-control" value="{{ old('catatan_kurir') }}" placeholder="Contoh: Titipkan di satpam jika tidak ada orang">
                    </div>
                </div>

                <!-- CARD 2: Metode Pembayaran (Dropdown Select Box Sederhana) -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 14px;">
                    <h5 class="fw-bold mb-3" style="color: #1e3d2f;">
                        <i class="bi bi-credit-card text-success me-2"></i> Pilihan Metode Pembayaran
                    </h5>

                    <div class="mb-1">
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select form-select-lg py-3 @error('metode_pembayaran') is-invalid @enderror" style="border-radius: 10px; font-size: 0.95rem;" required>
                            <option value="" disabled {{ old('metode_pembayaran') ? '' : 'selected' }}>Pilih Metode Pembayaran</option>
                            <option value="Non Tunai" {{ old('metode_pembayaran') == 'Non Tunai' ? 'selected' : '' }}>Non Tunai (QRIS, Transfer Bank, E-Wallet)</option>
                            <option value="COD" {{ old('metode_pembayaran') == 'COD' ? 'selected' : '' }}>COD (Bayar Tunai di Tempat)</option>
                        </select>
                        @error('metode_pembayaran') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Tombol Konfirmasi -->
                <button type="button" class="btn btn-lg w-100 py-3 fw-bold text-white shadow-sm mb-4" id="btnBukaKonfirmasi" style="background-color: #1e3d2f; border-radius: 10px;">
                    <i class="bi bi-shield-check me-2"></i> Konfirmasi & Bayar
                </button>
            </form>

            <div class="modal fade" id="konfirmasiCheckout" tabindex="-1" aria-labelledby="judulKonfirmasi" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="judulKonfirmasi">Konfirmasi Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin memproses pesanan ini? Total:
                            <strong id="modalTotal">Rp 0</strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Periksa Lagi</button>
                            <button type="button" class="btn text-white" id="submitCheckout" style="background-color: #1e3d2f;">Ya, Proses Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM KANAN: RINGKASAN PESANAN             -->
        <!-- ========================================== -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 90px; border-radius: 14px;">
                <h5 class="fw-bold mb-3" style="color: #1e3d2f;">
                    <i class="bi bi-receipt me-2"></i> Ringkasan Pesanan
                </h5>

                @php
                    $totalKeseluruhan = 0;
                    $items = [];
                    if($checkoutItem) {
                        $items = [$checkoutItem];
                    } elseif(isset($cart) && count($cart) > 0) {
                        $items = $cart;
                    }
                @endphp

                @if(count($items) > 0)
                    <!-- List Item -->
                    @foreach($items as $item)
                        @php
                            $isObj = is_object($item);
                            $product = $isObj && isset($item->product) ? $item->product : null;
                            $namaItem = $product ? $product->name : ($item['name'] ?? $item->name ?? 'Buket');
                            $hargaItem = $product ? $product->price : ($item['price'] ?? $item->price ?? 0);
                            $gambarItem = $product ? $product->image : ($item['image'] ?? $item->image ?? null);
                            $qty = $isObj ? ($item->quantity ?? 1) : ($item['quantity'] ?? $item['qty'] ?? 1);
                            $customKet = $isObj ? ($item->custom_text ?? '') : ($item['custom_text'] ?? '');

                            $subtotal = $hargaItem * $qty;
                            $totalKeseluruhan += $subtotal;
                        @endphp

                        <div class="d-flex gap-3 align-items-center p-3 rounded-3 mb-3" style="background-color: #faf6f0;">
                            <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center p-2" style="width: 60px; height: 60px; flex-shrink: 0;">
                                @if(!empty($gambarItem))
                                    <img src="{{ asset('storage/' . $gambarItem) }}" class="img-fluid rounded" style="max-height: 45px;">
                                @else
                                    <i class="bi bi-flower2" style="font-size: 1.8rem; color: #e9897d;"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $namaItem }} (x{{ $qty }})</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">{{ $customKet }}</p>
                                <span class="fw-bold" style="color: #1e3d2f; font-size: 0.9rem;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                    <span id="subtotalProduk" data-subtotal="{{ $totalKeseluruhan }}" class="d-none"></span>

                    <!-- Perhitungan Biaya -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-semibold">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-semibold" id="nominalOngkir">Masukkan Alamat Dulu</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                            <span class="text-muted">Biaya Layanan & Kartu Ucapan</span>
                            <span class="fw-semibold text-success">Gratis</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Total Tagihan</h5>
                            <h4 class="fw-bold mb-0" style="color: #1e3d2f;">
                                <span id="totalTagihan">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                            </h4>
                        </div>
                    </div>
                @else
                    <span id="subtotalProduk" data-subtotal="0" class="d-none"></span>
                    <!-- Tampilan Jika Belum Ada Barang -->
                    <div class="text-center py-4">
                        <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Belum ada item pesanan yang dipilih.</p>
                        <a href="{{ route('home') }}" class="btn btn-outline-success btn-sm">Lihat Katalog</a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
<!-- Panggil Library Snap JS Midtrans Sandbox -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alamatInput = document.getElementById('alamat');
        const subtotalElement = document.getElementById('subtotalProduk');
        const ongkirInfo = document.getElementById('ongkirInfo');
        const nominalOngkir = document.getElementById('nominalOngkir');
        const totalTagihan = document.getElementById('totalTagihan');
        const modalTotal = document.getElementById('modalTotal');
        const checkoutForm = document.getElementById('checkoutForm');
        const btnBukaKonfirmasi = document.getElementById('btnBukaKonfirmasi');
        const submitCheckoutBtn = document.getElementById('submitCheckout');
        const modalElement = document.getElementById('konfirmasiCheckout');
        const bsModal = new bootstrap.Modal(modalElement);

        const subtotal = Number(subtotalElement.dataset.subtotal);
        const wilayahGratis = ['padang', 'pasar baru', 'unand', 'universitas andalas'];

        function formatRupiah(nilai) {
            return new Intl.NumberFormat('id-ID').format(nilai);
        }

        // Fungsi menghitung ongkos kirim otomatis
        function hitungOngkir() {
            const alamat = alamatInput.value.trim().toLowerCase();

            if (alamat === '') {
                ongkirInfo.textContent = 'Masukkan Alamat Dulu';
                ongkirInfo.className = 'small mt-2 text-muted';
                nominalOngkir.textContent = 'Masukkan Alamat Dulu';
                nominalOngkir.className = 'fw-semibold text-muted';
                totalTagihan.textContent = 'Rp ' + formatRupiah(subtotal);
                modalTotal.textContent = 'Rp ' + formatRupiah(subtotal);
                return;
            }

            let ongkir = 15000;
            for (const wilayah of wilayahGratis) {
                if (alamat.includes(wilayah)) {
                    ongkir = 0;
                    break;
                }
            }

            if (ongkir === 0) {
                ongkirInfo.textContent = 'Alamat mendapat gratis ongkir.';
                ongkirInfo.className = 'small mt-2 text-success';
                nominalOngkir.textContent = 'Gratis';
                nominalOngkir.className = 'fw-semibold text-success';
            } else {
                ongkirInfo.textContent = 'Ongkos kirim: Rp ' + formatRupiah(ongkir);
                ongkirInfo.className = 'small mt-2 text-danger';
                nominalOngkir.textContent = 'Rp ' + formatRupiah(ongkir);
                nominalOngkir.className = 'fw-semibold';
            }

            const total = subtotal + ongkir;
            totalTagihan.textContent = 'Rp ' + formatRupiah(total);
            modalTotal.textContent = 'Rp ' + formatRupiah(total);
        }

        alamatInput.addEventListener('input', hitungOngkir);
        hitungOngkir();

        // 1. Validasi form saat tombol "Konfirmasi & Bayar Sekarang" diklik sebelum membuka modal
        btnBukaKonfirmasi.addEventListener('click', function () {
            if (!checkoutForm.checkValidity()) {
                checkoutForm.reportValidity();
                return;
            }
            bsModal.show();
        });

        // 2. Proses saat tombol "Ya, Proses Pesanan" di dalam modal diklik
        submitCheckoutBtn.addEventListener('click', function () {
            // Tampilkan status loading pada tombol modal
            submitCheckoutBtn.disabled = true;
            submitCheckoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghubungi Midtrans...';

            const formData = new FormData(checkoutForm);

            // Kirim data pesanan via AJAX (fetch) ke CheckoutController
            fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(async function (response) {
                // Tangani jika terjadi error validasi (HTTP 422)
                if (response.status === 422) {
                    const errorJson = await response.json();
                    bsModal.hide();
                    submitCheckoutBtn.disabled = false;
                    submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';

                    let pesan = '';
                    for (const key in errorJson.errors) {
                        pesan += '- ' + errorJson.errors[key].join(', ') + '\n';
                    }
                    alert('Mohon periksa data pesanan Anda:\n' + pesan);
                    return null;
                }

                // Tangani jika terjadi error server (HTTP 500)
                if (!response.ok) {
                    const errorJson = await response.json().catch(() => ({}));
                    bsModal.hide();
                    submitCheckoutBtn.disabled = false;
                    submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';
                    alert('Gagal: ' + (errorJson.message || 'Terjadi kesalahan sistem saat membuat pesanan.'));
                    return null;
                }

                return response.json();
            })
            .then(function (data) {
                if (!data) return;

                // Sembunyikan modal konfirmasi
                bsModal.hide();
                submitCheckoutBtn.disabled = false;
                submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';

                // JIKA METODE COD: Langsung arahkan ke halaman pesanan berhasil
                if (data.is_cod) {
                    window.location.href = data.redirect;
                    return;
                }

                // JIKA METODE MIDTRANS: Munculkan Pop-up Snap JS di halaman checkout ini!
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        // 1. KETIKA PEMBAYARAN SUKSES: baru diarahkan ke halaman sukses!
                        onSuccess: function (result) {
                            window.location.href = data.redirect;
                        },
                        // 2. KETIKA MENUNGGU PEMBAYARAN (onPending) -> Arahkan ke Pesanan Saya
                        onPending: function (result) {
                            alert('Pesanan Anda telah tersimpan dengan status Belum Dibayar. Silakan selesaikan pembayaran.');
                            window.location.href = "{{ route('pesanan.index') }}";
                        },
                        // 3. KETIKA PEMBAYARAN GAGAL (onError)
                        onError: function (result) {
                            alert('Pembayaran gagal! Silakan coba lagi.');
                            submitCheckoutBtn.disabled = false;
                            submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';
                        },
                        // 4. KETIKA USER MENUTUP POP-UP (onClose) -> Arahkan ke Pesanan Saya
                        onClose: function () {
                            alert('Anda menutup pop-up pembayaran. Pesanan Anda tersimpan di menu Pesanan Saya dengan status Belum Dibayar.');
                            window.location.href = "{{ route('pesanan.index') }}";
                        }
                    });
                } else {
                    // Fallback jika token tidak terbentuk
                    window.location.href = data.redirect;
                }
            })
            .catch(function (error) {
                console.error('Fetch error:', error);
                bsModal.hide();
                submitCheckoutBtn.disabled = false;
                submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';
                alert('Terjadi kendala jaringan saat memproses pesanan.');
            });
        });
    });
</script>
@endsection
