<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Bucket House</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
        * { font-family: 'DM Sans', sans-serif; }
        body { background: var(--cream); min-height: 100vh; display: flex; flex-direction: column; color: var(--ink); margin: 0; }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; color: var(--primary-dark); }

        /* TOP NAV */
        .top-nav { background: linear-gradient(135deg, var(--primary-dark), var(--primary)); padding: 14px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; display: flex; justify-content: space-between; align-items: center; }
        .top-nav .brand { color: white; font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .top-nav .brand i { color: var(--accent); font-size: 1.6rem; }
        .top-nav .user-info { color: white; font-size: 0.9rem; display: flex; align-items: center; gap: 12px; }
        .badge-role { padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; background: linear-gradient(135deg, var(--accent), #d4849c); color: white; }
        .btn-logout { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 6px 14px; border-radius: 8px; font-size: 0.85rem; transition: all 0.3s; }
        .btn-logout:hover { background: rgba(255,255,255,0.25); color: white; border-color: var(--accent); }

        /* WRAPPER */
        .wrapper { display: flex; flex: 1; }

        /* SIDEBAR */
        .sidebar { width: 260px; background: linear-gradient(180deg, var(--primary-dark), var(--primary)); padding: 24px 0; min-height: calc(100vh - 68px); position: sticky; top: 68px; height: calc(100vh - 68px); overflow-y: auto; flex-shrink: 0; }
        .sidebar-brand { text-align: center; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 16px; }
        .sidebar-brand i { font-size: 2.5rem; color: var(--accent); display: block; margin-bottom: 8px; }
        .sidebar-brand h5 { color: white; font-family: 'Playfair Display', serif; font-size: 1.1rem; margin: 0; letter-spacing: 1px; }
        .sidebar-brand small { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
        .sidebar-section { color: rgba(255,255,255,0.4); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; padding: 16px 28px 8px; font-weight: 600; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin: 4px 12px; }
        .sidebar-menu a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: rgba(255,255,255,0.75); text-decoration: none; border-radius: 10px; font-size: 0.9rem; font-weight: 500; transition: all 0.3s; }
        .sidebar-menu a i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-menu a:hover { background: rgba(255,255,255,0.1); color: white; transform: translateX(4px); }
        .sidebar-menu a.active { background: linear-gradient(135deg, var(--accent), #d4849c); color: white; box-shadow: 0 4px 12px rgba(232, 164, 184, 0.4); }
        .sidebar-menu a .coming-soon { font-size: 0.65rem; background: rgba(255,255,255,0.15); padding: 2px 8px; border-radius: 10px; margin-left: auto; }
        .sidebar-menu a .badge-count { font-size: 0.7rem; background: var(--accent); color: white; padding: 2px 8px; border-radius: 10px; margin-left: auto; font-weight: 600; }

        /* CONTENT */
        .content { flex: 1; padding: 28px 32px; min-width: 0; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 1.8rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px; }
        .page-title i { color: var(--accent); }
        .page-subtitle { color: var(--muted); font-size: 0.9rem; margin-top: 4px; }

        /* CARDS */
        .card { border: none; border-radius: 14px; box-shadow: 0 4px 12px rgba(45,85,68,0.08); transition: box-shadow 0.3s; }
        .card:hover { box-shadow: 0 8px 22px rgba(45,85,68,0.12); }
        .card-header { border-radius: 14px 14px 0 0 !important; border-bottom: 1px solid var(--line); padding: 18px 24px; background: white; font-weight: 600; }

        /* BUTTONS */
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(45,85,68,0.2); }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(45,85,68,0.3); color: white; }
        .btn-accent { background: linear-gradient(135deg, var(--accent), #d4849c); color: white; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(232, 164, 184, 0.3); }
        .btn-accent:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(232, 164, 184, 0.5); color: white; }

        /* STAT CARDS */
        .stat-card { padding: 16px 20px; border-radius: 12px; background: #fffefa; border: 1px solid var(--line); height: 100%; }
        .stat-card.total { border-left: 4px solid var(--primary); }
        .stat-card.income { border-left: 4px solid var(--primary-light); }
        .stat-card.low { border-left: 4px solid #efb184; }
        .stat-card.out { border-left: 4px solid var(--accent); }
        .stat-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); font-weight: 600; }
        .stat-value { font-size: 1.8rem; font-weight: 700; margin: 6px 0 0; }
        .stat-card.total .stat-value { color: var(--primary-dark); }
        .stat-card.income .stat-value { color: var(--primary); }
        .stat-card.low .stat-value { color: #b87044; }
        .stat-card.out .stat-value { color: #c45a6e; }
        .stat-trend { font-size: 0.75rem; color: var(--primary-light); }

        /* FILTER */
        .filter-bar { background: #fffefa; border: 1px solid var(--line); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; }
        .filter-bar label { font-size: 0.7rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
        .form-control, .form-select { border-radius: 8px; border: 1.5px solid var(--line); padding: 9px 14px; font-size: 0.9rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 4px rgba(74,128,105,0.1); }

        /* PRODUCT CARD */
        .bucket-card { background: #fffefa; border: 1px solid var(--line); border-radius: 12px; overflow: hidden; transition: all 0.3s; height: 100%; }
        .bucket-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(45,85,68,0.15); }
        .bucket-img { height: 200px; object-fit: cover; width: 100%; }
        .bucket-placeholder { height: 200px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--accent-soft), var(--cream)); color: var(--accent); font-size: 3rem; }
        .stock-badge { position: absolute; top: 12px; right: 12px; padding: 5px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
        .stock-badge.high { background: #d1e7dd; color: #0a3622; }
        .stock-badge.low { background: #fff3cd; color: #664d03; }
        .stock-badge.out { background: #f8d7da; color: #58151c; }
        .category-pill { background: #eaf0e7; color: var(--primary); font-weight: 600; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; display: inline-block; }
        .product-actions { padding: 10px 12px; border-top: 1px solid var(--line); display: flex; gap: 6px; }

        /* UPLOAD */
        .upload-zone { border: 2px dashed var(--line); border-radius: 12px; padding: 40px 20px; text-align: center; background: var(--cream); transition: all 0.3s; min-height: 280px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .upload-zone:hover, .upload-zone.dragover { border-color: var(--primary-light); background: #eaf0e7; }
        .upload-zone i.upload-icon { font-size: 3rem; color: var(--accent); }

        /* TOAST */
        .toast-container-custom { position: fixed; top: 90px; right: 24px; z-index: 9999; }
        .toast-custom { min-width: 320px; padding: 16px 20px; background: white; border-radius: 12px; box-shadow: 0 16px 40px rgba(45,85,68,0.15); border-left: 4px solid var(--primary-light); display: flex; align-items: center; gap: 12px; margin-bottom: 10px; animation: slideIn 0.4s ease; }
        .toast-custom.success { border-left-color: #28a745; }
        .toast-custom.error { border-left-color: #dc3545; }
        .toast-custom i { font-size: 1.5rem; }
        .toast-custom.success i { color: #28a745; }
        .toast-custom.error i { color: #dc3545; }
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* EMPTY */
        .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
        .empty-state i { font-size: 4rem; color: var(--accent-soft); display: block; margin-bottom: 16px; }

        /* TABLE */
        .table thead th { background: var(--primary-dark); color: white; padding: 14px 16px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: 0; }
        .table tbody td { padding: 14px 16px; vertical-align: middle; border-color: #f5efe8; }
        .table tbody tr:hover { background: var(--cream); }

        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -260px; z-index: 999; transition: left 0.3s; }
            .sidebar.show { left: 0; }
            .content { padding: 20px 16px; }
        }
    </style>
</head>
<body>

    <nav class="top-nav">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-white d-md-none me-2" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a href="{{ route('products.index') }}" class="brand">
                <i class="bi bi-flower1"></i>
                <span>Bucket House Admin</span>
            </a>
        </div>
        <div class="user-info">
            <div class="d-none d-md-block text-end">
                <div style="font-weight: 500;">Halo, {{ Auth::user()->name }}</div>
            </div>
            <span class="badge-role">{{ Auth::user()->role }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
    </nav>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="bi bi-flower1"></i>
                <h5>BUCKET HOUSE</h5>
                <small>Admin Panel</small>
            </div>

            {{-- <div class="sidebar-section">Transaksi</div>
            <ul class="sidebar-menu">
                <li><a href="#" class="text-muted"><i class="bi bi-cart3"></i> Pesanan <span class="badge-count">5</span></a></li>
                <li><a href="#" class="text-muted"><i class="bi bi-credit-card"></i> Pembayaran</a></li>
                <li><a href="#" class="text-muted"><i class="bi bi-truck"></i> Pengiriman <span class="coming-soon">Soon</span></a></li>
            </ul>

            <div class="sidebar-section">Pelanggan</div>
            <ul class="sidebar-menu">
                <li><a href="#" class="text-muted"><i class="bi bi-people"></i> Daftar User</a></li>
                <li><a href="#" class="text-muted"><i class="bi bi-star"></i> Ulasan <span class="badge-count">3</span></a></li>
            </ul> --}}

            <div class="sidebar-section">Produk</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Kelola Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Kelola Kategori
                    </a>
                </li>
            </ul>

            {{-- <div class="sidebar-section">Lainnya</div>
            <ul class="sidebar-menu">
                <li><a href="#" class="text-muted"><i class="bi bi-gear"></i> Pengaturan</a></li>
                <li><a href="{{ route('home') }}" target="_blank"><i class="bi bi-globe"></i> Lihat Toko</a></li>
            </ul> --}}
        </aside>

        <main class="content">
            @yield('content')
        </main>
    </div>

    <div class="toast-container-custom">
        @if(session('success'))
            <div class="toast-custom success">
                <i class="bi bi-check-circle-fill"></i>
                <div>
                    <strong>Berhasil!</strong>
                    <div style="font-size: 0.85rem; color: #666;">{{ session('success') }}</div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-custom error">
                <i class="bi bi-x-circle-fill"></i>
                <div>
                    <strong>Gagal!</strong>
                    <div style="font-size: 0.85rem; color: #666;">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(() => {
            document.querySelectorAll('.toast-custom').forEach(t => {
                t.style.transition = 'all 0.4s';
                t.style.transform = 'translateX(120%)';
                t.style.opacity = '0';
                setTimeout(() => t.remove(), 400);
            });
        }, 4000);
    </script>
    @stack('scripts')
</body>
</html>
