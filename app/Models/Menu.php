<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_menu',
        'kategori',
        'harga',
        'stok',
        'gambar_url', // fallback: link gambar dari luar (dipakai data lama/dummy)
        'gambar',     // path file yang diupload ke storage/app/public/menus
    ];

    /**
     * Relasi ke detail transaksi (menu bisa muncul di banyak transaksi).
     */
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    /**
     * URL gambar yang siap ditampilkan di view.
     * Prioritas: file yang diupload (via storage:link) > link URL eksternal > null.
     */
    public function getGambarTampilAttribute(): ?string
    {
        if ($this->gambar) {
            return Storage::url($this->gambar);
        }

        return $this->gambar_url;
    }
}
