<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TraccarService
{
    private string $traccarUrl;
    private ?string $username = null;
    private ?string $password = null;

    public function __construct()
    {
        $this->traccarUrl = config('traccar.url');
        $this->loadUserCredentials();
    }

    /**
     * Charger les identifiants de l'utilisateur connecté depuis la session
     */
    private function loadUserCredentials(): void
    {
        // Récupérer les credentials stockés lors du login
        $credentials = Session::get('traccar_credentials');
        
        if ($credentials) {
            try {
                $decrypted = decrypt($credentials);
                $this->username = $decrypted['email'] ?? null;
                $this->password = $decrypted['password'] ?? null;
            } catch (\Exception $e) {
                \Log::warning('Impossible de décrypter les credentials Traccar: ' . $e->getMessage());
            }
        }
        
        // Fallback vers les credentials du .env si pas d'utilisateur connecté
        if (!$this->username || !$this->password) {
            $this->username = config('traccar.username');
            $this->password = config('traccar.password');
        }
    }

    /**
     * Helper method pour effectuer les requêtes HTTP authentifiées
     */
    private function apiRequest(string $method, string $endpoint, array $data = [], array $query = [])
    {
        // Utiliser la session Traccar si disponible
        $sessionId = Session::get('traccar_session_id');
        
        if ($sessionId) {
            // Utiliser le cookie de session Traccar
            $domain = parse_url($this->traccarUrl, PHP_URL_HOST);
            $http = Http::withCookies(['JSESSIONID' => $sessionId], $domain);
        } else {
            // Fallback vers Basic Auth
            $http = Http::withBasicAuth($this->username, $this->password);
        }

        $url = $this->traccarUrl . $endpoint;

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return match (strtoupper($method)) {
            'GET' => $http->get($url),
            'POST' => $http->post($url, $data),
            'PUT' => $http->put($url, $data),
            'DELETE' => empty($data) ? $http->delete($url) : $http->delete($url, $data),
            default => $http->get($url),
        };
    }

    /**
     * Récupérer tous les devices
     */
    public function getDevices(): array
    {
        $response = $this->apiRequest('GET', 'devices');
        
        if ($response->successful()) {
            return $response->json() ?? [];
        }
        
        return [];
    }

    /**
     * Récupérer les positions actuelles
     */
    public function getPositions(): array
    {
        $response = $this->apiRequest('GET', 'positions');
        
        if ($response->successful()) {
            return $response->json() ?? [];
        }
        
        return [];
    }

    /**
     * Récupérer tous les geofences
     */
    public function getGeofences(): array
    {
        $response = $this->apiRequest('GET', 'geofences');
        
        if ($response->successful()) {
            return $response->json() ?? [];
        }
        
        return [];
    }

    /**
     * Récupérer toutes les alertes/notifications
     */
    public function getNotifications(): array
    {
        $response = $this->apiRequest('GET', 'notifications');
        
        if ($response->successful()) {
            return $response->json() ?? [];
        }
        
        return [];
    }

    /**
     * Récupérer les utilisateurs
     */
    public function getUsers(): array
    {
        $response = $this->apiRequest('GET', 'users');
        
        if ($response->successful()) {
            return $response->json() ?? [];
        }
        
        return [];
    }

    /**
     * Récupérer les groupes
     */
    public function getGroups(): array
    {
        $response = $this->apiRequest('GET', 'groups');
        
        if ($response->successful()) {
            return $response->json() ?? [];
        }
        
        return [];
    }

    /**
     * Récupérer les statistiques du dashboard
     */
    public function getDashboardStats(): array
    {
        // Clé de cache unique par utilisateur
        $userId = Auth::id() ?? 'guest';
        $cacheKey = 'dashboard_stats_' . $userId;
        
        // Mise en cache des statistiques pour 1 minute
        return Cache::remember($cacheKey, 60, function () {
            $devices = $this->getDevices();
            $positions = $this->getPositions();
            $geofences = $this->getGeofences();
            $notifications = $this->getNotifications();
            $users = $this->getUsers();
            $groups = $this->getGroups();
            
            // Créer un map des positions par deviceId
            $positionsByDevice = collect($positions)->keyBy('deviceId');
            
            // Calculer les statistiques
            $totalDevices = count($devices);
            $onlineCount = 0;
            $offlineCount = 0;
            $activeCount = 0;
            $inactiveCount = 0;
            $expiredCount = 0;
            $expiringCount = 0;
            $alertingCount = 0;
            $followingCount = 0;
            $stockCount = 0;

            $now = now();

            foreach ($devices as $device) {
                // Status online/offline basé sur la dernière position
                $status = $device['status'] ?? 'unknown';
                
                if ($status === 'online') {
                    $onlineCount++;
                } else {
                    $offlineCount++;
                }
                
                // Disabled = inactive
                $disabled = $device['disabled'] ?? false;
                if ($disabled) {
                    $inactiveCount++;
                } else {
                    $activeCount++;
                }
                
                // Vérification de l'expiration
                $expirationTime = $device['expirationTime'] ?? null;
                if ($expirationTime) {
                    $expDate = \Carbon\Carbon::parse($expirationTime);
                    if ($expDate->isPast()) {
                        $expiredCount++;
                    } elseif ($expDate->diffInDays($now) <= 30) {
                        $expiringCount++;
                    }
                }
                
                // Catégorie = stock si pas de position ou jamais connecté
                $lastUpdate = $device['lastUpdate'] ?? null;
                if (!$lastUpdate) {
                    $stockCount++;
                }
                
                // Following - vérifier les attributs
                $attributes = $device['attributes'] ?? [];
                if (isset($attributes['following']) && $attributes['following']) {
                    $followingCount++;
                }
            }

            // Alerting count basé sur les notifications actives
            $alertingCount = collect($notifications)->filter(function ($notification) {
                return ($notification['always'] ?? false) || ($notification['alarms'] ?? false);
            })->count();

            return [
                'totalDevices' => $totalDevices,
                'onlineCount' => $onlineCount,
                'offlineCount' => $offlineCount,
                'activeCount' => $activeCount,
                'inactiveCount' => $inactiveCount,
                'expiredCount' => $expiredCount,
                'expiringCount' => $expiringCount,
                'alertingCount' => $alertingCount,
                'followingCount' => $followingCount,
                'stockCount' => $stockCount,
                'geofenceCount' => count($geofences),
                'userCount' => count($users),
                'groupCount' => count($groups),
            ];
        });
    }

    /**
     * Rafraîchir les statistiques (invalider le cache)
     */
    public function refreshDashboardStats(): array
    {
        $userId = Auth::id() ?? 'guest';
        $cacheKey = 'dashboard_stats_' . $userId;
        Cache::forget($cacheKey);
        return $this->getDashboardStats();
    }
}
