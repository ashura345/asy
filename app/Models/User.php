<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // ⬅️ tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ⬅️ pastikan ada HasApiTokens di sini

    protected $fillable = [
        'name',
        'email',
        'nis',
        'password',
        'kelas',
        'tahun_ajaran',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}