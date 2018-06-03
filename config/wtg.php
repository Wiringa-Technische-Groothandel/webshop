<?php

return [
    'orderReceiveEmail' => 'verkoop@wiringa.nl',
    'recaptcha' => [
        'site-key' => env('WTG_SITE_KEY'),
        'secret-key' => env('WTG_SECRET_KEY')
    ],
    'maps-api-key' => env('GOOGLE_MAPS_KEY')
];