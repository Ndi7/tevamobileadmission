<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'nama_ayah',
        'nama_ibu',
        'hp_orangtua',
        'hp_siswa',
        'agama',
        'tanggal_lahir',
        'sekolah',
        'kelas',
        'alamat',
        'mapel_wajib',
        'mapel_reguler',
        'mapel_ekskul',
        'total_harga',
        'foto',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'pendaftar_id');
    }
}