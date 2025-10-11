<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        // Untuk API, kita balikin JSON, bukan redirect HTML
        if ($request->expectsJson()) {
            return null;
        }

        // Kalau bukan API (misalnya web), redirect ke login page
        return route('login');
    }
}
