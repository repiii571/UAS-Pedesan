@extends('layouts.app')

@section('page-title', 'Kelola Menu')
@section('breadcrumb', 'Manajemen Persediaan Pedesan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Daftar Menu</h4>
        <p class="text-muted small mb-0">Kelola stok, harga, dan foto menu pedesan.</p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg"></i> Tambah Menu Baru
    </a>
</div>

{{-- Tabel Menu --}}
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-secondary small fw-bold">
                        <th class="ps-4 py-3">FOTO</th>
                        <th class="py-3">NAMA MENU</th>
                        <th class="py-3">KATEGORI</th>
                        <th class="py-3">HARGA</th>
                        <th class="py-3">STOK</th>
                        <th class="py-3 pe-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($menus as $m)
                    <tr>
                        <td class="ps-4">
                            <img src="{{ $m->gambar_tampil ?? 'https://placehold.co/100x100?text=No+Image' }}" 
                                 alt="{{ $m->nama_menu }}" 
                                 class="rounded-3 shadow-sm"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            <span class="fw-bold d-block text-dark">{{ $m->nama_menu }}</span>
                        </td>
                        <td>
                            <span class="badge" style="background: var(--primary-light); color: var(--primary);">
                                {{ ucfirst($m->kategori) }}
                            </span>
                        </td>
                        <td><span class="fw-bold">Rp{{ number_format($m->harga, 0, ',', '.') }}</span></td>
                        <td>
                            @if($m->stok <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($m->stok <= 5)
                                <span class="badge bg-warning text-dark">{{ $m->stok }} Porsi ⚠️</span>
                            @else
                                <span class="text-success fw-bold">{{ $m->stok }} Porsi</span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            <div class="btn-group gap-2">
                                <a href="{{ route('admin.menus.edit', $m->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.menus.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada menu yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection