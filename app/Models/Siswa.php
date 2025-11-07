<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'siswa';

    protected $fillable = ['nama', 'nis', 'password'];

    protected $hidden = ['password'];
}
