
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Buket House')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff9f5;
            color: #1e3d2f;
            margin: 0;
        }

        /* =========================
           NAVBAR BUKET HOUSE
        ========================= */

        .navbar-custom {
            background: #1e3d2f;
            padding: 10px 0;
            min-height: 62px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        }

        .navbar-custom .container {
            display: flex;
            align-items: center;
        }

        /* Logo */
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-right: auto;
            padding: 0;
            white-space: nowrap;
        }

        .navbar-brand i {
            color: #ef8987;
            font-size: 23px;
        }

        /* Menu navigasi */
        .navbar-nav {
            align-items: center;
            gap: 5px;
        }

        .navbar-custom .nav-link {
            color: #fff !important;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 10px !important;
            margin: 0;
            white-space: nowrap;
            transition: 0.2s;
        }

        .navbar-custom .nav-link:hover {
            color: #ef8987 !important;
        }

        .navbar-custom .nav-link i {
            font-size: 14px;
            margin-right: 3px;
        }

        /* Bagian kanan navbar */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 17px;
            margin-left: 15px;
        }

        /* Link keranjang dan pesanan */
        .navbar-icon {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: 0.2s;
        }

        .navbar-icon i {
            font-size: 16px;
        }

        .navbar-icon:hover {
            color: #ef8987;
        }

        /* Badge jumlah */
        .navbar-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            border-radius: 50px;
            background: #ef5350;
            color: white;
            font-size: 10px;
            font-weight: 600;
            line-height: 1;
        }

        /* Tombol akun */
        .account-button {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 6px 11px;
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 7px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .account-button:hover,
        .account-button:focus {
            color: #ef8987;
            border-color: #ef8987;
        }

        .account-button i {
            font-size: 14px;
        }

        /* Dropdown akun */
        .account-menu {
            width: 220px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            padding: 9px;
            margin-top: 10px !important;
        }

        .account-info {
            padding: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 5px;
        }

        .account-info strong {
            color: #1e3d2f;
            font-size: 13px;
        }

        .account-info small {
            font-size: 11px;
        }

        .account-menu .dropdown-item {
            font-size: 13px;
            padding: 9px 10px;
            border-radius: 6px;
        }

        /* Tombol masuk */
        .btn-masuk {
            background: #fce5e2;
            color: #1e3d2f;
            border-radius: 7px;
            padding: 7px 13px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-masuk:hover {
            background: #ef8987;
            color: white;
        }

        /* Tombol menu HP */
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 5px 9px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            width: 21px;
            height: 21px;
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            background: #1e3d2f;
            color: white;
            padding: 30px 0 15px;
            margin-top: 50px;
        }

        footer p {
            color: #ddd;
            font-size: 12px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 991px) {

            .navbar-custom {
                padding: 10px 0;
            }

            .navbar-custom .container {
                flex-wrap: wrap;
            }

            .navbar-collapse {
                width: 100%;
                padding-top: 12px;
            }

            .navbar-nav {
                align-items: flex-start;
                gap: 0;
            }

            .navbar-custom .nav-link {
                padding: 9px 0 !important;
            }

            .navbar-actions {
                margin-left: 0;
                padding: 12px 0 5px;
                gap: 20px;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 400px) {

            .navbar-brand {
                font-size: 19px;
            }

            .navbar-actions {
                gap: 14px;
            }

            .navbar-icon {
                font-size: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-flower1"></i>
                <span>Buket House</span>
            </a>

            <!-- TOMBOL RESPONSIVE -->
            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">

                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- MENU UTAMA -->
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="bi bi-house-door"></i>
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('home') }}#katalog" class="nav-link">
                            <i class="bi bi-bag"></i>
                            Produk
                        </a>
                    </li>

                </ul>

                <!-- MENU KANAN -->
                <div class="navbar-actions">

                    @auth

                        @php
                            // Menghitung jumlah produk di keranjang
                            $jumlahKeranjang = \App\Models\Cart::where('user_id', auth()->id())
                                ->sum('quantity');

                            // Menghitung pesanan yang belum selesai
                            $jumlahPesanan = \App\Models\Order::where('user_id', auth()->id())
                                ->where(function ($query) {
                                    $query->whereNull('status')
                                          ->orWhere('status', '!=', 'Selesai');
                                })
                                ->count();
                        @endphp

                        <!-- KERANJANG -->
                        <a href="{{ route('cart.index') }}" class="navbar-icon">

                            <i class="bi bi-cart3"></i>

                            <span>Keranjang</span>

                            @if($jumlahKeranjang > 0)
                                <span class="navbar-badge">
                                    {{ $jumlahKeranjang > 99 ? '99+' : $jumlahKeranjang }}
                                </span>
                            @endif

                        </a>

                        <!-- PESANAN SAYA -->
                        <a href="{{ route('pesanan.index') }}" class="navbar-icon">

                            <i class="bi bi-clipboard-check"></i>

                            <span>Pesanan Saya</span>

                            @if($jumlahPesanan > 0)
                                <span class="navbar-badge">
                                    {{ $jumlahPesanan > 99 ? '99+' : $jumlahPesanan }}
                                </span>
                            @endif

                        </a>

                        <!-- DROPDOWN AKUN -->
                        <div class="dropdown">

                            <button class="account-button dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                <i class="bi bi-person-fill"></i>

                                <span>{{ auth()->user()->name }}</span>

                            </button>

                            <div class="dropdown-menu dropdown-menu-end account-menu">

                                <div class="account-info">

                                    <strong>
                                        {{ auth()->user()->name }}
                                    </strong>

                                    <small class="d-block text-muted">
                                        {{ auth()->user()->email }}
                                    </small>

                                </div>

                                <form action="{{ route('logout') }}" method="POST">

                                    @csrf

                                    <button type="submit" class="dropdown-item text-danger">

                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Keluar

                                    </button>

                                </form>

                            </div>

                        </div>

                    @else

                        <!-- JIKA BELUM LOGIN -->
                        <a href="{{ route('login') }}" class="btn-masuk">

                            <i class="bi bi-person"></i>
                            Masuk

                        </a>

                    @endauth

                </div>

            </div>

        </div>
    </nav>

    <!-- ISI HALAMAN -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer>

        <div class="container">

            <div class="row">

                <div class="col-md-6">

                    <h4 style="font-family:'Playfair Display'">

                        <i class="bi bi-flower1" style="color:#ef8987"></i>

                        Buket House

                    </h4>

                    <p>
                        Rangkaian buket penuh makna untuk setiap momen istimewa Anda.
                    </p>

                </div>

                <div class="col-md-6 text-md-end">

                    <p>
                        Buket Bunga | Buket Uang | Buket Snack | Buket Boneka
                    </p>

                    <p>
                        &copy; {{ date('Y') }} Buket House. All Rights Reserved.
                    </p>

                </div>

            </div>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
