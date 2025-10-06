<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\KategoriPembayaran;
use App\Models\Siswa;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'kategori_id', // BUKAN kategori_pembayaran_id
        'nama',
        'kelas',
        'jumlah',
        'tanggal_buat',
        'tanggal_tempo',
        'status',
    ];

    protected $casts = [
        'tanggal_buat' => 'date',
        'tanggal_tempo' => 'date',
        'jumlah' => 'float',
    ];

    /**
     * Relasi ke kategori pembayaran (many to one)
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriPembayaran::class, 'kategori_id');
    }

    /**
     * Relasi many-to-many ke siswa (user) menggunakan pivot table pembayaran_user
     */
    public function siswa()
    {
        return $this->belongsToMany(User::class, 'pembayaran_user', 'pembayaran_id', 'user_id')
                    ->withPivot([
                        'status',
                        'tanggal_pembayaran',
                        'metode', // hanya 'tunai' atau 'transfer'
                    ])
                    ->withTimestamps();
    }

    /**
     * Relasi many-to-many ke siswa (model Siswa) menggunakan pivot
     * Hanya jika Anda memang memerlukan relasi antara Pembayaran dan Siswa terpisah dari User
     */
    public function siswaModel()
    {
        return $this->belongsToMany(Siswa::class)->withPivot('status', 'tanggal_pembayaran', 'metode');
    }
}
