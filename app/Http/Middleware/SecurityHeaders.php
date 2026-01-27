<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * Ajoute des headers de sécurité HTTP pour protéger contre les attaques courantes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Protection contre le clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Protection XSS (bien que les navigateurs modernes le font par défaut)
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Empêche les navigateurs de détecter le type MIME
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Politique de référent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy - désactive les fonctionnalités non utilisées
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=(), camera=()');
        
        // Content Security Policy - Protège contre XSS et injection de données
        // Adapté pour permettre les ressources légitimes de l'application
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://unpkg.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https://*.tile.openstreetmap.org https://*.basemaps.cartocdn.com https://ui-avatars.com https://unpkg.com blob:",
            "connect-src 'self' wss: ws: https://cdn.jsdelivr.net https://unpkg.com",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "base-uri 'self'"
        ]);
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Strict Transport Security (HTTPS uniquement)
        // Activer seulement en production avec HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
