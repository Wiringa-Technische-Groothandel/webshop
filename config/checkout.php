<?php

return [
    'cart' => [
        /**
         * The default cart driver that will be used.
         */
        'default' => 'eloquent',

        /**
         * The available storage drivers for the cart.
         *
         */
        'drivers' => [
            'eloquent' => [
                /**
                 * This is the model that will be used as
                 * a backend model for the Eloquent cart.
                 */
                'class' => WTG\Models\Quote::class
            ],
        ]
    ]
];