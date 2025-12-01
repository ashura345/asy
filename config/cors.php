<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'webhooks/*'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => ['*'], // Production: ganti dengan domain spesifik
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];