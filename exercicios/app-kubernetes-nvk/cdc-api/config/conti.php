<?php

return [

    'host' => env('CDC_API_HOST', 'cdc-api.test'),

    'port' => env('CDC_API_PORT', '80'),

    'token' => env('CDC_API_TOKEN', 'token_test'),

    'auth' => [
        'type' => env('CDC_API_AUTH_TYPE', 'basic'),
        'username' => env('CDC_API_AUTH_USERNAME', 'none'),
        'password' => env('CDC_API_AUTH_PASSWORD', 'none'),
    ],

];
