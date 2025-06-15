<?php

return [

    'defaults' => [
        'guard' => 'tenant',
        'passwords' => 'tenants', // Changed from 'users' to 'tenants'
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'tenants',
        ],
        'tenant' => [ // Add this
            'driver' => 'session',
            'provider' => 'tenants',
        ],
    ],

    'providers' => [
        'tenants' => [
            'driver' => 'eloquent',
            'model' => App\Models\Tenant::class,
        ],
    ],

    'passwords' => [
        'tenants' => [ // Changed from 'users' to 'tenants'
            'provider' => 'tenants',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
