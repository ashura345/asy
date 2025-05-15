<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'nis',
        'kelas',
        'role',
        'tahun_ajaran',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}