<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     * Kita menghapus HasApiTokens karena proyek ini berbasis Web murni.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi: Satu user (admin/kasir) bisa memiliki banyak transaksi.
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}