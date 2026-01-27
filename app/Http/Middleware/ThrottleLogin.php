<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    protected RateLimiter $limiter;
    
    /**
     * Nombre maximum de tentatives de connexion
     */
    protected int $maxAttempts = 5;
    
    /**
     * Durée de blocage en minutes après dépassement
     */
    protected int $decayMinutes = 15;
    
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     * Limite les tentatives de connexion pour prévenir les attaques brute-force
     */
    public function handle(Request $request, Closure $next): Response
    {
        // N'appliquer que pour les requêtes POST de login
        if (!$this->isLoginAttempt($request)) {
            return $next($request);
        }
        
        $key = $this->throttleKey($request);
        
        if ($this->limiter->tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            $minutes = ceil($seconds / 60);
            
            return $this->lockoutResponse($minutes);
        }
        
        $response = $next($request);
        
        // Si la connexion a échoué (pas de redirection), incrémenter le compteur
        if ($response->getStatusCode() === 422 || $response->getStatusCode() === 401) {
            $this->limiter->hit($key, $this->decayMinutes * 60);
        } else {
            // Connexion réussie, effacer les tentatives
            $this->limiter->clear($key);
        }
        
        return $response;
    }
    
    /**
     * Vérifie si c'est une tentative de connexion
     */
    protected function isLoginAttempt(Request $request): bool
    {
        return $request->isMethod('POST') && 
               ($request->is('login') || $request->routeIs('login'));
    }
    
    /**
     * Génère une clé unique pour le throttling basée sur l'email et l'IP
     */
    protected function throttleKey(Request $request): string
    {
        $email = strtolower($request->input('email', ''));
        $ip = $request->ip();
        
        return sha1("login|{$email}|{$ip}");
    }
    
    /**
     * Réponse de blocage
     */
    protected function lockoutResponse(int $minutes): Response
    {
        return redirect()->back()
            ->withInput()
            ->withErrors([
                'email' => "Trop de tentatives de connexion. Veuillez réessayer dans {$minutes} minute(s)."
            ]);
    }
}
