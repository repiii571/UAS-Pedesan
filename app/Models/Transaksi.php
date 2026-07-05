<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'nama_pelanggan', 
        'total_bayar', 
        'status_pembayaran'
    ];

    /**
     * Relasi ke Kasir (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Detail Transaksi (Banyak Menu)
     * Ini adalah fungsi yang menyebabkan error jika tidak ada.
     */
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}