<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;
use App\Models\PaketMapelKelas;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = ['nama_mapel',];

    /**
     * Relasi many-to-many dengan Kelas melalui tabel pivot kelas_mata_pelajaran.
     */
    public function kelas()
    {
        return $this->belongsToMany(
            Kelas::class,
            'kelas_mata_pelajaran',
            'mata_pelajaran_id',
            'kelas_id'
        )->withPivot('tipe')->withTimestamps();
    }

    /**
     * Relasi one-to-many ke tabel PaketMapelKelas (jika ada tabel paket khusus).
     */
    public function paket()
    {
        return $this->hasMany(PaketMapelKelas::class, 'mata_pelajaran_id');
    }

    /**
     * Accessor agar nama mapel otomatis kapital di huruf pertama.
     */
    public function getNamaMapelAttribute($value)
    {
        return ucfirst($value);
    }
}
