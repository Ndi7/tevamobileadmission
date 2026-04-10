<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel di database
    protected $table = 'admin';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Kolom yang disembunyikan saat diambil datanya
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
