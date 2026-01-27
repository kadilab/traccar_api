<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckManager
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur est administrateur ou manager Traccar
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Vérifier si l'utilisateur est désactivé
        if ($user->disabled) {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->with('error', 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.');
        }
        
        // Vérifier si le compte est expiré
        if ($user->isExpired()) {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')
                ->with('error', 'Votre compte a expiré. Veuillez contacter l\'administrateur.');
        }
        
        // Vérifier si l'utilisateur est admin ou manager
        if (!$user->isAdmin() && !$user->isManager()) {
            return redirect()->route('monitor')
                ->with('error', 'Accès réservé aux administrateurs et managers.');
        }

        return $next($request);
    }
}
