@extends('layouts.app')

@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Laporan Penjualan</h4>
        <p class="text-muted small mb-0">{{ $transaksis->count() }} transaksi ditemukan</p>
    </div>
    <a href="{{ route('admin.laporan.export', request()->query()) }}" class="btn btn-outline-primary shadow-sm" target="_blank">
        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
    </a>
</div>

{{-- Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;">
            <div class="fw-bold fs-4" style="color:#1e293b;">{{ $transaksis->count() }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem;text-transform:uppercase;">Transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;">
            <div class="fw-bold text-warning fs-4">{{ $transaksis->where('status_pembayaran','belum_bayar')->count() }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem;text-transform:uppercase;">Belum Bayar</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;">
            <div class="fw-bold fs-4" style="color:#1e293b;">{{ $transaksis->flatMap->details->sum('jumlah') }}</div>
            <div class="text-muted fw-bold" style="font-size:.7rem;text-transform:uppercase;">Total Porsi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:16px;background:#e7f1ff;">
            <div class="fw-bold text-primary" style="font-size:1rem;">
                Rp{{ number_format($transaksis->where('status_pembayaran','lunas')->sum('total_bayar'),0,',','.') }}
            </div>
            <div class="text-muted fw-bold" style="font-size:.7rem;text-transform:uppercase;">Pendapatan (Lunas)</div>
        </div>
    </div>
</div>

{{-- Chart Pendapatan Bulanan --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-1">Tren Pendapatan 6 Bulan Terakhir</h6>
        <p class="text-muted small mb-3">Total pendapatan (transaksi lunas) per bulan.</p>
        <div style="position:relative;height:280px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

{{-- Filter tanggal --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.laporan') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel me-1"></i>Terapkan
                </button>
                <a href="{{ route('admin.laporan') }}" class="btn btn-light btn-sm border">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-secondary small fw-bold">
                        <th class="ps-4 py-3">WAKTU</th>
                        <th class="py-3">PELANGGAN</th>
                        <th class="py-3">DETAIL PESANAN</th>
                        <th class="py-3">TOTAL</th>
                        <th class="py-3 text-center">KASIR</th>
                        <th class="py-3 pe-4">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold d-block small">{{ $t->created_at->format('d/m/Y') }}</span>
                            <span class="text-muted small">{{ $t->created_at->format('H:i') }}</span>
                        </td>
                        <td><span class="fw-bold text-primary">{{ $t->nama_pelanggan }}</span></td>
                        <td>
                            <ul class="list-unstyled mb-0 small">
                                @foreach($t->details as $d)
                                <li>{{ $d->menu->nama_menu ?? 'Menu Dihapus' }} <span class="text-muted">x{{ $d->jumlah }}</span></li>
                                @endforeach
                            </ul>
                        </td>
                        <td><span class="fw-bold">Rp{{ number_format($t->total_bayar, 0, ',', '.') }}</span></td>
                        <td class="text-center small">{{ $t->user->name ?? '-' }}</td>
                        <td class="pe-4">
                            <span class="badge {{ $t->status_pembayaran == 'lunas' ? 'bg-primary' : 'bg-warning text-dark' }} w-100">
                                {{ strtoupper(str_replace('_', ' ', $t->status_pembayaran)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                            Tidak ada transaksi pada rentang yang dipilih.
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chart['labels']),
            datasets: [{
                label: 'Pendapatan',
                data: @json($chart['data']),
                backgroundColor: '#1a56db',
                borderRadius: 6,
                maxBarThickness: 56,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (item) => 'Rp' + item.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => 'Rp' + Number(value).toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
@endpush
