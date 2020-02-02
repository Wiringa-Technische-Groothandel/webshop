<?php

return [
    'orderReceiveEmail' => 'verkoop@wiringa.nl',
    'recaptcha' => [
        'site-key' => env('WTG_SITE_KEY'),
        'secret-key' => env('WTG_SECRET_KEY')
    ],

    // RestClient config
    'rest' => [
        'base_url' => env('REST_URL', ''),
        'admin_code' => env('REST_ADMIN_CODE', ''),
        'auth' => [
            'username' => env('REST_USERNAME', ''),
            'password' => env('REST_PASSWORD', ''),
        ],
    ]
];
