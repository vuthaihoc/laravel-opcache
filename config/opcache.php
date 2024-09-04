<?php

return [
    'url' => env('OPCACHE_URL', config('app.url')),
    'enpoint' => env('OPCACHE_ENDPOINT', '/_utils/opcache-control'),
    'verify' => false,
    'validate_ip' => env('OPCACHE_VALIDATE_IP', false),
    'headers' => [
        'Accept' => 'application/json',
    ],
];
