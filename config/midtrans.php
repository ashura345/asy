<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-KPLwfs_MA_vQGr5mmUCzsJnv'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-76pZMC4XR5OkprtT'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'G099523672'),
    'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
    'isSanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is3ds' => env('MIDTRANS_IS_3DS', true),
];
