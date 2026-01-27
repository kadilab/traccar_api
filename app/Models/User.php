<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'traccar'; // Utilise la DB Traccar
    protected $table = 'users';     // Table Traccar

    // Traccar n'utilise pas created_at/updated_at par défaut
    public $timestamps = false;
    
    // Désactiver le remember_token car la table Traccar n'a pas cette colonne
    protected $rememberTokenName = false;

    protected $fillable = [
        'name', 'email', 'hashedpassword', 'salt', 'attributes', 
        'administrator', 'disabled', 'readonly', 'devicereadonly',
        'userlimit', 'devicelimit', 'expirationtime', 'phone',
    ];

    // Cast les colonnes appropriées
    protected $casts = [
        'administrator' => 'boolean',
        'disabled' => 'boolean',
        'readonly' => 'boolean',
        'devicereadonly' => 'boolean',
        'userlimit' => 'integer',
        'devicelimit' => 'integer',
        'expirationtime' => 'datetime',
    ];

    // Indiquer à Laravel quelle colonne contient le mot de passe
    public function getAuthPassword()
    {
        return $this->hashedpassword;
    }
    
    /**
     * Vérifie si l'utilisateur est administrateur
     */
    public function isAdmin(): bool
    {
        return (bool) $this->administrator;
    }
    
    /**
     * Vérifie si l'utilisateur est un manager (peut gérer d'autres utilisateurs)
     * Un manager a userlimit > 0 ou userlimit = -1 (illimité)
     */
    public function isManager(): bool
    {
        // Les admins sont automatiquement des managers
        if ($this->isAdmin()) {
            return true;
        }
        
        // Un utilisateur avec userlimit > 0 ou -1 (illimité) est un manager
        $userLimit = $this->userlimit ?? 0;
        return $userLimit > 0 || $userLimit === -1;
    }
    
    /**
     * Vérifie si l'utilisateur peut gérer un autre utilisateur spécifique
     */
    public function canManageUser(int $targetUserId): bool
    {
        // Les admins peuvent gérer tout le monde
        if ($this->isAdmin()) {
            return true;
        }
        
        // Vérifier si l'utilisateur cible est un utilisateur géré
        return DB::connection('traccar')->table('user_user')
            ->where('userid', $this->id)
            ->where('manageduserid', $targetUserId)
            ->exists();
    }
    
    /**
     * Récupère les IDs des utilisateurs que ce manager peut gérer
     */
    public function getManagedUserIds(): array
    {
        if ($this->isAdmin()) {
            // Les admins peuvent voir tous les utilisateurs
            return DB::connection('traccar')->table('users')
                ->pluck('id')
                ->toArray();
        }
        
        return DB::connection('traccar')->table('user_user')
            ->where('userid', $this->id)
            ->pluck('manageduserid')
            ->toArray();
    }
    
    /**
     * Vérifie si l'utilisateur est en lecture seule
     */
    public function isReadOnly(): bool
    {
        return (bool) $this->readonly;
    }
    
    /**
     * Vérifie si le compte est expiré
     */
    public function isExpired(): bool
    {
        if (!$this->expirationtime) {
            return false;
        }
        return $this->expirationtime->isPast();
    }
    
    /**
     * Vérifie si l'utilisateur peut créer plus d'utilisateurs
     */
    public function canCreateMoreUsers(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $userLimit = $this->userlimit ?? 0;
        
        // -1 = illimité
        if ($userLimit === -1) {
            return true;
        }
        
        // 0 = ne peut pas créer d'utilisateurs
        if ($userLimit === 0) {
            return false;
        }
        
        // Compter les utilisateurs gérés actuellement
        $managedCount = DB::connection('traccar')->table('user_user')
            ->where('userid', $this->id)
            ->count();
            
        return $managedCount < $userLimit;
    }
    
    /**
     * Vérifie si l'utilisateur peut ajouter plus de devices
     */
    public function canAddMoreDevices(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $deviceLimit = $this->devicelimit ?? -1;
        
        // -1 = illimité
        if ($deviceLimit === -1) {
            return true;
        }
        
        // Compter les devices actuellement assignés
        $deviceCount = DB::connection('traccar')->table('user_device')
            ->where('userid', $this->id)
            ->count();
            
        return $deviceCount < $deviceLimit;
    }
}