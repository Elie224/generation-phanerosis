<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration de sécurité de l'administrateur principal
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient les paramètres de sécurité pour protéger
    | l'administrateur principal de la plateforme.
    |
    */

    'main_admin' => [
        // Email de l'administrateur principal (NE JAMAIS MODIFIER)
        'email' => env('MAIN_ADMIN_EMAIL', 'kouroumaelisee@gmail.com'),
        
        // Rôle forcé pour l'administrateur principal
        'forced_role' => 'admin',
        
        // Statut forcé pour l'administrateur principal
        'forced_status' => true,
        
        // Protection contre la modification
        'prevent_modification' => true,
        
        // Protection contre la suppression
        'prevent_deletion' => true,
        
        // Protection contre la désactivation
        'prevent_deactivation' => true,
    ],

    'logging' => [
        // Activer les logs de sécurité
        'enabled' => env('ADMIN_SECURITY_LOGGING', true),
        
        // Niveau de log pour les tentatives d'accès
        'access_level' => 'info',
        
        // Niveau de log pour les tentatives de modification
        'modification_level' => 'warning',
        
        // Niveau de log pour les tentatives de suppression
        'deletion_level' => 'critical',
        
        // Niveau de log pour les actions de l'admin principal
        'admin_action_level' => 'info',
    ],

    'notifications' => [
        // Activer les notifications par email pour les tentatives suspectes
        'email_enabled' => env('ADMIN_SECURITY_EMAIL_NOTIFICATIONS', false),
        
        // Email pour recevoir les alertes de sécurité
        'alert_email' => env('ADMIN_SECURITY_ALERT_EMAIL', 'kouroumaelisee@gmail.com'),
        
        // Seuil pour déclencher une alerte (nombre de tentatives)
        'alert_threshold' => env('ADMIN_SECURITY_ALERT_THRESHOLD', 3),
    ],

    'rate_limiting' => [
        // Activer la limitation de taux pour les actions sensibles
        'enabled' => env('ADMIN_SECURITY_RATE_LIMITING', true),
        
        // Nombre maximum de tentatives par minute
        'max_attempts_per_minute' => env('ADMIN_SECURITY_MAX_ATTEMPTS', 5),
        
        // Durée de blocage en minutes
        'block_duration' => env('ADMIN_SECURITY_BLOCK_DURATION', 15),
    ],

    'ip_whitelist' => [
        // Activer la liste blanche d'IP pour l'admin principal
        'enabled' => env('ADMIN_SECURITY_IP_WHITELIST', false),
        
        // IPs autorisées pour accéder à l'admin principal
        'allowed_ips' => explode(',', env('ADMIN_SECURITY_ALLOWED_IPS', '')),
    ],
]; 