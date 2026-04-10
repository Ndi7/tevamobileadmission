<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'bukti',
        'jumlah',
        'status'
    ];

    public function pendaftar()
{
    return $this->belongsTo(\App\Models\Pendaftar::class, 'pendaftar_id');
}
}