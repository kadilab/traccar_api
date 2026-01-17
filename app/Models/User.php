<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'traccar'; // Utilise la DB Traccar
    protected $table = 'users';     // Table Traccar

    // Traccar n'utilise pas created_at/updated_at par défaut
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'hashedpassword', 'salt', 'attributes', 'administrator', 'disabled', 'readonly',
    ];

    // Cast la colonne administrator en boolean
    protected $casts = [
        'administrator' => 'boolean',
        'disabled' => 'boolean',
        'readonly' => 'boolean',
    ];

    // Indiquer à Laravel quelle colonne contient le mot de passe
    public function getAuthPassword()
    {
        return $this->hashedpassword;
    }
    
    // Alias pour compatibilité
    public function isAdmin(): bool
    {
        return (bool) $this->administrator;
    }
}