@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e293b;">Riwayat Transaksi</h4>
        <p class="text-muted small mb-0">{{ $transaksis->count() }} transaksi tercatat dalam sistem</p>
    </div>
    @if(auth()->user()->role == 'kasir')
    <a href="{{ route('kasir.transaksi.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-1"></i>Pesanan Baru
    </a>
    @endif
</div>

{{-- Statistik Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;">
            <div class="fw-bold fs-4" style="color:#0d6efd;">{{ $transaksis->where('status_pembayaran','lunas')->count() }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem; text-transform: uppercase;">Lunas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;">
            <div class="fw-bold text-warning fs-4">{{ $transaksis->where('status_pembayaran','belum_bayar')->count() }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem; text-transform: uppercase;">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;">
            <div class="fw-bold fs-4" style="color:#1e293b;">{{ $transaksis->flatMap->details->sum('jumlah') }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem; text-transform: uppercase;">Total Porsi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px; background: #e7f1ff;">
            <div class="fw-bold text-primary" style="font-size:1rem;">Rp{{ number_format($transaksis->where('status_pembayaran','lunas')->sum('total_bayar'),0,',','.') }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem; text-transform: uppercase;">Pendapatan</div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-3">
        <div class="row g-2">
            <div class="col-md-8">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 bg-white" placeholder="Cari pelanggan atau menu...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="filterStatus" class="form-select form-select-sm bg-white">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="lunas">Lunas</option>
                    <option value="belum_bayar">Belum Bayar (Pending)</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Riwayat --}}
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius:16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-secondary small fw-bold">
                        <th class="ps-4 py-3">WAKTU</th>
                        <th class="py-3">PELANGGAN</th>
                        <th class="py-3">PESANAN (ITEMS)</th>
                        <th class="py-3">TOTAL</th>
                        <th class="py-3">STATUS</th>
                        <th class="py-3 pe-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                    {{-- Perbaikan baris 85: Mengambil semua nama menu dari relasi details --}}
                    @php
                        $menuNames = $t->details->map(function($d) { 
                            return $d->menu->nama_menu ?? 'Menu Dihapus'; 
                        })->join(', ');
                    @endphp
                    <tr class="transaksi-row" 
                        data-search="{{ strtolower($t->nama_pelanggan . ' ' . $menuNames) }}" 
                        data-status="{{ $t->status_pembayaran }}">
                        <td class="ps-4">
                            <div class="fw-bold text-dark small">{{ $t->created_at->format('d M Y') }}</div>
                            <div class="text-muted small" style="font-size: 0.7rem;">{{ $t->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $t->nama_pelanggan }}</div>
                            <div class="text-muted small" style="font-size: 0.7rem;">ID: #TRX-{{ $t->id }}</div>
                        </td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach($t->details as $detail)
                                <li class="small">
                                    <span class="fw-bold text-dark">{{ $detail->menu->nama_menu ?? 'Menu Dihapus' }}</span> 
                                    <span class="text-muted">x{{ $detail->jumlah }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">Rp{{ number_format($t->total_bayar, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            @if($t->status_pembayaran == 'lunas')
                                <span class="badge bg-primary bg-opacity-10 text-primary w-100 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> LUNAS
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning w-100 py-2">
                                    <i class="bi bi-hourglass-split me-1"></i> PENDING
                                </span>
                            @endif
                        </td>
                        <td class="pe-4 text-center">
                            @if($t->status_pembayaran == 'belum_bayar' && auth()->user()->role == 'kasir')
                                <form action="{{ route('kasir.transaksi.bayar', $t->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm px-3 shadow-sm rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        PROSES
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small fw-bold">
                                    <i class="bi bi-patch-check text-success"></i> SELESAI
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                            Belum ada riwayat transaksi yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const si = document.getElementById('searchInput');
    const fs = document.getElementById('filterStatus');

    function filterTable() {
        const query = si.value.toLowerCase();
        const status = fs.value;
        
        document.querySelectorAll('.transaksi-row').forEach(row => {
            const matchesSearch = row.dataset.search.includes(query);
            const matchesStatus = !status || row.dataset.status === status;
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    si.addEventListener('input', filterTable);
    fs.addEventListener('change', filterTable);
</script>
@endpush