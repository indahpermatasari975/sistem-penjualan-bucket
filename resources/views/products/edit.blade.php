@extends('layouts.app')

@section('content')
<div class="surface col-md-8 mx-auto">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Edit Bucket</h4>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Bucket <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                           class="form-control @error('price') is-invalid @enderror" required>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jumlah Tersedia <span class="text-danger">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                           class="form-control @error('stock') is-invalid @enderror" required>
                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Saat Ini</label><br>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" width="150" class="img-thumbnail mb-2">
                @else
                    <p class="text-muted">Tidak ada gambar</p>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                       accept="image/*" onchange="previewImage(event)">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Biarkan kosong jika foto tidak ingin diubah.</small>
                <img id="preview" class="mt-2 img-thumbnail d-none" width="150">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.classList.remove('d-none');
    }
</script>
@endsection
