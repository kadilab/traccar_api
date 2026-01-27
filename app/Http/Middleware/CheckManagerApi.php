<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckManagerApi
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur est administrateur ou manager pour les requêtes API
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
        
        // Vérifier si le compte est expiré
        if ($user->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte a expiré'
            ], 403);
        }
        
        // Vérifier si l'utilisateur est admin ou manager
        if (!$user->isAdmin() && !$user->isManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux administrateurs et managers'
            ], 403);
        }

        return $next($request);
    }
}
