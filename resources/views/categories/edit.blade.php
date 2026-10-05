@extends('layouts.app')

@section('content')
<div class="surface col-md-6 mx-auto">
    <div class="card-header">
        <h4><i class="bi bi-pencil"></i> Edit Kategori Bucket</h4>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required autofocus>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
