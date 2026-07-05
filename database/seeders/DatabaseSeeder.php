<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * File ini adalah "pintu utama" untuk menjalankan semua seeder.
     */
    public function run(): void
    {
        // Panggil seeder satu per satu di sini
        $this->call([
            UserSeeder::class,      // Menjalankan UserSeeder (Admin & Kasir)
            MenuSeeder::class,      // Menjalankan MenuSeeder (Daftar Pedesan)
            TransaksiSeeder::class, // Data dummy transaksi 6 bulan terakhir (untuk chart & laporan)
        ]);
        
        // Info: Kamu bisa menambahkan seeder lain di dalam array di atas jika ada.
    }
}