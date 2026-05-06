<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pendaftar extends Model
{
    use HasFactory;

    // ❌ TIDAK PERLU protected $table
    // karena tabel = pendaftars (sudah sesuai Laravel)

    protected $fillable = [
        'user_id',
        'uuid',
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

    // ✅ INI WAJIB (biar web & flutter konsisten)
    protected $casts = [
        'mapel_wajib'   => 'array',
        'mapel_reguler' => 'array',
        'mapel_ekskul'  => 'array',
        'tanggal_lahir' => 'date',
        'total_harga'   => 'integer',
    ];

    // =====================
    // RELASI
    // =====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'pendaftar_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
        });
    }
}