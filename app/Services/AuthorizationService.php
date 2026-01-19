<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;

class AuthorizationService
{
    /**
     * Vérifier si l'utilisateur a accès à un device
     * L'admin a accès à tous les devices
     * Les utilisateurs normaux n'ont accès qu'à leurs devices assignés
     */
    public static function canAccessDevice(User $user, int $deviceId): bool
    {
        // L'admin a accès à tout
        if ($user->administrator) {
            return true;
        }
        
        // Vérifier que le device est assigné à cet utilisateur
        return UserDevice::where('userid', $user->id)
            ->where('deviceid', $deviceId)
            ->exists();
    }
    
    /**
     * Vérifier si l'utilisateur peut modifier un device
     * Seul l'admin peut modifier les devices
     */
    public static function canUpdateDevice(User $user): bool
    {
        return $user->administrator;
    }
    
    /**
     * Vérifier si l'utilisateur peut supprimer un device
     * Seul l'admin peut supprimer les devices
     */
    public static function canDeleteDevice(User $user): bool
    {
        return $user->administrator;
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les utilisateurs
     * Seul l'admin peut voir les utilisateurs
     */
    public static function canManageUsers(User $user): bool
    {
        return $user->administrator;
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les groupes
     * Seul l'admin peut gérer les groupes
     */
    public static function canManageGroups(User $user): bool
    {
        return $user->administrator;
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les géofences
     * L'admin a accès à tous, les utilisateurs normaux à leurs géofences
     */
    public static function canManageGeofences(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent gérer leurs géofences
    }
    
    /**
     * Vérifier si l'utilisateur peut voir/gérer les notifications
     * L'admin a accès à tous, les utilisateurs normaux à leurs notifications
     */
    public static function canManageNotifications(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent gérer leurs notifications
    }
    
    /**
     * Vérifier si l'utilisateur peut exécuter des commandes
     * Seul l'admin peut exécuter des commandes
     */
    public static function canExecuteCommands(User $user): bool
    {
        return $user->administrator;
    }
}
