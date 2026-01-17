<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Traccar API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour la connexion à l'API Traccar GPS Tracking
    |
    */

    'url' => env('TRACCAR_URL', 'http://localhost:8082/api/'),

    'username' => env('TRACCAR_USERNAME', ''),

    'password' => env('TRACCAR_PASSWORD', ''),

];
