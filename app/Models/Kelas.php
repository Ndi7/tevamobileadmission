<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MataPelajaran;
use App\Models\KelasMataPelajaran;
use App\Models\PaketMapelKelas;

class Kelas extends Model
{
    protected $fillable = ['nama_kelas', 'jenjang'];

    /**
     * Relasi many-to-many ke MataPelajaran melalui tabel pivot kelas_mata_pelajaran.
     */
    public function mataPelajaran()
    {
        return $this->belongsToMany(
            MataPelajaran::class,
            'kelas_mata_pelajaran',
            'kelas_id',
            'mata_pelajaran_id'
        )->withPivot('tipe')->withTimestamps();
    }

    /**
     * Relasi one-to-many ke tabel KelasMataPelajaran (pivot model).
     */
    public function kelasMataPelajaran()
    {
        return $this->hasMany(KelasMataPelajaran::class, 'kelas_id');
    }

    /**
     * Relasi one-to-many ke tabel PaketMapelKelas (jika digunakan terpisah dari pivot).
     */
    public function paket()
    {
        return $this->hasMany(PaketMapelKelas::class, 'kelas_id');
    }
    
}
