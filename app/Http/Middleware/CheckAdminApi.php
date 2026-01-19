<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminApi
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur est administrateur pour les requêtes API
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié'
            ], 401);
        }

        $user = auth()->user();
        
        // Vérifier si l'utilisateur est désactivé
        if ($user->disabled) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte a été désactivé'
            ], 403);
        }
        
        // Vérifier si l'utilisateur est admin
        $isAdmin = $user->administrator === true || 
                   $user->administrator === 1 || 
                   $user->administrator === '1' ||
                   $user->administrator === 'true';
        
        if (!$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux administrateurs'
            ], 403);
        }

        return $next($request);
    }
}
