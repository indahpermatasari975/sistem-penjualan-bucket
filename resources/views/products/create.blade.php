@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-plus-circle"></i> Tambah Bucket Baru</h2>
            <p class="page-subtitle">Tambahkan produk bucket baru ke toko</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Bucket <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Bucket Mawar Pink Premium" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" rows="4" class="form-control" placeholder="Jelaskan detail bucket...">{{ old('description') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" value="{{ old('price') }}"
                                    class="form-control @error('price') is-invalid @enderror" placeholder="150000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Tersedia <span class="text-danger">*</span></label>
                                <input type="number" name="stock" value="{{ old('stock') }}"
                                    class="form-control @error('stock') is-invalid @enderror" placeholder="10" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn-accent"><i class="bi bi-save"></i> Simpan Bucket</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary"
                                style="border-radius: 10px;">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><i class="bi bi-image"></i> Foto Bucket</div>
                <div class="card-body">
                    <label for="imageInput" class="upload-zone" id="dropZone">
                        <div id="previewContainer" style="display: none; width: 100%;">
                            <img id="imagePreview" class="img-fluid rounded" style="max-height: 280px;">
                        </div>
                        <div id="uploadPrompt">
                            <i class="bi bi-cloud-arrow-up upload-icon"></i>
                            <p style="margin: 12px 0 4px; font-weight: 600;">Klik atau drop gambar</p>
                            <small class="text-muted">JPG, PNG, WEBP • Maks 2MB</small>
                        </div>
                    </label>
                    <input type="file" name="image" id="imageInput" form="productForm" style="display: none;" accept="image/*"
                        onchange="previewImage(event)">
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    <div id="fileName" class="text-center small text-muted mt-2"></div>
                    <button type="button" id="removeBtn" class="btn btn-sm btn-outline-danger w-100 mt-2"
                        style="display: none;" onclick="removeImage()">
                        <i class="bi bi-x-circle"></i> Hapus Foto
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                    document.getElementById('uploadPrompt').style.display = 'none';
                    document.getElementById('fileName').textContent = '📎 ' + file.name;
                    document.getElementById('removeBtn').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }

            function removeImage() {
                document.getElementById('imageInput').value = '';
                document.getElementById('previewContainer').style.display = 'none';
                document.getElementById('uploadPrompt').style.display = 'block';
                document.getElementById('fileName').textContent = '';
                document.getElementById('removeBtn').style.display = 'none';
            }
            const dz = document.getElementById('dropZone');
            dz.addEventListener('dragover', e => {
                e.preventDefault();
                dz.classList.add('dragover');
            });
            dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
            dz.addEventListener('drop', e => {
                e.preventDefault();
                dz.classList.remove('dragover');
                const file = e.dataTransfer.files[0];
                if (file) {
                    document.getElementById('imageInput').files = e.dataTransfer.files;
                    previewImage({
                        target: {
                            files: [file]
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
