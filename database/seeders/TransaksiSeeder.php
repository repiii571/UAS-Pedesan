<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Generate transaksi dummy untuk 6 bulan terakhir supaya chart pendapatan bulanan
     * di halaman Laporan langsung ada datanya.
     */
    public function run(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        $menus = Menu::all();

        if (!$kasir || $menus->isEmpty()) {
            $this->command?->warn('Lewati TransaksiSeeder: butuh minimal 1 user kasir dan beberapa menu (jalankan UserSeeder & MenuSeeder dulu).');
            return;
        }

        $namaPelangganList = [
            'Budi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Rina Kusuma', 'Joko Prasetyo',
            'Dewi Lestari', 'Agus Setiawan', 'Fitri Handayani', 'Bambang Hermawan', 'Yuni Astuti',
            'Hendra Gunawan', 'Maya Puspita',
        ];

        // Kosongkan data lama supaya tidak dobel kalau seeder dijalankan berkali-kali
        DetailTransaksi::query()->delete();
        Transaksi::query()->delete();

        // 6 bulan terakhir, termasuk bulan berjalan
        for ($bulanKe = 5; $bulanKe >= 0; $bulanKe--) {
            $bulan = now()->subMonths($bulanKe);
            $jumlahTransaksiBulanIni = rand(18, 32);

            for ($i = 0; $i < $jumlahTransaksiBulanIni; $i++) {
                $tanggal = $bulan->copy()
                    ->startOfMonth()
                    ->addDays(rand(0, $bulan->daysInMonth - 1))
                    ->addHours(rand(9, 20))
                    ->addMinutes(rand(0, 59));

                // Kalau kebetulan hasilnya di masa depan (bulan berjalan), mundurkan ke hari ini
                if ($tanggal->isFuture()) {
                    $tanggal = now()->subHours(rand(1, 72));
                }

                $transaksi = Transaksi::create([
                    'user_id' => $kasir->id,
                    'nama_pelanggan' => $namaPelangganList[array_rand($namaPelangganList)],
                    'total_bayar' => 0,
                    // ~85% lunas, sisanya masih pending — biar laporan terlihat realistis
                    'status_pembayaran' => rand(1, 100) <= 85 ? 'lunas' : 'belum_bayar',
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                $jumlahItem = rand(1, 3);
                $menuTerpilih = $menus->random(min($jumlahItem, $menus->count()));
                $menuTerpilih = $menuTerpilih instanceof Menu ? collect([$menuTerpilih]) : $menuTerpilih;

                $total = 0;
                foreach ($menuTerpilih as $menu) {
                    $qty = rand(1, 4);
                    $subtotal = $menu->harga * $qty;
                    $total += $subtotal;

                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'menu_id' => $menu->id,
                        'jumlah' => $qty,
                        'subtotal' => $subtotal,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);
                }

                $transaksi->update(['total_bayar' => $total]);
            }
        }

        $this->command?->info('TransaksiSeeder selesai: data dummy 6 bulan terakhir berhasil dibuat.');
    }
}
