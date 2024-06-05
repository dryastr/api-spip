<?php

return [
    // Security Config
    'security' => [
        'encrypt_method' => env('SECURITY_ENCRYPT_METHOD', 'AES-256-CBC'),
        'secret_key' => env('SECURITY_SECRET_KEY'),
        'secret_iv' => env('SECURITY_SECRET_IV')
    ],
];
