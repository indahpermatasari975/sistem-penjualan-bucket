<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bucket House | Katalog Bucket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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

        body {
            min-height: 100vh;
            color: var(--ink);
            background-color: var(--paper);
            background-image: radial-gradient(#d8cfc0 0.65px, transparent 0.65px);
            background-size: 18px 18px;
            font-family: 'DM Sans', sans-serif;
        }

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
        .page-shell { max-width: 1180px; padding-bottom: 3rem; }
        h1, h2, h3, h4, .card-title { color: var(--ink); font-family: 'Playfair Display', serif; }
        h1 { font-size: 3.25rem; }
        .text-muted { color: var(--muted) !important; }

        .btn { border-radius: 4px; font-weight: 600; padding: .62rem 1rem; }
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
        .btn-warning { background: #efb184; border-color: #efb184; }
        .btn-outline-light:hover { color: var(--leaf-dark); }

        .catalog-intro {
            position: relative;
            overflow: hidden;
            margin: 1.75rem 0 2rem;
            padding: clamp(1.5rem, 5vw, 3.5rem);
            border: 1px solid #dfe6dc;
            border-radius: 8px;
            background: linear-gradient(115deg, #eef2e9 0%, #f8eee6 68%, #f8e5df 100%);
        }
        .catalog-intro::after {
            position: absolute;
            right: -30px;
            bottom: -90px;
            width: 240px;
            height: 240px;
            border: 1px solid #315d4926;
            border-radius: 50%;
            box-shadow: 0 0 0 22px #315d4908, 0 0 0 44px #315d4906;
            content: '';
        }
        .eyebrow { color: var(--leaf); font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .bucket-card, .surface {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fffefa;
            box-shadow: 0 8px 22px #293d2d0a;
        }
        .bucket-card { transition: transform .2s ease, box-shadow .2s ease; }
        .bucket-card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px #293d2d16; }
        .bucket-card .card-img-top, .bucket-placeholder { height: 230px; object-fit: cover; }
        .bucket-placeholder { color: #8c9b86; background: linear-gradient(145deg, #e7eee3, #f5e8df); }
        .bucket-card .card-body { padding: 1.2rem; }
        .bucket-card .card-title { font-size: 1.25rem; }
        .price { color: var(--leaf-dark); font-size: 1.12rem; font-weight: 700; }
        .category-pill { color: #315d49; background: #eaf0e7; font-weight: 600; }

        .surface .card-header { padding: 1.1rem 1.35rem; color: #fff; background: var(--leaf); border: 0; }
        .surface .card-header h4 { margin: 0; color: #fff; font-size: 1.25rem; }
        .form-control, .form-select { min-height: 44px; border-color: #dcded5; border-radius: 4px; }
        .form-control:focus, .form-select:focus { border-color: #78977d; box-shadow: 0 0 0 .2rem #315d491a; }
        .form-label { color: #34473b; font-weight: 600; }
        .table { --bs-table-bg: transparent; vertical-align: middle; }
        .table thead th { padding: .9rem .75rem; color: #fff; background: var(--leaf-dark); font-size: .8rem; font-weight: 600; }
        .table td { padding: .85rem .75rem; border-color: var(--line); }
        .table > :not(caption) > * > * { box-shadow: none; }
        .auth-wrap { max-width: 470px; margin: 2rem auto; }
        .auth-brand { color: var(--leaf); font-family: 'Playfair Display', serif; }

        @media (max-width: 575px) {
            .navbar .container { padding-right: 1rem; padding-left: 1rem; }
            .navbar .btn { padding: .45rem .65rem; }
            h1 { font-size: 2.35rem; }
            .bucket-card .card-img-top, .bucket-placeholder { height: 205px; }
            .catalog-intro::after { right: -145px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-flower1"></i> Bucket House
            </a>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a href="{{ route('home') }}" class="nav-link d-none d-md-inline">Katalog</a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('products.index') }}" class="nav-link px-1" aria-label="Produk" title="Produk">
                            <i class="bi bi-flower2"></i><span class="d-none d-lg-inline ms-1">Produk</span>
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-link px-1" aria-label="Kategori" title="Kategori">
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
                        <button class="btn btn-outline-light btn-sm" aria-label="Keluar" title="Keluar">
                            <i class="bi bi-box-arrow-right"></i><span class="d-none d-sm-inline ms-1">Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container page-shell">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
