@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-pencil"></i> Edit Bucket</h2>
        <p class="page-subtitle">Perbarui informasi bucket</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <form id="productForm" action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Bucket <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Tersedia <span class="text-danger">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn-primary-custom"><i class="bi bi-save"></i> Simpan Perubahan</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-image"></i> Foto Saat Ini</div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" style="max-height: 220px;">
                @else
                    <div class="empty-state" style="padding: 30px;"><i class="bi bi-image" style="font-size: 3rem;"></i><p class="mb-0">Belum ada foto</p></div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-repeat"></i> Ganti Foto</div>
            <div class="card-body">
                <label for="imageInput" class="upload-zone" id="dropZone" style="min-height: 180px; padding: 20px;">
                    <div id="previewContainer" style="display: none;">
                        <img id="imagePreview" class="img-fluid rounded" style="max-height: 180px;">
                    </div>
                    <div id="uploadPrompt">
                        <i class="bi bi-cloud-arrow-up upload-icon" style="font-size: 2rem;"></i>
                        <p style="font-size: 0.85rem; margin: 8px 0 4px;">Klik atau drop untuk ganti</p>
                        <small class="text-muted" style="font-size: 0.7rem;">Biarkan kosong jika tidak ingin ganti</small>
                    </div>
                </label>
                <input type="file" name="image" id="imageInput" form="productForm" style="display: none;" accept="image/*" onchange="previewImage(event)">
                <button type="button" id="removeBtn" class="btn btn-sm btn-outline-danger w-100 mt-2" style="display: none;" onclick="removeImage()">
                    <i class="bi bi-x-circle"></i> Batal Ganti Foto
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
        document.getElementById('removeBtn').style.display = 'block';
    };
    reader.readAsDataURL(file);
}
function removeImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('previewContainer').style.display = 'none';
    document.getElementById('uploadPrompt').style.display = 'block';
    document.getElementById('removeBtn').style.display = 'none';
}
</script>
@endpush
@endsection
