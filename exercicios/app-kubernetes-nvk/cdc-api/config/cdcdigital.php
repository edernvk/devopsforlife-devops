<?php

return [

    'mail_tech' => [
        'host' => env('MAIL_TECH_HOST', 'localhost'),
        'port' => env('MAIL_TECH_PORT', 1025),
        'encryption' => env('MAIL_TECH_ENCRYPTION'),
        'username' => env('MAIL_TECH_USERNAME'),
        'password' => env('MAIL_TECH_PASSWORD'),
    ]

];
