<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Menambahkan kolom 'bukti_struk' pada tabel 'rekap'.
     */
    public function up(): void
    {
        // Cek dulu apakah kolom sudah ada agar tidak error saat migrate ulang
        if (!Schema::hasColumn('rekap', 'bukti_struk')) {
            Schema::table('rekap', function (Blueprint $table) {
                $table->string('bukti_struk')
                    ->nullable()
                    ->after('pengeluaran')
                    ->comment('Path foto bukti struk pengeluaran');
            });
        }
    }

    /**
     * Kembalikan migrasi (rollback).
     * Menghapus kolom 'bukti_struk' dari tabel 'rekap'.
     */
    public function down(): void
    {
        Schema::table('rekap', function (Blueprint $table) {
            if (Schema::hasColumn('rekap', 'bukti_struk')) {
                $table->dropColumn('bukti_struk');
            }
        });
    }
};
