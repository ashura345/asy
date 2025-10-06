<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pembayarans';

    protected $fillable = [
        'pembayaran_id',
        'user_id',
        'status',
        'jumlah_bayar',
        'metode',
        'tanggal_bayar',
        'no_referensi',
        'dibuat_oleh',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
