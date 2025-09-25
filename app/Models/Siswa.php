<?php

namespace App\Models; // atau namespace sesuai struktur projek kamu

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Tentukan nama tabel kalau tabelnya bukan plural "siswas"
    protected $table = 'siswa';

    // Kalau kamu pakai guarded atau fillable, isi sesuai kebutuhan
    protected $fillable = ['name', 'nis', 'kelas', 'tahun_ajaran', 'email', 'password', 'role'];
}
