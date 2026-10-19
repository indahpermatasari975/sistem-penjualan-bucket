@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container text-center mt-5 mb-5" style="padding: 100px 0;">
    <i class="bi bi-cart-x text-muted" style="font-size: 80px;"></i>
    <h3 class="mt-3 fw-bold">Keranjang Masih Kosong</h3>
    <p class="text-muted">Fitur ini akan dibuat pada Minggu ke-5.</p>
    <a href="{{ route('home') }}" class="btn btn-success mt-3">Kembali Belanja</a>
</div>
@endsection
