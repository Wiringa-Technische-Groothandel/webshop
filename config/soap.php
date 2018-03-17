<?php

/**
 * Configuration for the soap service.
 */
return [
    'wsdl' => env('SOAP_WSDL'),

    'user' => env('SOAP_USER'),

    'pass' => env('SOAP_PASS'),

    'admin' => env('SOAP_ADMIN'),

    'profiles' => [
        'product' => 'V1',
        'priceAndStock' => 'V1',
    ]
];