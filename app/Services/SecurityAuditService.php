<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SecurityAuditService
{
    /**
     * Enregistre une action de sécurité dans les logs
     */
    public static function log(string $action, array $context = [], string $level = 'info'): void
    {
        if (!config('security.audit.enabled', true)) {
            return;
        }
        
        $loggedActions = config('security.audit.log_actions', []);
        
        if (!in_array($action, $loggedActions)) {
            return;
        }
        
        $data = array_merge([
            'action' => $action,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'timestamp' => now()->toIso8601String(),
        ], $context);
        
        // Masquer les données sensibles
        $data = self::maskSensitiveData($data);
        
        Log::channel('security')->{$level}("Security Audit: {$action}", $data);
    }
    
    /**
     * Log une tentative de connexion réussie
     */
    public static function logLogin(int $userId, string $email): void
    {
        self::log('login', [
            'user_id' => $userId,
            'email' => $email,
        ]);
    }
    
    /**
     * Log une tentative de connexion échouée
     */
    public static function logLoginFailed(string $email, string $reason = 'invalid_credentials'): void
    {
        self::log('login_failed', [
            'email' => $email,
            'reason' => $reason,
        ], 'warning');
    }
    
    /**
     * Log une déconnexion
     */
    public static function logLogout(int $userId): void
    {
        self::log('logout', [
            'user_id' => $userId,
        ]);
    }
    
    /**
     * Log une création d'utilisateur
     */
    public static function logUserCreated(int $createdUserId, string $email): void
    {
        self::log('user_created', [
            'created_user_id' => $createdUserId,
            'created_email' => $email,
        ]);
    }
    
    /**
     * Log une suppression d'utilisateur
     */
    public static function logUserDeleted(int $deletedUserId, string $email): void
    {
        self::log('user_deleted', [
            'deleted_user_id' => $deletedUserId,
            'deleted_email' => $email,
        ], 'warning');
    }
    
    /**
     * Log une tentative d'accès non autorisé
     */
    public static function logUnauthorizedAccess(string $resource, string $action): void
    {
        self::log('unauthorized_access', [
            'resource' => $resource,
            'attempted_action' => $action,
        ], 'warning');
    }
    
    /**
     * Log une commande envoyée à un device
     */
    public static function logCommandSent(int $deviceId, string $commandType): void
    {
        self::log('command_sent', [
            'device_id' => $deviceId,
            'command_type' => $commandType,
        ]);
    }
    
    /**
     * Log un trop grand nombre de tentatives (rate limiting)
     */
    public static function logRateLimitExceeded(string $endpoint): void
    {
        self::log('rate_limit_exceeded', [
            'endpoint' => $endpoint,
        ], 'warning');
    }
    
    /**
     * Masque les données sensibles dans les logs
     */
    private static function maskSensitiveData(array $data): array
    {
        $sensitiveKeys = ['password', 'token', 'secret', 'api_key', 'authorization'];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::maskSensitiveData($value);
            } elseif (is_string($value)) {
                foreach ($sensitiveKeys as $sensitiveKey) {
                    if (stripos($key, $sensitiveKey) !== false) {
                        $data[$key] = '***MASKED***';
                    }
                }
            }
        }
        
        return $data;
    }
}
