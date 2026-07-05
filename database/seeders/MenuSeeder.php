<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Jalankan database seeds untuk daftar menu dengan URL gambar.
     */
    public function run(): void
    {
        $menus = [
            [
                'nama_menu'  => 'Pedesan Iga Sapi',
                'kategori'   => 'sapi',
                'harga'      => 45000,
                'stok'       => 20,
                'gambar_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=500&auto=format&fit=crop',
            ],
            [
                'nama_menu'  => 'Pedesan Sumsum Sapi',
                'kategori'   => 'sapi',
                'harga'      => 50000,
                'stok'       => 10,
                'gambar_url' => 'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?q=80&w=500&auto=format&fit=crop',
            ],
            [
                'nama_menu'  => 'Pedesan Kepala Kambing',
                'kategori'   => 'kambing',
                'harga'      => 60000,
                'stok'       => 5,
                'gambar_url' => 'https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?q=80&w=500&auto=format&fit=crop',
            ],
            [
                'nama_menu'  => 'Mi Instan Pedesan Sapi',
                'kategori'   => 'mieinstan',
                'harga'      => 15000,
                'stok'       => 50,
                'gambar_url' => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?q=80&w=500&auto=format&fit=crop',
            ],
            [
                'nama_menu'  => 'Es Teh Manis',
                'kategori'   => 'minuman',
                'harga'      => 5000,
                'stok'       => 100,
                'gambar_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=500&auto=format&fit=crop',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(['nama_menu' => $menu['nama_menu']], $menu);
        }
    }
}