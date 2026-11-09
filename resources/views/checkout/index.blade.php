@extends('layouts.app')

@section('title', 'Pembayaran & Checkout - Buket House')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1e3d2f; font-size: 2.3rem;">
            Pembayaran & <span style="color: #e9897d;">Checkout</span>
        </h2>
        <p class="text-muted" style="font-size: 0.9rem;">Periksa kembali detail pesanan Anda dan lengkapi informasi pengiriman.</p>
    </div>

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
        <!-- KOLOM KIRI -->
        <div class="col-lg-7">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf
                <!-- CARD 1: Alamat -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 14px;">
                    <h5 class="fw-bold mb-3" style="color: #1e3d2f;"><i class="bi bi-geo-alt text-danger me-2"></i> Alamat & Informasi Pengiriman</h5>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nama Lengkap Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="nama_penerima" class="form-control" value="{{ old('nama_penerima', Auth::user()->name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                        <input type="tel" name="telepon" class="form-control" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota" required>{{ old('alamat') }}</textarea>
                        <div class="small mt-2 text-success" id="ongkirInfo">Gratis ongkir untuk seluruh pesanan</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem;">Catatan Kurir (Opsional)</label>
                        <input type="text" name="catatan_kurir" class="form-control" value="{{ old('catatan_kurir') }}" placeholder="Contoh: Titipkan di satpam">
                    </div>
                </div>

                <!-- CARD 2: Metode Pembayaran -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 14px;">
                    <h5 class="fw-bold mb-3" style="color: #1e3d2f;"><i class="bi bi-credit-card text-success me-2"></i> Pilihan Metode Pembayaran</h5>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select form-select-lg py-3" style="border-radius: 10px; font-size: 0.95rem;" required>
                        <option value="" disabled {{ old('metode_pembayaran') ? '' : 'selected' }}>Pilih Metode Pembayaran</option>
                        <option value="Non Tunai" {{ old('metode_pembayaran') == 'Non Tunai' ? 'selected' : '' }}>Non Tunai (QRIS, Transfer Bank, E-Wallet)</option>
                        <option value="COD" {{ old('metode_pembayaran') == 'COD' ? 'selected' : '' }}>COD (Bayar Tunai di Tempat)</option>
                    </select>
                </div>

                <button type="button" class="btn btn-lg w-100 py-3 fw-bold text-white shadow-sm mb-4" id="btnBukaKonfirmasi" style="background-color: #1e3d2f; border-radius: 10px;">
                    <i class="bi bi-shield-check me-2"></i> Konfirmasi & Bayar
                </button>
            </form>

            <!-- Modal Konfirmasi -->
            <div class="modal fade" id="konfirmasiCheckout" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Konfirmasi Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">Apakah Anda yakin ingin memproses pesanan ini? Total: <strong id="modalTotal">Rp 0</strong></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Periksa Lagi</button>
                            <button type="button" class="btn text-white" id="submitCheckout" style="background-color: #1e3d2f;">Ya, Proses Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: RINGKASAN -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 90px; border-radius: 14px;">
                <h5 class="fw-bold mb-3" style="color: #1e3d2f;"><i class="bi bi-receipt me-2"></i> Ringkasan Pesanan</h5>

                @php
                    $totalKeseluruhan = 0;
                    $items = $checkoutItem ? [$checkoutItem] : (session('checkout_item') ? [session('checkout_item')] : (session('custom_buket') ? [session('custom_buket')] : ($cart ?? [])));
                @endphp

                @if(count($items) > 0)
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
                                @if(!empty($gambarItem)) <img src="{{ asset('storage/' . $gambarItem) }}" class="img-fluid rounded" style="max-height: 45px;">
                                @else <i class="bi bi-flower2" style="font-size: 1.8rem; color: #e9897d;"></i> @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $namaItem }} (x{{ $qty }})</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.75rem;">{{ $customKet }}</p>
                                <span class="fw-bold" style="color: #1e3d2f; font-size: 0.9rem;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                    <span id="subtotalProduk" data-subtotal="{{ $totalKeseluruhan }}" class="d-none"></span>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;"><span class="text-muted">Subtotal Produk</span><span class="fw-semibold">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span></div>
                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;"><span class="text-muted">Ongkos Kirim</span><span class="fw-semibold text-success" id="nominalOngkir">Gratis</span></div>
                        <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;"><span class="text-muted">Biaya Layanan & Kartu Ucapan</span><span class="fw-semibold text-success">Gratis</span></div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Total Tagihan</h5>
                            <h4 class="fw-bold mb-0" style="color: #1e3d2f;"><span id="totalTagihan">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</span></h4>
                        </div>
                    </div>
                @else
                    <span id="subtotalProduk" data-subtotal="0" class="d-none"></span>
                    <div class="text-center py-4">
                        <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Belum ada item pesanan.</p>
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
        const checkoutForm = document.getElementById('checkoutForm');
        const btnBukaKonfirmasi = document.getElementById('btnBukaKonfirmasi');
        const submitCheckoutBtn = document.getElementById('submitCheckout');
        const modalElement = document.getElementById('konfirmasiCheckout');
        const bsModal = new bootstrap.Modal(modalElement);

        // Ambil Total dari PHP
        const total = Number(document.getElementById('subtotalProduk').dataset.subtotal);
        document.getElementById('modalTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);

        // Buka Modal Konfirmasi
        btnBukaKonfirmasi.addEventListener('click', function () {
            if (!checkoutForm.checkValidity()) {
                checkoutForm.reportValidity();
                return;
            }
            bsModal.show();
        });

        // Tombol "Ya, Proses Pesanan" di dalam Modal
        submitCheckoutBtn.addEventListener('click', async function () {
            submitCheckoutBtn.disabled = true;
            submitCheckoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghubungi Midtrans...';

            const formData = new FormData(checkoutForm);

            try {
                const response = await fetch("{{ route('checkout.process') }}", {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan sistem.');
                }

                bsModal.hide();
                submitCheckoutBtn.disabled = false;
                submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';

                // COD
                if (data.is_cod) {
                    window.location.href = data.redirect;
                    return;
                }

                // Midtrans Snap
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function () { window.location.href = data.redirect; },
                        onPending: function () { alert('Menunggu pembayaran. Silakan selesaikan.'); window.location.href = "{{ route('pesanan.index') }}"; },
                        onError: function () { alert('Pembayaran gagal!'); submitCheckoutBtn.disabled = false; submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan'; },
                        onClose: function () { alert('Pembayaran belum selesai.'); window.location.href = "{{ route('pesanan.index') }}"; }
                    });
                } else {
                    window.location.href = data.redirect;
                }
            } catch (error) {
                alert('Gagal: ' + error.message);
                bsModal.hide();
                submitCheckoutBtn.disabled = false;
                submitCheckoutBtn.innerHTML = 'Ya, Proses Pesanan';
            }
        });
    });
</script>
@endsection
