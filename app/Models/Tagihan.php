<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional kalau sama)
    protected $table = 'tagihans';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'nama_tagihan',
        'jumlah',
        'tanggal_jatuh_tempo',
        'status',
        'siswa_id',
    ];

    protected $casts = [
    'jumlah' => 'float',
    'jatuh_tempo' => 'datetime',
];

    // Relasi ke model Siswa
    public function user()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // Scope helper biar query lebih mudah
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeTunggakan($query)
    {
        return $query->where('status', 'tunggakan');
    }
}
