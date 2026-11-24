<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi tabel kendaraan.
     */
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id('id_kendaraan'); // Primary key
            $table->string('no_plat')->unique(); // Nomor plat kendaraan
            $table->string('merek'); // Merek kendaraan
            $table->string('jenis_kendaraan'); // Mobil / Motor
            $table->string('warna'); // Warna kendaraan
            $table->string('jenis_bahan_bakar'); // Solar / Bensin / Listrik
            $table->string('klp')->nullable(); // Kelompok / Unit opsional
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Hapus tabel kendaraan jika dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
