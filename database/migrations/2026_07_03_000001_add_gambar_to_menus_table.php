<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Path file gambar yang diupload (disimpan di storage/app/public/menus).
            // Kolom gambar_url lama tetap dipertahankan sebagai fallback (link gambar dari luar).
            $table->string('gambar')->nullable()->after('gambar_url');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
