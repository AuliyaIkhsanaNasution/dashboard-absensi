<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authentication
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [

        // Dashboard (WEB)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // API Karyawan (Android)
        'karyawan' => [
            'driver' => 'sanctum',
            'provider' => 'karyawans',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [

        // Admin Dashboard
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // Karyawan (Absensi)
        'karyawans' => [
            'driver' => 'eloquent',
            'model' => App\Models\Karyawan::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    */
    'passwords' => [

        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        // OPTIONAL (jika nanti karyawan reset password)
        'karyawans' => [
            'provider' => 'karyawans',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
