@extends('layouts.admin')
@section('title', 'Tambah Kategori')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-plus-circle"></i> Tambah Kategori</h2>
            <p class="page-subtitle">Buat kategori baru untuk bucket</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card col-lg-6">
        <div class="card-body p-4">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Bucket Bunga" required
                        autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-accent"><i class="bi bi-save"></i> Simpan</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary"
                        style="border-radius: 10px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
