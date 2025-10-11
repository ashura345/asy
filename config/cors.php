<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Tentukan route mana saja yang diizinkan untuk menerima request dari
    | domain lain (CORS). Biasanya cukup untuk route API.
    |
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | Tentukan method HTTP apa saja yang diizinkan. '*' artinya semua.
    |
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Tentukan domain mana saja yang diizinkan akses.
    | Saat development, gunakan ['*'] agar semua domain bisa akses.
    |
    */
    'allowed_origins' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | Biasanya dikosongkan kecuali kamu mau pakai regex untuk domain tertentu.
    |
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | Header apa saja yang diizinkan dikirim dari frontend (misal Authorization).
    |
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | Header yang diizinkan untuk dibaca frontend dari response server.
    |
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | Waktu cache preflight request (OPTIONS) dalam detik. Biasanya 0 atau 3600.
    |
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Set true kalau frontend butuh kirim cookie atau Authorization header.
    |
    */
    'supports_credentials' => false,

];
