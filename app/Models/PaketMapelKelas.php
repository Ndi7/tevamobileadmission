<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketMapelKelas extends Model
{
    protected $table = 'paket_mapel_kelas';
    protected $fillable = ['kelas_id', 'mata_pelajaran_id', 'tipe'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
