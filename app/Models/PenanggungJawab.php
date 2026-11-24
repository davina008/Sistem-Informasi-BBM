<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawab extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab';
    protected $primaryKey = 'id_penanggung_jawab';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_penanggung_jawab',
        'nama',
        'no_hp',
        'id_divisi',
    ];

    /**
     * 🏢 Relasi ke tabel Divisi
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    /**
     * 🚗 Relasi ke kendaraan
     */
    public function kendaraan()
    {
        return $this->hasOne(Kendaraan::class, 'id_penanggung_jawab', 'id_penanggung_jawab');
    }
}
