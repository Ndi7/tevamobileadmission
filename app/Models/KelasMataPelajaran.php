<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasMataPelajaran extends Model
{
    protected $table = 'kelas_mata_pelajaran';

    protected $fillable = [
        'kelas_id',
        'mata_pelajaran_id',
        'tipe',
        'harga'
    ];

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}