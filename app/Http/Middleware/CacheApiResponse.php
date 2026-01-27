<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheApiResponse
{
    /**
     * Cache les réponses API pour améliorer les performances
     * 
     * @param int $ttl Durée de vie du cache en secondes
     */
    public function handle(Request $request, Closure $next, int $ttl = 30): Response
    {
        // Ne cacher que les requêtes GET
        if (!$request->isMethod('GET')) {
            return $next($request);
        }
        
        // Ne pas cacher si l'utilisateur demande un refresh
        if ($request->header('Cache-Control') === 'no-cache') {
            return $next($request);
        }
        
        $cacheKey = $this->generateCacheKey($request);
        
        // Vérifier si la réponse est en cache
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            return response()->json($cached['data'], $cached['status'])
                ->withHeaders([
                    'X-Cache' => 'HIT',
                    'X-Cache-TTL' => $ttl,
                ]);
        }
        
        $response = $next($request);
        
        // Ne cacher que les réponses réussies
        if ($response->getStatusCode() === 200) {
            $content = json_decode($response->getContent(), true);
            
            Cache::put($cacheKey, [
                'data' => $content,
                'status' => $response->getStatusCode(),
            ], $ttl);
            
            $response->headers->set('X-Cache', 'MISS');
            $response->headers->set('X-Cache-TTL', $ttl);
        }
        
        return $response;
    }
    
    /**
     * Génère une clé de cache unique basée sur la requête et l'utilisateur
     */
    private function generateCacheKey(Request $request): string
    {
        $userId = auth()->id() ?? 'guest';
        $path = $request->path();
        $query = $request->query();
        ksort($query);
        
        return "api_cache:{$userId}:" . sha1($path . json_encode($query));
    }
}
