<?php

namespace App\Auth;

use App\Models\User;
use App\Services\TraccarHasher;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class TraccarUserProvider implements UserProvider
{
    protected TraccarHasher $hasher;

    public function __construct()
    {
        $this->hasher = new TraccarHasher();
    }

    /**
     * Récupérer un utilisateur par son ID
     */
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    /**
     * Récupérer un utilisateur par son ID et token "remember me"
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = User::find($identifier);
        
        if (!$user) {
            return null;
        }

        $rememberToken = $user->getRememberToken();
        
        return $rememberToken && hash_equals($rememberToken, $token) ? $user : null;
    }

    /**
     * Mettre à jour le token "remember me"
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    /**
     * Récupérer un utilisateur par ses credentials
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || !isset($credentials['email'])) {
            return null;
        }

        return User::where('email', $credentials['email'])->first();
    }

    /**
     * Valider les credentials d'un utilisateur avec PBKDF2-SHA1
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!isset($credentials['password'])) {
            return false;
        }

        $plain = $credentials['password'];
        
        return $this->hasher->check($plain, $user->getAuthPassword(), ['user' => $user]);
    }

    /**
     * Réhabiliter l'utilisateur avec le hash
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Traccar n'a pas besoin de rehash
        return;
    }
}
