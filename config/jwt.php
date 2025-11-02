<?php

return [

    'secret' => env('JWT_SECRET'),

    'keys' => [
        'public' => env('JWT_PUBLIC_KEY', storage_path('keys/jwtRS256.key.pub')),
        'private' => env('JWT_PRIVATE_KEY', storage_path('keys/jwtRS256.key')),
        'passphrase' => env('JWT_PASSPHRASE', null),
    ],

    'ttl' => (int) env('JWT_TTL', 60), // 1 heure
    'refresh_iat' => env('JWT_REFRESH_IAT', true),
    'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 43200), // 30 jours

    'algo' => env('JWT_ALGO', 'RS256'),

    'required_claims' => ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'],

    'persistent_claims' => [],

    'lock_subject' => true,

    'leeway' => (int) env('JWT_LEEWAY', 0),

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    'blacklist_grace_period' => (int) env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    'show_black_list_exception' => env('JWT_SHOW_BLACKLIST_EXCEPTION', true),

    'decrypt_cookies' => false,

    'cookie_key_name' => 'token',

    'providers' => [
        'jwt' => PHPOpenSourceSaver\JWTAuth\Providers\JWT\Lcobucci::class,
        'auth' => PHPOpenSourceSaver\JWTAuth\Providers\Auth\Illuminate::class,
        'storage' => PHPOpenSourceSaver\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];
