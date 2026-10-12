<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buket House | Katalog Buket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #263c32;
            --leaf: #315d49;
            --leaf-dark: #234636;
            --coral: #e9897d;
            --paper: #fbf8f2;
            --line: #e8e2d8;
            --muted: #788078;
        }

        * { font-family: 'DM Sans', sans-serif; }

        body {
            min-height: 100vh;
            color: var(--ink);
            background-color: var(--paper);
            background-image: radial-gradient(#d8cfc0 0.65px, transparent 0.65px);
            background-size: 18px 18px;
        }

        h1, h2, h3, h4, .card-title, .brand-text {
            font-family: 'Playfair Display', serif;
            color: var(--ink);
        }

        /* ============ NAVBAR ============ */
        .navbar {
            min-height: 76px;
            background: var(--leaf-dark) !important;
            box-shadow: 0 8px 24px #203d2c20;
        }
        .navbar-brand {
            color: #fff !important;
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
        }
        .navbar-brand i { color: #f0a49a; }
        .nav-link { color: #e8eee8 !important; font-size: .9rem; }
        .nav-link:hover { color: #f2b6a9 !important; }

        /* ============ LAYOUT ============ */
        .page-shell { max-width: 1180px; padding-bottom: 3rem; }

        /* ============ BUTTONS ============ */
        .btn-primary, .btn-success {
            color: #fff;
            background: var(--leaf);
            border-color: var(--leaf);
        }
        .btn-primary:hover, .btn-success:hover {
            color: #fff;
            background: var(--leaf-dark);
            border-color: var(--leaf-dark);
        }
        .btn-warning { background: #efb184; border-color: #efb184; color: #5b3a1e; }
        .btn-warning:hover { background: #e5a06d; border-color: #e5a06d; color: #fff; }
        .btn-outline-light:hover { color: var(--leaf-dark); }

        /* ============ CARDS & SURFACE ============ */
        .bucket-card, .surface {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fffefa;
            box-shadow: 0 8px 22px #293d2d0a;
        }
        .bucket-card { transition: transform .2s ease, box-shadow .2s ease; }
        .bucket-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px #293d2d16;
        }
        .bucket-card .card-img-top, .bucket-placeholder {
            height: 230px;
            object-fit: cover;
        }
        .bucket-placeholder {
            color: #8c9b86;
            background: linear-gradient(145deg, #e7eee3, #f5e8df);
        }
        .bucket-card .card-body { padding: 1.2rem; }
        .bucket-card .card-title { font-size: 1.25rem; }

        .surface .card-header {
            padding: 1.1rem 1.35rem;
            color: #fff;
            background: var(--leaf);
            border: 0;
        }
        .surface .card-header h4 { margin: 0; color: #fff; font-size: 1.25rem; }

        /* ============ PRICE & BADGES ============ */
        .price { color: var(--leaf-dark); font-size: 1.12rem; font-weight: 700; }
        .category-pill { color: #315d49; background: #eaf0e7; font-weight: 600; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; }

        /* ============ FORMS ============ */
        .form-control, .form-select {
            min-height: 44px;
            border-color: #dcded5;
            border-radius: 6px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #78977d;
            box-shadow: 0 0 0 .2rem #315d491a;
        }
        .form-label { color: #34473b; font-weight: 600; font-size: 0.9rem; }

        /* ============ TABLE ============ */
        .table { --bs-table-bg: transparent; vertical-align: middle; }
        .table thead th {
            padding: .9rem .75rem;
            color: #fff;
            background: var(--leaf-dark);
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table tbody td { padding: .85rem .75rem; border-color: var(--line); }
        .table > :not(caption) > * > * { box-shadow: none; }
        .table tbody tr:hover { background: #fbf8f2; }

        /* ============ STATISTIC CARDS ============ */
        .stat-card {
            padding: 1rem 1.2rem;
            border-radius: 8px;
            background: #fffefa;
            border: 1px solid var(--line);
            height: 100%;
            transition: all 0.2s;
        }
        .stat-card:hover { box-shadow: 0 8px 22px #293d2d0a; }
        .stat-card.total { border-left: 4px solid var(--leaf); }
        .stat-card.low   { border-left: 4px solid #efb184; }
        .stat-card.out   { border-left: 4px solid var(--coral); }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        .stat-value { font-size: 1.75rem; font-weight: 700; margin: 0; }
        .stat-card.total .stat-value { color: var(--leaf-dark); }
        .stat-card.low .stat-value   { color: #b87044; }
        .stat-card.out .stat-value   { color: #c45a6e; }

        /* ============ STOCK BADGE ============ */
        .stock-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 2;
        }
        .stock-badge.high { background: #d1e7dd; color: #0a3622; }
        .stock-badge.low  { background: #fff3cd; color: #664d03; }
        .stock-badge.out  { background: #f8d7da; color: #58151c; }

        /* ============ FILTER BAR ============ */
        .filter-bar {
            background: #fffefa;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
        }
        .filter-bar .form-label {
            font-size: 0.75rem;
            margin-bottom: 4px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============ PRODUCT ACTIONS ============ */
        .product-actions {
            display: flex;
            gap: 4px;
            padding: 8px 12px;
            border-top: 1px solid var(--line);
        }
        .product-actions .btn {
            border-radius: 6px;
            font-size: 0.85rem;
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-state i {
            font-size: 4rem;
            color: var(--line);
            display: block;
            margin-bottom: 1rem;
        }
        .empty-state h4 {
            font-family: 'Playfair Display', serif;
            color: var(--ink);
        }

        /* ============ IMAGE UPLOAD ============ */
        .upload-zone {
            border: 2px dashed var(--line);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            background: var(--paper);
            transition: all 0.3s;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .upload-zone:hover {
            border-color: var(--leaf);
            background: #eaf0e7;
        }
        .upload-zone.dragover {
            border-color: var(--leaf);
            background: #eaf0e7;
        }
        .upload-zone i.upload-icon {
            font-size: 3rem;
            color: var(--leaf);
        }
        .upload-zone p { margin: 0.75rem 0 0.25rem; font-weight: 600; }

        /* ============ TOAST ============ */
        .toast-container-custom {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 9999;
        }
        .toast-custom {
            min-width: 320px;
            padding: 1rem 1.2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 30px #293d2d20;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            animation: slideInRight 0.4s ease;
        }
        .toast-custom.success { border-left: 4px solid var(--leaf); }
        .toast-custom.error   { border-left: 4px solid #dc3545; }
        .toast-custom i { font-size: 1.5rem; }
        .toast-custom.success i { color: var(--leaf); }
        .toast-custom.error i   { color: #dc3545; }

        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }

        /* ============ DETAIL PAGE ============ */
        .detail-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }
        .info-box {
            padding: 1rem;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 8px;
        }
        .info-box-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
        }
        .info-box-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--leaf-dark);
            margin: 4px 0 0;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 575px) {
            h1 { font-size: 2.35rem; }
            .bucket-card .card-img-top, .bucket-placeholder { height: 205px; }
            .toast-custom { min-width: 280px; }
        }
    </style>
</head>
<body>
    {{-- ============ NAVBAR ============ --}}
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-flower1"></i> Buket House
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <a href="{{ route('home') }}" class="nav-link d-none d-md-inline">Katalog</a>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('products.index') }}" class="nav-link px-1" title="Produk">
                                <i class="bi bi-flower2"></i><span class="d-none d-lg-inline ms-1">Produk</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-link px-1" title="Kategori">
                                <i class="bi bi-tags"></i><span class="d-none d-lg-inline ms-1">Kategori</span>
                            </a>
                        @endif
                        <span class="text-white small d-none d-lg-inline">
                            {{ Auth::user()->name }}
                            <span class="badge bg-light text-dark ms-1">
                                {{ Auth::user()->role === 'admin' ? 'Admin' : 'Pelanggan' }}
                            </span>
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right"></i><span class="d-none d-sm-inline ms-1">Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-light btn-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ============ TOAST NOTIFICATION ============ --}}
    <div class="toast-container-custom">
        @if(session('success'))
            <div class="toast-custom success" id="flashToast">
                <i class="bi bi-check-circle-fill"></i>
                <div>
                    <strong>Berhasil!</strong>
                    <div class="small text-muted">{{ session('success') }}</div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-custom error" id="flashToast">
                <i class="bi bi-x-circle-fill"></i>
                <div>
                    <strong>Gagal!</strong>
                    <div class="small text-muted">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    {{-- ============ CONTENT ============ --}}
    <main class="container page-shell">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide toast setelah 4 detik
        setTimeout(() => {
            document.querySelectorAll('.toast-custom').forEach(t => {
                t.style.transition = 'all 0.5s';
                t.style.opacity = '0';
                t.style.transform = 'translateX(120%)';
                setTimeout(() => t.remove(), 500);
            });
        }, 4000);
    </script>
</body>
</html>
