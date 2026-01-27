<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des paramètres de sécurité de l'application
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration du rate limiting pour les API et l'authentification
    |
    */

    'rate_limits' => [
        // Limite pour les endpoints API généraux (requêtes par minute)
        'api' => [
            'max_attempts' => (int) env('API_RATE_LIMIT', 60),
            'decay_minutes' => 1,
        ],
        
        // Limite pour les tentatives de connexion
        'login' => [
            'max_attempts' => (int) env('LOGIN_RATE_LIMIT', 5),
            'decay_minutes' => (int) env('LOGIN_LOCKOUT_MINUTES', 15),
        ],
        
        // Limite pour les endpoints sensibles (création d'utilisateur, etc.)
        'sensitive' => [
            'max_attempts' => (int) env('SENSITIVE_RATE_LIMIT', 10),
            'decay_minutes' => 5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Requirements
    |--------------------------------------------------------------------------
    |
    | Exigences de complexité pour les mots de passe
    |
    */

    'password' => [
        'min_length' => (int) env('PASSWORD_MIN_LENGTH', 8),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_special' => env('PASSWORD_REQUIRE_SPECIAL', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Configuration de sécurité pour les sessions
    |
    */

    'session' => [
        // Durée d'inactivité maximale en minutes (0 = désactivé)
        'idle_timeout' => (int) env('SESSION_IDLE_TIMEOUT', 60),
        
        // Regénérer l'ID de session après authentification
        'regenerate_on_login' => true,
        
        // Invalider les autres sessions lors de la connexion
        'invalidate_other_sessions' => env('INVALIDATE_OTHER_SESSIONS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist / Blacklist
    |--------------------------------------------------------------------------
    |
    | Liste blanche/noire d'adresses IP
    |
    */

    'ip_restrictions' => [
        'enabled' => env('IP_RESTRICTIONS_ENABLED', false),
        
        // IPs autorisées (vide = toutes autorisées)
        'whitelist' => array_filter(explode(',', env('IP_WHITELIST', ''))),
        
        // IPs bloquées
        'blacklist' => array_filter(explode(',', env('IP_BLACKLIST', ''))),
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Configuration du logging des actions sensibles
    |
    */

    'audit' => [
        'enabled' => env('AUDIT_LOGGING_ENABLED', true),
        
        // Actions à logger
        'log_actions' => [
            'login',
            'logout',
            'login_failed',
            'password_change',
            'user_created',
            'user_deleted',
            'user_updated',
            'device_created',
            'device_deleted',
            'command_sent',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security
    |--------------------------------------------------------------------------
    |
    | Configuration de sécurité du contenu
    |
    */

    'content' => [
        // Domaines autorisés pour les requêtes CORS
        'allowed_origins' => array_filter(explode(',', env('CORS_ALLOWED_ORIGINS', ''))),
        
        // Types de fichiers autorisés pour upload
        'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'csv', 'xlsx'],
        
        // Taille maximale d'upload en KB
        'max_upload_size' => (int) env('MAX_UPLOAD_SIZE', 5120),
    ],

];
