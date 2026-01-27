<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AuthorizationService
{
    /**
     * Durée du cache en secondes (5 minutes)
     */
    private const CACHE_TTL = 300;
    
    /**
     * Vérifier si l'utilisateur a accès à un device
     * L'admin a accès à tous les devices
     * Les managers ont accès aux devices de leurs utilisateurs gérés
     * Les utilisateurs normaux n'ont accès qu'à leurs devices assignés
     */
    public static function canAccessDevice(User $user, int $deviceId): bool
    {
        // L'admin a accès à tout
        if ($user->administrator) {
            return true;
        }
        
        // Vérifier d'abord si le device est directement assigné à cet utilisateur
        $directAccess = UserDevice::where('userid', $user->id)
            ->where('deviceid', $deviceId)
            ->exists();
            
        if ($directAccess) {
            return true;
        }
        
        // Si c'est un manager, vérifier si le device appartient à un utilisateur géré
        if ($user->isManager()) {
            $managedUserIds = $user->getManagedUserIds();
            return UserDevice::whereIn('userid', $managedUserIds)
                ->where('deviceid', $deviceId)
                ->exists();
        }
        
        return false;
    }
    
    /**
     * Vérifier si l'utilisateur peut modifier un device
     * Admin ou utilisateur non-readonly avec accès au device
     */
    public static function canUpdateDevice(User $user, int $deviceId = null): bool
    {
        // L'utilisateur en lecture seule ne peut pas modifier
        if ($user->readonly || $user->devicereadonly) {
            return false;
        }
        
        // L'admin peut tout modifier
        if ($user->administrator) {
            return true;
        }
        
        // Si un deviceId est fourni, vérifier l'accès
        if ($deviceId !== null) {
            return self::canAccessDevice($user, $deviceId);
        }
        
        return true;
    }
    
    /**
     * Vérifier si l'utilisateur peut supprimer un device
     * Seul l'admin ou le propriétaire non-readonly peut supprimer
     */
    public static function canDeleteDevice(User $user, int $deviceId = null): bool
    {
        if ($user->readonly) {
            return false;
        }
        
        if ($user->administrator) {
            return true;
        }
        
        // Vérifier si c'est le propriétaire direct
        if ($deviceId !== null) {
            return UserDevice::where('userid', $user->id)
                ->where('deviceid', $deviceId)
                ->exists();
        }
        
        return false;
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les utilisateurs
     * Admin et managers peuvent gérer les utilisateurs
     */
    public static function canManageUsers(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }
    
    /**
     * Vérifier si l'utilisateur peut créer un nouvel utilisateur
     */
    public static function canCreateUser(User $user): bool
    {
        if ($user->readonly) {
            return false;
        }
        
        return $user->canCreateMoreUsers();
    }
    
    /**
     * Vérifier si l'utilisateur peut modifier un utilisateur spécifique
     */
    public static function canUpdateUser(User $user, int $targetUserId): bool
    {
        if ($user->readonly) {
            return false;
        }
        
        // On peut toujours modifier son propre profil
        if ($user->id === $targetUserId) {
            return true;
        }
        
        return $user->canManageUser($targetUserId);
    }
    
    /**
     * Vérifier si l'utilisateur peut supprimer un utilisateur spécifique
     */
    public static function canDeleteUser(User $user, int $targetUserId): bool
    {
        if ($user->readonly) {
            return false;
        }
        
        // On ne peut pas se supprimer soi-même
        if ($user->id === $targetUserId) {
            return false;
        }
        
        return $user->canManageUser($targetUserId);
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les groupes
     * Admin et managers peuvent gérer les groupes
     */
    public static function canManageGroups(User $user): bool
    {
        return $user->administrator || $user->isManager();
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les géofences
     * Tous les utilisateurs peuvent gérer leurs géofences
     */
    public static function canManageGeofences(User $user): bool
    {
        return !$user->readonly;
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les notifications
     * Tous les utilisateurs peuvent gérer leurs notifications
     */
    public static function canManageNotifications(User $user): bool
    {
        return !$user->readonly;
    }
    
    /**
     * Vérifier si l'utilisateur peut exécuter des commandes
     * Admin ou utilisateur avec accès au device
     */
    public static function canExecuteCommands(User $user, int $deviceId = null): bool
    {
        if ($user->readonly) {
            return false;
        }
        
        if ($user->administrator) {
            return true;
        }
        
        // Vérifier l'accès au device si fourni
        if ($deviceId !== null) {
            return self::canAccessDevice($user, $deviceId);
        }
        
        return true;
    }
    
    /**
     * Récupérer les IDs des devices auxquels l'utilisateur a accès (avec cache)
     */
    public static function getAccessibleDeviceIds(User $user): array
    {
        if ($user->administrator) {
            // Admin a accès à tous les devices - utiliser le cache
            return Cache::remember('all_device_ids', self::CACHE_TTL, function () {
                return DB::connection('traccar')->table('devices')
                    ->pluck('id')
                    ->toArray();
            });
        }
        
        $cacheKey = "user_{$user->id}_device_ids";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $deviceIds = UserDevice::where('userid', $user->id)
                ->pluck('deviceid')
                ->toArray();
            
            // Si c'est un manager, ajouter les devices des utilisateurs gérés
            if ($user->isManager()) {
                $managedUserIds = $user->getManagedUserIds();
                $managedDeviceIds = UserDevice::whereIn('userid', $managedUserIds)
                    ->pluck('deviceid')
                    ->toArray();
                    
                $deviceIds = array_unique(array_merge($deviceIds, $managedDeviceIds));
            }
            
            return $deviceIds;
        });
    }
    
    /**
     * Invalider le cache des devices pour un utilisateur
     */
    public static function invalidateDeviceCache(int $userId): void
    {
        Cache::forget("user_{$userId}_device_ids");
    }
    
    /**
     * Invalider tout le cache des devices
     */
    public static function invalidateAllDeviceCache(): void
    {
        Cache::forget('all_device_ids');
    }
}
