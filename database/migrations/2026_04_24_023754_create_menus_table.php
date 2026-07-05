<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('nama_menu');
            
            // Diubah ke string agar fleksibel (bisa mi instan, minuman, dll)
            $blueprint->string('kategori'); 
            
            $blueprint->integer('harga');
            $blueprint->integer('stok');
            
            // Tambahkan gambar_url bersifat opsional (nullable)
            $blueprint->text('gambar_url')->nullable(); 
            
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};