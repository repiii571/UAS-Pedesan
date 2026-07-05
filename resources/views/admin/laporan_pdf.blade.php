<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: Helvetica, Arial, sans-serif; }
        body { font-size: 11px; color: #1e293b; }
        h1 { font-size: 18px; margin: 0 0 2px 0; color: #1a56db; }
        .subtitle { font-size: 10px; color: #64748b; margin-bottom: 14px; }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .summary-table td {
            width: 25%;
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: center;
        }
        .summary-value { font-size: 14px; font-weight: bold; color: #1e293b; }
        .summary-label { font-size: 8px; text-transform: uppercase; color: #64748b; }

        .chart-wrapper { text-align: center; margin-bottom: 18px; }
        .chart-wrapper img { width: 100%; max-width: 680px; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data thead th {
            background: #f8faff;
            border-bottom: 2px solid #e2e8f0;
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            padding: 6px 8px;
            text-align: left;
        }
        table.data tbody td {
            border-bottom: 1px solid #f1f5f9;
            padding: 6px 8px;
            font-size: 10px;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-lunas { background: #dbeafe; color: #1a56db; }
        .badge-pending { background: #fef3c7; color: #92400e; }

        .footer-note { margin-top: 14px; font-size: 8px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan — Warung Pedesan</h1>
    <div class="subtitle">
        Dicetak: {{ now()->format('d/m/Y H:i') }} WIB
        @if($dari || $sampai)
            &middot; Periode: {{ $dari ?? '(awal)' }} s/d {{ $sampai ?? '(sekarang)' }}
        @else
            &middot; Semua periode
        @endif
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-value">{{ $transaksis->count() }}</div>
                <div class="summary-label">Transaksi</div>
            </td>
            <td>
                <div class="summary-value">{{ $transaksis->where('status_pembayaran','belum_bayar')->count() }}</div>
                <div class="summary-label">Belum Bayar</div>
            </td>
            <td>
                <div class="summary-value">{{ $transaksis->flatMap->details->sum('jumlah') }}</div>
                <div class="summary-label">Total Porsi</div>
            </td>
            <td>
                <div class="summary-value">Rp{{ number_format($transaksis->where('status_pembayaran','lunas')->sum('total_bayar'),0,',','.') }}</div>
                <div class="summary-label">Pendapatan (Lunas)</div>
            </td>
        </tr>
    </table>

    <div class="chart-wrapper">
        <img src="{{ $chartImage }}" alt="Grafik pendapatan bulanan">
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Pelanggan</th>
                <th>Detail Pesanan</th>
                <th>Total</th>
                <th>Kasir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $t)
            <tr>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->nama_pelanggan }}</td>
                <td>
                    @foreach($t->details as $d)
                        {{ $d->menu->nama_menu ?? 'Menu Dihapus' }} x{{ $d->jumlah }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>Rp{{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                <td>{{ $t->user->name ?? '-' }}</td>
                <td>
                    @if($t->status_pembayaran == 'lunas')
                        <span class="badge badge-lunas">LUNAS</span>
                    @else
                        <span class="badge badge-pending">PENDING</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#94a3b8;padding:14px;">Tidak ada transaksi pada rentang yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">Warung Pedesan Sapi &amp; Kambing — Laporan dibuat otomatis oleh sistem.</div>
</body>
</html>
