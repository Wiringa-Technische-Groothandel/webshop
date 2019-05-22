<?php

/**
 * Configuration for the soap service.
 */
return [
    'logging' => env('SOAP_LOGGING', false),

    'wsdl' => env('SOAP_WSDL'),

    'user' => env('SOAP_USER'),

    'pass' => env('SOAP_PASS'),

    'admin' => env('SOAP_ADMIN'),

    'profiles' => [
        'product' => 'V1',
        'priceAndStock' => 'V1',
    ]
];