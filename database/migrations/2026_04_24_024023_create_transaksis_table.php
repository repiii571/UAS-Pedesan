<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained('users'); // Kasir yang melayani
            $blueprint->string('nama_pelanggan');
            $blueprint->integer('total_bayar')->default(0); // Penjumlahan dari semua subtotal
            $blueprint->enum('status_pembayaran', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};