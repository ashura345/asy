<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Middleware groups lainnya

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     */
    protected $routeMiddleware = [
        'verified'   => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'is_admin'   => \App\Http\Middleware\IsAdmin::class,
        'is_siswa'   => \App\Http\Middleware\IsSiswa::class,
        'role'       => \App\Http\Middleware\CheckRole::class, // Untuk middleware 'role:admin' atau 'role:siswa'
    ];
}
