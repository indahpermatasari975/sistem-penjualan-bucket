<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Buket House')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #faf6f0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        /* Navbar Hijau */
        .navbar-custom {
            background-color: #1e3d2f;
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
            font-size: 22px;
        }
        .navbar-brand i { color: #e9897d; }
        .nav-link { color: white !important; margin: 0 10px; }
        .nav-link:hover { color: #e9897d !important; }

        .btn-masuk { color: white; border: 1px solid white; border-radius: 5px; padding: 6px 18px; text-decoration: none; font-size: 14px; }
        .btn-masuk:hover { background: white; color: #1e3d2f; }

        /* Footer */
        .footer { background: #1e3d2f; color: white; padding: 30px 0; margin-top: auto; text-align: center; }
    </style>
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-flower1"></i> Buket House
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Menu Wajib -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#katalog">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('custom.index') }}">Rakit Buket</a></li>

                    @auth
                        {{-- TAMPILAN JIKA SUDAH LOGIN --}}
                        @php
                            // Ambil jumlah item keranjang langsung dari database (Model Cart) milik user yang login
                            $jumlahKeranjang = (int) \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                            $jumlahPesananDiproses = \App\Models\Order::where('user_id', Auth::id())
                                ->where('status', 'diproses')
                                ->count();
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3"></i> Keranjang
                                <span class="badge rounded-pill bg-danger">{{ $jumlahKeranjang }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pesanan.index') }}">
                                <i class="bi bi-bag-check"></i> Pesanan Saya
                                <span class="badge rounded-pill bg-danger">{{ $jumlahPesananDiproses }}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown ms-3">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profil Saya</a></li>

                                {{-- Kalau yang login Admin, munculin menu Dashboard --}}
                                @if(Auth::user()->role == 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-success fw-bold" href="{{ route('products.index') }}">Dashboard Admin</a></li>
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        {{-- TAMPILAN JIKA BELUM LOGIN (PUBLIC) --}}
                        <li class="nav-item ms-3">
                            <a href="{{ route('login') }}" class="btn-masuk"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ISI KONTEN -->
    <div style="flex: 1;">
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <h5>Buket House</h5>
            <p style="font-size: 14px; margin: 0;">&copy; 2026 - Indah Permata Sari</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
