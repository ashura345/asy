<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans'; // Nama tabel

    protected $fillable = [
        'user_id',
        'nama',
        'kelas',
        'tanggal_pembayaran',
        'status_pembayaran',
        'jumlah',
    ];
    

    // Relasi ke user/siswa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
