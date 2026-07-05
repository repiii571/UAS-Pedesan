<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $blueprint) {
            $blueprint->id();
            // Menghubungkan detail ini ke transaksi utama (Header)
            $blueprint->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $blueprint->foreignId('menu_id')->constrained('menus');
            $blueprint->integer('jumlah'); // Jumlah porsi
            $blueprint->integer('subtotal'); // Harga menu saat itu x jumlah
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
