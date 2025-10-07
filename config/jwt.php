<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | This value is the secret key used to sign your JSON Web Tokens.
    | Make sure to set this in your environment file and keep it secure.
    |
    */

    'secret' => env('JWT_SECRET', 'your-default-secret'),

    /*
    |--------------------------------------------------------------------------
    | JWT Time To Live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 60 minutes.
    |
    */

    'ttl' => (int) env('JWT_TTL', 60),
];
