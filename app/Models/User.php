<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Pembayaran;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'nis',
        'kelas',
        'role',
        'tahun_ajaran',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Relasi many-to-many ke pembayaran menggunakan pivot table pembayaran_user
     */
    public function pembayarans(): BelongsToMany
    {
        return $this->belongsToMany(Pembayaran::class, 'pembayaran_user', 'user_id', 'pembayaran_id')  // Menentukan kolom foreign key pada pivot
                    ->withPivot(['status', 'tanggal_pembayaran', 'metode', 'order_id', 'bukti_transfer'])  // Kolom pivot
                    ->withTimestamps();
    }


    public function riwayatPembayaran()
{
    return $this->hasMany(\App\Models\RiwayatPembayaran::class);
}

}
