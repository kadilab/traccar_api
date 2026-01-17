<?php

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher;

class TraccarHasher implements Hasher
{
    /**
     * Créer un hash compatible Traccar (PBKDF2-HMAC-SHA1)
     */
    public function make($value, array $options = [])
    {
        // Générer un sel de 24 octets (48 caractères hex)
        $salt = bin2hex(random_bytes(24));
        
        // PBKDF2 avec SHA1, 1000 itérations, 24 octets de sortie
        $hash = hash_pbkdf2("sha1", $value, hex2bin($salt), 1000, 24, true);
        
        return [
            'hash' => strtoupper(bin2hex($hash)),
            'salt' => strtoupper($salt)
        ];
    }

    /**
     * Vérifier un mot de passe contre un hash Traccar
     */
    public function check($value, $hashedValue, array $options = [])
    {
        $user = $options['user'] ?? null;
        if (!$user || !isset($user->salt)) {
            return false;
        }

        $salt = hex2bin($user->salt);
        $calculatedHash = hash_pbkdf2("sha1", $value, $salt, 1000, 24, true);
        
        return hash_equals(strtoupper(bin2hex($calculatedHash)), strtoupper($hashedValue));
    }

    /**
     * Vérifier si le hash doit être recalculé
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }

    /**
     * Obtenir des informations sur le hash
     */
    public function info($hashedValue)
    {
        return [
            'algo' => 'pbkdf2-sha1',
            'algoName' => 'PBKDF2-HMAC-SHA1',
            'options' => ['iterations' => 1000]
        ];
    }
}
