<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';
    protected $primaryKey = 'id_kendaraan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_kendaraan',
        'kategori',
        'no_plat',
        'merek',
        'jenis_kendaraan',
        'warna',
        'jenis_bahan_bakar',
        'id_penanggung_jawab',
    ];

    protected $casts = [
        'id_kendaraan'        => 'string',
        'id_penanggung_jawab' => 'string',
        'kategori'            => 'string',
    ];

    /* ================================================================
     | 🔗 RELASI
     ================================================================ */

    public function penanggungJawab()
    {
        return $this->belongsTo(
            PenanggungJawab::class,
            'id_penanggung_jawab',
            'id_penanggung_jawab'
        );
    }

    public function anggarans()
    {
        return $this->hasMany(Anggaran::class, 'id_kendaraan', 'id_kendaraan');
    }

    /* ================================================================
     | 🔍 QUERY SCOPE
     ================================================================ */

    public function scopeKategori($query, $tipe)
    {
        if (!empty($tipe)) {
            $query->where('kategori', $tipe);
        }
        return $query;
    }

    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('no_plat', 'like', "%{$keyword}%")
                  ->orWhere('merek', 'like', "%{$keyword}%")
                  ->orWhere('jenis_kendaraan', 'like', "%{$keyword}%")
                  ->orWhere('warna', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }

    /* ================================================================
     | 🧩 ACCESSOR YANG AMAN
     ================================================================ */

    /**
     * Nama penanggung jawab kendaraan
     */
    public function getNamaPjAttribute()
    {
        return optional($this->penanggungJawab)->nama ?? '-';
    }

    /**
     * Nomor HP penanggung jawab kendaraan (untuk WhatsApp)
     */
    public function getNoHpPjAttribute()
    {
        return optional($this->penanggungJawab)->no_hp ?? null;
    }
}
