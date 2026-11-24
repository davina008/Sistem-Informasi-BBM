<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';
    protected $primaryKey = 'id_anggaran';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_anggaran',
        'id_kendaraan',
        'tahun',
        'anggaran_total',
        'pemakaian',
        'sisa',
    ];

    protected $casts = [
        'tahun'          => 'integer',
        'anggaran_total' => 'float',
        'pemakaian'      => 'float',
        'sisa'           => 'float',
    ];

    /* =========================================================
     | 🔗 RELASI
     ========================================================= */

    /**
     * Setiap anggaran dimiliki oleh satu kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(
            Kendaraan::class,
            'id_kendaraan',
            'id_kendaraan'
        );
    }

    /**
     * Relasi ke Rekap (One to Many)
     */
    public function rekaps()
    {
        return $this->hasMany(
            Rekap::class,
            'id_anggaran',
            'id_anggaran'
        );
    }

    /**
     * Relasi tidak langsung ke Penanggung Jawab (via Kendaraan)
     */
    public function penanggungJawab()
    {
        return $this->hasOneThrough(
            PenanggungJawab::class,
            Kendaraan::class,
            'id_kendaraan',            // foreign key di kendaraan
            'id_penanggung_jawab',     // foreign key di penanggung_jawab
            'id_kendaraan',            // local key di anggaran
            'id_penanggung_jawab'      // local key di kendaraan
        );
    }

    /* =========================================================
     | 🧩 ACCESSOR UTILITY
     ========================================================= */

    /**
     * Ambil nomor plat kendaraan
     */
    public function getNoPlatAttribute()
    {
        return $this->kendaraan->no_plat ?? '-';
    }

    /**
     * Nama Penanggung Jawab
     */
    public function getNamaPjAttribute()
    {
        return $this->kendaraan->penanggungJawab->nama ?? '-';
    }

    /**
     * Nomor HP penanggung jawab (untuk WhatsApp)
     */
    public function getNoHpPjAttribute()
    {
        return $this->kendaraan->no_hp_pj ?? null;
    }

    /* =========================================================
     | 🔢 UPDATE OTOMATIS NILAI PEMAKAIAN & SISA
     ========================================================= */

    public function updateTotalPemakaian()
    {
        $total = $this->rekaps()->sum('pengeluaran');

        $this->update([
            'pemakaian' => $total,
            'sisa'      => ($this->anggaran_total - $total),
        ]);
    }

    /* =========================================================
     | 🧠 BOOT (AUTO DEFAULT)
     ========================================================= */

    protected static function booted()
    {
        static::creating(function ($anggaran) {
            $anggaran->pemakaian = $anggaran->pemakaian ?? 0;
            $anggaran->sisa      = $anggaran->sisa ?? ($anggaran->anggaran_total ?? 0);
        });
    }
}
