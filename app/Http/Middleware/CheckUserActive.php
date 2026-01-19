<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur authentifié n'est pas désactivé
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si l'utilisateur est désactivé, le déconnecter immédiatement
            if ($user->disabled) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect('/login')
                    ->with('error', 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.');
            }
        }

        return $next($request);
    }
}
