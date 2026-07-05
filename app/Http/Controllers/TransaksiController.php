<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class TransaksiController extends Controller
{
    /**
     * Nama bulan singkat berbahasa Indonesia (dipakai untuk label chart).
     */
    private const NAMA_BULAN = [
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
        '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des',
    ];

    // Menampilkan Laporan untuk Admin (tabel bisa difilter tanggal + chart pendapatan bulanan)
    public function adminIndex(Request $request)
    {
        $query = Transaksi::with(['details.menu', 'user'])->latest();

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transaksis = $query->get();

        // Chart pendapatan 6 bulan terakhir (independen dari filter tabel di atas)
        $chart = $this->getMonthlyRevenue(6);

        return view('admin.laporan', compact('transaksis', 'chart'));
    }

    // Export laporan penjualan ke PDF (mPDF), berisi chart pendapatan bulanan + tabel transaksi
    public function exportLaporan(Request $request)
    {
        $query = Transaksi::with(['details.menu', 'user'])->latest();

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transaksis = $query->get();

        $chart = $this->getMonthlyRevenue(6);
        $chartImage = $this->buildChartSvgDataUri($chart['labels'], $chart['data']);

        $html = view('admin.laporan_pdf', [
            'transaksis' => $transaksis,
            'chart' => $chart,
            'chartImage' => $chartImage,
            'dari' => $request->input('dari'),
            'sampai' => $request->input('sampai'),
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);
        $mpdf->WriteHTML($html);

        $filename = 'laporan-penjualan-' . now()->format('Y-m-d_His') . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Total pendapatan (transaksi lunas) per bulan, N bulan terakhir termasuk bulan berjalan.
     * Return: ['labels' => ['Feb 26', 'Mar 26', ...], 'data' => [120000, 340000, ...]]
     */
    private function getMonthlyRevenue(int $months = 6): array
    {
        $end = now()->endOfMonth();
        $start = now()->subMonths($months - 1)->startOfMonth();

        $rows = Transaksi::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periode, SUM(total_bayar) as total")
            ->where('status_pembayaran', 'lunas')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('periode')
            ->pluck('total', 'periode');

        $labels = [];
        $data = [];
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $key = $cursor->format('Y-m');
            $labels[] = self::NAMA_BULAN[$cursor->format('m')] . ' ' . $cursor->format('y');
            $data[] = (int) ($rows[$key] ?? 0);
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Bikin chart batang sederhana dalam bentuk SVG (murni PHP, tanpa GD/library tambahan),
     * dikembalikan sebagai data URI base64 supaya bisa ditempel via <img> di mPDF.
     */
    private function buildChartSvgDataUri(array $labels, array $data): string
    {
        $width = 680;
        $height = 300;
        $paddingLeft = 20;
        $paddingRight = 20;
        $paddingTop = 30;
        $paddingBottom = 45;

        $chartWidth = $width - $paddingLeft - $paddingRight;
        $chartHeight = $height - $paddingTop - $paddingBottom;

        $max = max($data ?: [0]) ?: 1;
        $barCount = max(count($data), 1);
        $barGap = 18;
        $barWidth = ($chartWidth - ($barGap * ($barCount - 1))) / $barCount;

        $bars = '';
        foreach ($data as $i => $value) {
            $barHeight = $max > 0 ? ($value / $max) * $chartHeight : 0;
            $x = $paddingLeft + $i * ($barWidth + $barGap);
            $y = $paddingTop + ($chartHeight - $barHeight);

            $bars .= sprintf(
                '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" fill="#1a56db" rx="4"/>',
                $x, $y, $barWidth, max($barHeight, 1)
            );

            $valueLabel = 'Rp' . number_format($value, 0, ',', '.');
            $bars .= sprintf(
                '<text x="%.2f" y="%.2f" font-family="Helvetica" font-size="10" text-anchor="middle" fill="#334155">%s</text>',
                $x + $barWidth / 2, max($y - 6, 12), $valueLabel
            );

            $label = $labels[$i] ?? '';
            $bars .= sprintf(
                '<text x="%.2f" y="%.2f" font-family="Helvetica" font-size="11" text-anchor="middle" fill="#64748b">%s</text>',
                $x + $barWidth / 2, $height - $paddingBottom + 20, htmlspecialchars($label)
            );
        }

        // Garis sumbu horizontal (dasar chart)
        $axisY = $paddingTop + $chartHeight;
        $bars .= sprintf(
            '<line x1="%d" y1="%.2f" x2="%d" y2="%.2f" stroke="#cbd5e1" stroke-width="1"/>',
            $paddingLeft, $axisY, $width - $paddingRight, $axisY
        );

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect x="0" y="0" width="{$width}" height="{$height}" fill="#ffffff"/>
    {$bars}
</svg>
SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    // Menampilkan Riwayat untuk Kasir
    public function index()
    {
        $transaksis = Transaksi::with(['details.menu'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('transaksi.index', compact('transaksis'));
    }

    // Form Pesanan Baru
    public function create()
    {
        $menus = Menu::where('stok', '>', 0)->get();
        return view('transaksi.create', compact('menus'));
    }

    // Simpan Transaksi (Banyak Menu)
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'user_id' => auth()->id(),
                'nama_pelanggan' => $request->nama_pelanggan,
                'total_bayar' => 0, 
                'status_pembayaran' => 'belum_bayar',
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                if ($menu->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$menu->nama_menu} tidak cukup.");
                }

                $subtotal = $menu->harga * $item['jumlah'];
                $total += $subtotal;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);

                $menu->decrement('stok', $item['jumlah']);
            }

            $transaksi->update(['total_bayar' => $total]);
            DB::commit();
            return redirect()->route('kasir.transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Update Status Bayar
    public function updatePaymentStatus($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status_pembayaran' => 'lunas']);
        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }
}
