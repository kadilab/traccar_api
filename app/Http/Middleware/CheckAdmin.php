<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur est administrateur Traccar
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Vérifier si l'utilisateur est admin (colonne 'administrator' dans Traccar)
        // La valeur peut être 1, true, ou "true" selon la base de données
        $isAdmin = $user->administrator === true || 
                   $user->administrator === 1 || 
                   $user->administrator === '1' ||
                   $user->administrator === 'true';
        
        if (!$isAdmin) {
            // Rediriger vers le monitor si non-admin
            return redirect()->route('monitor')->with('error', 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
