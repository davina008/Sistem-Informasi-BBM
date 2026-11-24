<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Rekap extends Model
{
    use HasFactory;

    /** 🔹 Konfigurasi dasar model */
    protected $table = 'rekap';
    protected $primaryKey = 'id_rekap';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /** 🔹 Kolom yang boleh diisi mass assignment */
    protected $fillable = [
        'id_rekap',
        'tgl',
        'pengeluaran',
        'id_anggaran',
        'bukti_struk', // ✅ tambahkan kolom foto
    ];

    /** 
     * 🔹 Relasi ke tabel Anggaran 
     */
    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class, 'id_anggaran', 'id_anggaran');
    }

    /** 
     * 🔹 Relasi tidak langsung ke tabel Kendaraan melalui Anggaran 
     */
    public function kendaraan()
    {
        return $this->hasOneThrough(
            Kendaraan::class,   // Model tujuan akhir
            Anggaran::class,    // Model perantara
            'id_anggaran',      // Foreign key di tabel Anggaran
            'id_kendaraan',     // Foreign key di tabel Kendaraan
            'id_anggaran',      // Local key di tabel Rekap
            'id_kendaraan'      // Local key di tabel Anggaran
        );
    }

    /**
     * 🔄 Event otomatis: update total pemakaian anggaran
     * 🧹 + hapus file struk jika data rekap dihapus
     */
    protected static function booted(): void
    {
        // 🔸 Update total pemakaian anggaran setiap ada perubahan data rekap
        $updateAnggaran = function ($rekap) {
            DB::transaction(function () use ($rekap) {
                $rekap->loadMissing('anggaran');
                if ($rekap->anggaran) {
                    $rekap->anggaran->updateTotalPemakaian();
                }
            });
        };

        static::created($updateAnggaran);
        static::updated($updateAnggaran);
        static::deleted($updateAnggaran);

        // 🔸 Hapus file struk kalau rekap dihapus
        static::deleting(function ($rekap) {
            if ($rekap->bukti_struk && Storage::exists($rekap->bukti_struk)) {
                Storage::delete($rekap->bukti_struk);
            }
        });
    }

    /**
     * 🔹 Akses langsung plat kendaraan dari rekap
     */
    public function getNoPlatAttribute(): string
    {
        return $this->anggaran && $this->anggaran->kendaraan
            ? $this->anggaran->kendaraan->no_plat
            : '-';
    }

    /**
     * 🖼️ Mendapatkan URL publik untuk foto struk
     * Contoh di Blade: <img src="{{ $rekap->bukti_struk_url }}" width="100">
     */
    public function getBuktiStrukUrlAttribute(): ?string
    {
        return $this->bukti_struk
            ? Storage::url($this->bukti_struk)
            : null;
    }
}
