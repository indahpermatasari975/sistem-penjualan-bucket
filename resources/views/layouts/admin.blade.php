
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin') - Buket House</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #315d49;
            --primary-dark: #1e3d2f;
            --primary-light: #4a8069;
            --accent: #e9897d;
            --accent-soft: #f7d5e0;
            --cream: #faf6f0;
            --line: #e8e2d8;
            --muted: #788078;
            --ink: #263c32;
        }

        * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--cream);
            color: var(--ink);
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-dark);
        }

        /* =========================
           NAVBAR ATAS
        ========================= */

        .top-nav {
            width: 100%;
            min-height: 64px;
            padding: 10px 28px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 3px 12px rgba(0,0,0,0.10);
        }

        .top-nav .brand {
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 23px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-nav .brand i {
            color: var(--accent);
            font-size: 25px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 13px;
        }

        .badge-role {
            padding: 5px 13px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--accent), #d4849c);
            color: white;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .btn-logout {
            background: rgba(255,255,255,0.13);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12px;
            transition: .3s;
        }

        .btn-logout:hover {
            background: var(--accent);
            color: white;
        }

        /* =========================
           LAYOUT UTAMA
        ========================= */

        .wrapper {
            display: flex;
            align-items: stretch;
            min-height: calc(100vh - 64px);
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 250px;
            min-width: 250px;
            background: linear-gradient(180deg, var(--primary-dark), var(--primary));
            padding: 22px 0;
            min-height: calc(100vh - 64px);
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.3) transparent;
        }

        .sidebar-brand {
            text-align: center;
            padding: 5px 20px 22px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 15px;
        }

        .sidebar-brand i {
            font-size: 2.5rem;
            color: var(--accent);
            display: block;
            margin-bottom: 8px;
        }

        .sidebar-brand h5 {
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            margin: 0;
            letter-spacing: 1px;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.55);
            font-size: 11px;
        }

        .sidebar-section {
            color: rgba(255,255,255,0.45);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            padding: 18px 26px 8px;
            font-weight: 700;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 4px 12px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 15px;
            color: rgba(255,255,255,0.76);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: .25s;
        }

        .sidebar-menu a i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.10);
            color: white;
            transform: translateX(3px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, var(--accent), #d4849c);
            color: white;
            box-shadow: 0 5px 14px rgba(232,164,184,0.28);
        }

        .sidebar-menu a .coming-soon {
            margin-left: auto;
            font-size: 10px;
            background: rgba(255,255,255,0.15);
            padding: 4px 9px;
            border-radius: 20px;
        }

        .sidebar-menu a .badge-count {
            margin-left: auto;
            background: var(--accent);
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        /* =========================
           AREA KONTEN
        ========================= */

        .content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            color: var(--accent);
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 0;
        }

        /* =========================
           CARD
        ========================= */

        .card {
            background: white;
            border: 1px solid var(--line);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(45,85,68,0.06);
            transition: .25s;
        }

        .card:hover {
            box-shadow: 0 7px 18px rgba(45,85,68,0.10);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--line);
            border-radius: 13px 13px 0 0 !important;
        }

        .card-body {
            padding: 20px;
        }

        /* =========================
           TOMBOL
        ========================= */

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: .3s;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #d4849c);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: .3s;
        }

        .btn-accent:hover {
            color: white;
            transform: translateY(-2px);
        }

        /* =========================
           KARTU STATISTIK
        ========================= */

        .stat-card {
            padding: 18px 20px;
            border-radius: 12px;
            background: white;
            border: 1px solid var(--line);
            height: 100%;
        }

        .stat-card.total {
            border-left: 4px solid var(--primary);
        }

        .stat-card.income {
            border-left: 4px solid var(--primary-light);
        }

        .stat-card.low {
            border-left: 4px solid #efb184;
        }

        .stat-card.out {
            border-left: 4px solid var(--accent);
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            font-weight: 700;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 700;
            margin: 7px 0 2px;
            color: var(--primary-dark);
        }

        /* =========================
           FILTER
        ========================= */

        .filter-bar {
            background: white;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 22px;
        }

        .filter-bar label {
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: block;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--line);
            padding: 10px 13px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(74,128,105,0.12);
        }

        /* =========================
           TABEL
        ========================= */

        .table-responsive {
            border-radius: 12px;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
            min-width: 850px;
        }

        .table thead th {
            background: #f0f4ef;
            color: var(--primary-dark);
            padding: 15px 13px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .4px;
            font-weight: 700;
            white-space: nowrap;
            border-bottom: 1px solid var(--line);
        }

        .table tbody td {
            padding: 16px 13px;
            font-size: 13px;
            color: var(--ink);
            border-color: #f1ede7;
        }

        .table tbody tr:hover {
            background: #fcfaf7;
        }

        .table tbody td small {
            font-size: 11px;
        }

        /* =========================
           PRODUK
        ========================= */

        .bucket-card {
            background: white;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            transition: .3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .bucket-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(45,85,68,0.12);
        }

        .bucket-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .bucket-placeholder {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 45px;
        }

        .stock-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .stock-badge.high {
            background: #d1e7dd;
            color: #0a3622;
        }

        .stock-badge.low {
            background: #fff3cd;
            color: #664d03;
        }

        .stock-badge.out {
            background: #f8d7da;
            color: #58151c;
        }

        .category-pill {
            background: #eaf0e7;
            color: var(--primary);
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 11px;
            display: inline-block;
        }

        .product-actions {
            padding: 10px 12px;
            border-top: 1px solid var(--line);
            display: flex;
            gap: 6px;
            margin-top: auto;
        }

        /* =========================
           NOTIFIKASI
        ========================= */

        .toast-container-custom {
            position: fixed;
            top: 85px;
            right: 22px;
            z-index: 9999;
        }

        .toast-custom {
            min-width: 300px;
            max-width: 380px;
            padding: 15px 18px;
            background: white;
            border-radius: 11px;
            box-shadow: 0 10px 30px rgba(45,85,68,0.15);
            border-left: 4px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            animation: slideIn .4s ease;
        }

        .toast-custom.success {
            border-left-color: #28a745;
        }

        .toast-custom.error {
            border-left-color: #dc3545;
        }

        .toast-custom i {
            font-size: 23px;
        }

        .toast-custom.success i {
            color: #28a745;
        }

        .toast-custom.error i {
            color: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 55px;
            color: var(--accent-soft);
            display: block;
            margin-bottom: 15px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 991px) {

            .sidebar {
                width: 220px;
                min-width: 220px;
            }

            .content {
                padding: 22px 18px;
            }

            .page-title {
                font-size: 24px;
            }
        }

        @media (max-width: 767px) {

            .top-nav {
                padding: 12px 16px;
            }

            .top-nav .brand {
                font-size: 18px;
            }

            .user-info {
                gap: 7px;
            }

            .badge-role {
                display: none;
            }

            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                top: 0;
                width: 100%;
                min-width: 100%;
                height: auto;
                min-height: auto;
                padding: 12px 0;
                overflow: visible;
            }

            .sidebar-brand {
                display: none;
            }

            .sidebar-section {
                padding: 12px 18px 5px;
            }

            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                padding: 0 8px;
            }

            .sidebar-menu li {
                margin: 3px;
            }

            .sidebar-menu a {
                padding: 9px 12px;
                font-size: 12px;
            }

            .sidebar-menu a i {
                font-size: 15px;
            }

            .content {
                padding: 20px 14px;
            }

            .page-title {
                font-size: 22px;
            }

            .page-subtitle {
                font-size: 12px;
            }
        }

        /* =========================
           CETAK INVOICE
        ========================= */

        @media print {

            body {
                background: white !important;
            }

            .top-nav,
            .sidebar,
            .no-print,
            .toast-container-custom {
                display: none !important;
            }

            .wrapper {
                display: block !important;
                min-height: auto;
            }

            .content {
                padding: 0 !important;
                width: 100% !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }

            .card-header {
                background: #f5f5f5 !important;
            }

            .table {
                width: 100% !important;
            }
        }

    </style>

    @stack('styles')
</head>

<body>

    <!-- =========================
         NAVBAR ATAS
    ========================= -->

    <nav class="top-nav">

        <a href="{{ route('products.index') }}" class="brand">
            <i class="bi bi-flower1"></i>
            Buket House Admin
        </a>

        <div class="user-info">

            <div class="d-none d-md-block text-end">
                <div>
                    Halo, {{ Auth::user()->name ?? 'Admin' }}
                </div>
            </div>

            <span class="badge-role">
                {{ Auth::user()->role ?? 'Admin' }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>

        </div>

    </nav>


    <!-- =========================
         SIDEBAR DAN KONTEN
    ========================= -->

    <div class="wrapper">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-brand">
                <i class="bi bi-flower1"></i>

                <h5>BUKET HOUSE</h5>

                <small>Admin Panel</small>
            </div>


            <!-- MENU TRANSAKSI -->
            <div class="sidebar-section">
                Transaksi
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('admin.orders.index') }}"
                       class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

                        <i class="bi bi-cart3"></i>
                        Pesanan

                        @php
                            $jumlahPesanan = \App\Models\Order::where('order_status', 'Menunggu')->count();
                        @endphp

                        @if($jumlahPesanan > 0)
                            <span class="badge-count">
                                {{ $jumlahPesanan > 99 ? '99+' : $jumlahPesanan }}
                            </span>
                        @endif

                    </a>
                </li>

            </ul>


            <!-- MENU PELANGGAN -->
            <div class="sidebar-section">
                Pelanggan
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                        <i class="bi bi-people"></i>
                        Daftar User

                    </a>
                </li>

                {{-- <li>
                    <a href="#" class="text-muted">
                        <i class="bi bi-star"></i>
                        Ulasan

                        <span class="badge-count">3</span>
                    </a>
                </li> --}}

            </ul>


            <!-- MENU PRODUK -->
            <div class="sidebar-section">
                Produk
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('products.index') }}"
                       class="{{ request()->routeIs('products.*') ? 'active' : '' }}">

                        <i class="bi bi-box-seam"></i>
                        Kelola Produk

                    </a>
                </li>

                <li>
                    <a href="{{ route('categories.index') }}"
                       class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">

                        <i class="bi bi-tags"></i>
                        Kelola Kategori

                    </a>
                </li>

            </ul>


            <!-- MENU LAINNYA -->
            <div class="sidebar-section">
                Lainnya
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-globe"></i>
                        Lihat Toko
                    </a>
                </li>

            </ul>

        </aside>


        <!-- KONTEN HALAMAN -->
        <main class="content">

            @yield('content')

        </main>

    </div>


    <!-- =========================
         NOTIFIKASI
    ========================= -->

    <div class="toast-container-custom">

        @if(session('success'))

            <div class="toast-custom success">

                <i class="bi bi-check-circle-fill"></i>

                <div>
                    <strong>Berhasil!</strong>

                    <div style="font-size: 13px; color: #666;">
                        {{ session('success') }}
                    </div>
                </div>

            </div>

        @endif


        @if(session('error'))

            <div class="toast-custom error">

                <i class="bi bi-x-circle-fill"></i>

                <div>
                    <strong>Gagal!</strong>

                    <div style="font-size: 13px; color: #666;">
                        {{ session('error') }}
                    </div>
                </div>

            </div>

        @endif

    </div>


    <!-- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Menghilangkan notifikasi setelah 4 detik
        setTimeout(function () {

            document.querySelectorAll('.toast-custom').forEach(function (toast) {

                toast.style.transition = 'all 0.4s';
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';

                setTimeout(function () {
                    toast.remove();
                }, 400);

            });

        }, 4000);
    </script>

    @stack('scripts')

</body>
</html>
