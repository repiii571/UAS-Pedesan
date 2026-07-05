<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warung Pedesan') — Pedesan Sapi & Kambing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1648c0;
            --primary-light: #e8f0fe;
        }
        body { background: #f0f4ff; font-family: 'Segoe UI', system-ui, sans-serif; min-height: 100vh; }

        /* NAVBAR */
        .top-navbar { background: #1a56db; box-shadow: 0 2px 12px rgba(26,86,219,.25); }
        .top-navbar .navbar-brand { font-weight: 800; font-size: 1rem; color: #fff !important; gap: .5rem; }
        .top-navbar .brand-icon { width:34px;height:34px;background:rgba(255,255,255,.18);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem; }
        .top-navbar .nav-link { color:rgba(255,255,255,.8) !important;font-size:.875rem;font-weight:500;padding:.35rem .75rem !important;border-radius:8px;transition:background .15s;display:flex;align-items:center;gap:.4rem; }
        .top-navbar .nav-link:hover { background:rgba(255,255,255,.15);color:#fff !important; }
        .top-navbar .nav-link.active { background:rgba(255,255,255,.22);color:#fff !important; }
        .user-pill { background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:.3rem .85rem .3rem .4rem;color:#fff;font-size:.8rem;font-weight:600;display:flex;align-items:center;gap:.5rem; }
        .user-avatar { width:26px;height:26px;background:rgba(255,255,255,.25);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#fff; }
        .btn-logout { background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:8px;font-size:.8rem;padding:.3rem .75rem; }
        .btn-logout:hover { background:rgba(255,255,255,.28);color:#fff; }
        .navbar-toggler { color:#fff;border:none;box-shadow:none; }

        /* CONTENT */
        .content-wrapper { max-width:1200px;margin:0 auto;padding:1.75rem 1.25rem; }

        /* CARDS */
        .card { border:none;border-radius:14px;box-shadow:0 1px 8px rgba(0,0,0,.07); }
        .stat-card { transition:transform .2s,box-shadow .2s; }
        .stat-card:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.1); }
        .stat-icon { width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }

        /* TABLE */
        .table thead th { background:#f8faff;border-bottom:2px solid #e2e8f0;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;padding:.85rem 1rem; }
        .table tbody td { padding:.8rem 1rem;vertical-align:middle;font-size:.875rem;border-color:#f1f5f9; }
        .table tbody tr:hover { background:#f8faff; }

        /* FORMS */
        .form-label { font-size:.83rem;font-weight:600;color:#475569;margin-bottom:.35rem; }
        .form-control,.form-select { border-radius:9px;border-color:#d1d9f0;font-size:.875rem;padding:.6rem .9rem;transition:border-color .2s,box-shadow .2s; }
        .form-control:focus,.form-select:focus { border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,86,219,.12); }

        /* BUTTONS */
        .btn { border-radius:9px;font-size:.875rem;font-weight:500; }
        .btn-primary { background:var(--primary);border-color:var(--primary); }
        .btn-primary:hover { background:var(--primary-dark);border-color:var(--primary-dark); }
        .btn-sm { padding:.35rem .8rem;font-size:.8rem;border-radius:7px; }

        /* BADGES */
        .badge { font-size:.7rem;padding:.35em .65em;border-radius:6px;font-weight:600; }

        /* ALERTS */
        .alert { border-radius:11px;border:none;font-size:.875rem; }

        /* EMPTY */
        .empty-state { padding:3rem;text-align:center;color:#94a3b8; }
        .empty-state i { font-size:2.75rem;display:block;margin-bottom:.75rem; }
    </style>
    @stack('styles')
</head>
<body>
@auth
<nav class="top-navbar navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <div class="brand-icon me-2">🍖</div>
            Warung Pedesan
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>
                @if(auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" href="{{ route('admin.menus.index') }}">
                        <i class="bi bi-journal-richtext"></i> Kelola Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" href="{{ route('admin.laporan') }}">
                        <i class="bi bi-bar-chart-line"></i> Laporan
                    </a>
                </li>
                @endif
                @if(auth()->user()->role == 'kasir')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.transaksi.create') ? 'active' : '' }}" href="{{ route('kasir.transaksi.create') }}">
                        <i class="bi bi-plus-circle"></i> Buat Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.transaksi.index') ? 'active' : '' }}" href="{{ route('kasir.transaksi.index') }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                </li>
                @endif
            </ul>
            <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                <div class="user-pill">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    {{ auth()->user()->name }}
                    <span style="opacity:.55;">·</span>
                    <span style="opacity:.7;font-weight:400;">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>
@endauth

<div class="content-wrapper">
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill text-success"></i>
        {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
        {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => bootstrap.Alert.getOrCreateInstance(a).close());
    }, 4000);
</script>
@stack('scripts')
</body>
</html>