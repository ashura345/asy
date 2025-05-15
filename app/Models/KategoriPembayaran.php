<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPembayaran extends Model
{
    protected $fillable = ['nama', 'tipe', 'deskripsi'];
}
