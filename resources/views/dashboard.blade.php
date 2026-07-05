@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Dashboard</h4>
        <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="p-4 mb-4 rounded-3 text-white"
     style="background:linear-gradient(135deg,#1a56db 0%,#3b82f6 100%);position:relative;overflow:hidden;">
    <div style="position:absolute;right:-10px;top:-10px;font-size:7rem;opacity:.07;line-height:1;">🍖</div>
    <div class="position-relative">
        <h4 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}! 👋</h4>
        <p class="mb-0" style="opacity:.8;">
            Anda masuk sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>. Selamat bekerja!
        </p>
    </div>
</div>

@if(auth()->user()->role == 'admin')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="stat-icon" style="background:#e8f0fe;color:#1a56db;">
                    <i class="bi bi-journal-richtext"></i>
                </div>
                <div class="flex-fill">
                    <h6 class="fw-bold mb-1">Kelola Menu</h6>
                    <p class="text-muted small mb-3">Tambah, edit, dan atur stok menu pedesan.</p>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-right-circle me-1"></i>Buka Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="stat-icon" style="background:#e8f0fe;color:#1a56db;">
                    <i class="bi bi-bar-chart-line-fill"></i>
                </div>
                <div class="flex-fill">
                    <h6 class="fw-bold mb-1">Laporan Penjualan</h6>
                    <p class="text-muted small mb-3">Pantau total transaksi dan pendapatan warung.</p>
                    <a href="{{ route('admin.laporan') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-right-circle me-1"></i>Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(auth()->user()->role == 'kasir')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="stat-icon" style="background:#e8f0fe;color:#1a56db;">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
                <div class="flex-fill">
                    <h6 class="fw-bold mb-1">Buat Pesanan Baru</h6>
                    <p class="text-muted small mb-3">Input orderan pelanggan yang baru datang.</p>
                    <a href="{{ route('kasir.transaksi.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="stat-icon" style="background:#e8f0fe;color:#1a56db;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="flex-fill">
                    <h6 class="fw-bold mb-1">Riwayat Transaksi</h6>
                    <p class="text-muted small mb-3">Konfirmasi pembayaran dan pantau pesanan.</p>
                    <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-list-ul me-1"></i>Cek Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
