@extends('layouts.admin')
@section('title', 'Kelola Kategori')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-tags"></i> Kelola Kategori</h2>
            <p class="page-subtitle">Total: {{ $categories->count() }} kategori</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-accent"><i class="bi bi-plus-circle"></i> Tambah Kategori</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Kategori</th>
                            <th width="180">Jumlah Bucket</th>
                            <th width="220">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><span class="category-pill"><i class="bi bi-flower2"></i>
                                        {{ $category->products_count }} bucket</span></td>
                                <td>
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm"><i
                                            class="bi bi-pencil"></i> Edit</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bi bi-tags"></i>
                                        <h4>Belum Ada Kategori</h4>
                                        <p>Mulai tambahkan kategori pertama Anda!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
