@extends('layouts.app')

@section('title', 'Login - Buket House')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5 pt-5 mb-5">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">

                <!-- Header Kotak Hijau -->
                <div class="card-header text-center py-3" style="background-color: #1e3d2f; border-radius: 5px 5px 0 0;">
                    <h4 class="mb-0 text-white" style="font-family: 'Playfair Display', serif;">
                        <i class="bi bi-flower1"></i> Masuk ke Buket House
                    </h4>
                </div>

                <!-- Body Form -->
                <div class="card-body p-4">

                    <!-- Tampilkan pesan sukses/error jika ada -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Input Email / No HP -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email / No HP</label>
                            <input type="text" name="login" class="form-control" placeholder="Masukkan email atau nomor handphone" required autofocus>
                        </div>

                        <!-- Input Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>

                        <!-- Tombol Masuk -->
                        <button type="submit" class="btn w-100 text-white mt-2" style="background-color: #1e3d2f;">
                            Masuk
                        </button>
                    </form>

                    <!-- Link Daftar -->
                    <p class="text-center mt-4 mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
