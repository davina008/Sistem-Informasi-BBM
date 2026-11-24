<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PenanggungJawab;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    public $incrementing = false; // karena bukan AUTO_INCREMENT
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_divisi',
        'nama_divisi'
    ];

    /**
     * Relasi: 1 Divisi memiliki banyak Penanggung Jawab
     */
    public function penanggungJawab()
    {
        return $this->hasMany(PenanggungJawab::class, 'id_divisi', 'id_divisi');
    }
}