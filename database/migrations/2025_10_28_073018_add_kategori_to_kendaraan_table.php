<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (menambah kolom kategori)
     */
    public function up(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            // 🔹 Tambah kolom kategori di setelah id_kendaraan
            $table->string('kategori', 20)
                  ->default('per_plat')
                  ->after('id_kendaraan')
                  ->comment('Kategori kendaraan: per_plat atau operasional');
        });
    }

    /**
     * Batalkan perubahan (hapus kolom kategori)
     */
    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
