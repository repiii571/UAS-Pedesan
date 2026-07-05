<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel users.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->string('email')->unique();
            $blueprint->timestamp('email_verified_at')->nullable();
            $blueprint->string('password');
            // Menambahkan kolom role untuk multi-role (admin & kasir)
            $blueprint->string('role')->default('kasir'); 
            $blueprint->rememberToken();
            $blueprint->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};