<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\UserDevice;

class TraccarController extends Controller
{
    private string $traccarUrl;

    public function __construct()
    {
        $this->traccarUrl = config('traccar.url');
    }

    /**
     * Obtenir le domaine de l'URL Traccar pour les cookies
     */
    private function getTraccarHost(): string
    {
        $parsed = parse_url($this->traccarUrl);
        return $parsed['host'] ?? 'localhost';
    }

    /**
     * Recréer la session Traccar avec les credentials stockés
     */
    private function refreshTraccarSession(): ?string
    {
        try {
            $encryptedCredentials = session('traccar_credentials');
            if (!$encryptedCredentials) {
                return null;
            }

            $credentials = decrypt($encryptedCredentials);
            
            $response = Http::asForm()->post($this->traccarUrl . 'session', [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]);

            if ($response->successful()) {
                $cookies = $response->cookies();
                foreach ($cookies as $cookie) {
                    if ($cookie->getName() === 'JSESSIONID') {
                        $sessionId = $cookie->getValue();
                        session(['traccar_session_id' => $sessionId]);
                        \Log::info('Session Traccar recréée avec succès');
                        return $sessionId;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Impossible de recréer la session Traccar: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Helper method pour effectuer les requêtes HTTP authentifiées
     * Utilise le cookie de session Traccar de l'utilisateur connecté
     */
    private function apiRequest(string $method, string $endpoint, array $data = [], array $query = [])
    {
        // Récupérer le cookie de session Traccar depuis la session Laravel
        $sessionId = session('traccar_session_id');
        $host = $this->getTraccarHost();
        
        if ($sessionId) {
            // Utiliser le cookie de session de l'utilisateur connecté
            $http = Http::withCookies(['JSESSIONID' => $sessionId], $host);
        } else {
            // Pas de session, essayer de la créer
            $sessionId = $this->refreshTraccarSession();
            if ($sessionId) {
                $http = Http::withCookies(['JSESSIONID' => $sessionId], $host);
            } else {
                // Fallback sur les credentials admin
                $http = Http::withBasicAuth(
                    config('traccar.username'),
                    config('traccar.password')
                );
            }
        }

        $url = $this->traccarUrl . $endpoint;

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $response = match (strtoupper($method)) {
            'GET' => $http->get($url),
            'POST' => $http->post($url, $data),
            'PUT' => $http->put($url, $data),
            'DELETE' => empty($data) ? $http->delete($url) : $http->delete($url, $data),
            default => $http->get($url),
        };

        // Si 401 avec session, la session a expiré - tenter de la recréer
        if ($response->status() === 401 && $sessionId) {
            \Log::warning('Session Traccar expirée, tentative de recréation');
            
            $newSessionId = $this->refreshTraccarSession();
            if ($newSessionId) {
                // Retry avec la nouvelle session
                $http = Http::withCookies(['JSESSIONID' => $newSessionId], $host);
                
                return match (strtoupper($method)) {
                    'GET' => $http->get($url),
                    'POST' => $http->post($url, $data),
                    'PUT' => $http->put($url, $data),
                    'DELETE' => empty($data) ? $http->delete($url) : $http->delete($url, $data),
                    default => $http->get($url),
                };
            }
        }

        return $response;
    }

    /**
     * Helper method pour formater la réponse
     */
    private function formatResponse($response, string $key = 'data'): JsonResponse
    {
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                $key => $response->json(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur API Traccar',
            'error' => $response->body()
        ], $response->status());
    }

    // ==================== HEALTH ====================

    /**
     * Check server health
     * GET /health
     */
    public function health(): JsonResponse
    {
        $response = Http::get($this->traccarUrl . 'health');
        return $this->formatResponse($response, 'status');
    }

    // ==================== SERVER ====================

    /**
     * Fetch Server information
     * GET /server
     */
    public function getServer(): JsonResponse
    {
        $response = $this->apiRequest('GET', 'server');
        return $this->formatResponse($response, 'server');
    }

    /**
     * Update Server information
     * PUT /server
     */
    public function updateServer(Request $request): JsonResponse
    {
        $response = $this->apiRequest('PUT', 'server', $request->all());
        return $this->formatResponse($response, 'server');
    }

    // ==================== SESSION ====================

    /**
     * Fetch Session information
     * GET /session
     */
    public function getSession(Request $request): JsonResponse
    {
        $query = $request->only(['token']);
        $response = $this->apiRequest('GET', 'session', [], $query);
        return $this->formatResponse($response, 'session');
    }

    /**
     * Create a new Session
     * POST /session
     */
    public function createSession(Request $request): JsonResponse
    {
        $response = Http::asForm()->post($this->traccarUrl . 'session', [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        return $this->formatResponse($response, 'session');
    }

    /**
     * Close the Session
     * DELETE /session
     */
    public function deleteSession(): JsonResponse
    {
        $response = $this->apiRequest('DELETE', 'session');
        return $this->formatResponse($response, 'session');
    }

    /**
     * Generate Session Token
     * POST /session/token
     */
    public function generateToken(Request $request): JsonResponse
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->asForm()
            ->post($this->traccarUrl . 'session/token', $request->all());
        return $this->formatResponse($response, 'token');
    }

    /**
     * Revoke Session Token
     * POST /session/token/revoke
     */
    public function revokeToken(Request $request): JsonResponse
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->asForm()
            ->post($this->traccarUrl . 'session/token/revoke', $request->all());
        return $this->formatResponse($response, 'result');
    }

    // ==================== DEVICES ====================

    /**
     * Fetch a list of Devices
     * GET /devices
     */
    public function getDevices(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'id', 'uniqueId']);
        $response = $this->apiRequest('GET', 'devices', [], $query);
        
        if ($response->successful()) {
            $devices = $response->json();
            $user = auth()->user();
            
            // Charger les assignations utilisateur-device depuis la table tc_user_device
            try {
                $userDevices = UserDevice::all();
                
                // Créer un mapping deviceId -> userId
                $deviceUserMap = [];
                foreach ($userDevices as $ud) {
                    $deviceUserMap[$ud->deviceid] = $ud->userid;
                }
                
                // Ajouter l'userId à chaque device
                if (is_array($devices)) {
                    foreach ($devices as &$device) {
                        $device['userId'] = $deviceUserMap[$device['id']] ?? null;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Erreur lors du chargement des assignations utilisateur-device: ' . $e->getMessage());
                // Continuer même si les assignations ne se chargent pas
                if (is_array($devices)) {
                    foreach ($devices as &$device) {
                        $device['userId'] = null;
                    }
                }
            }
            
            // Si l'utilisateur n'est pas admin, filtrer pour afficher seulement ses devices
            if (!$user->administrator) {
                $devices = array_filter($devices, function($device) use ($user) {
                    // Afficher le device si :
                    // 1. Il est assigné à l'utilisateur courant
                    // 2. Il est assigné à aucun utilisateur (devices non assignés visibles par tous)
                    return $device['userId'] === $user->id || $device['userId'] === null;
                });
            }
            
            return response()->json([
                'success' => true,
                'devices' => $devices
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur API Traccar',
            'error' => $response->body()
        ], $response->status());
    }

    /**
     * Fetch a single Device by ID
     * GET /devices/{id}
     */
    public function getDevice(int $id): JsonResponse
    {
        $user = auth()->user();
        
        // Si l'utilisateur n'est pas admin, vérifier qu'il a accès à ce device
        if (!$user->administrator) {
            $userDevice = UserDevice::where('userid', $user->id)
                ->where('deviceid', $id)
                ->first();
            
            if (!$userDevice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé à ce device',
                ], 403);
            }
        }
        
        $response = $this->apiRequest('GET', 'devices', [], ['id' => $id]);
        
        if ($response->successful()) {
            $devices = $response->json();
            
            // Extract the single device from array
            if (is_array($devices) && count($devices) > 0) {
                return response()->json([
                    'success' => true,
                    'device' => $devices[0]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur API Traccar',
            'error' => $response->body()
        ], $response->status());
    }

    /**
     * Create a Device
     * POST /devices
     */
    public function createDevice(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'devices', $request->all());
        return $this->formatResponse($response, 'device');
    }

    /**
     * Update a Device
     * PUT /devices/{id}
     */
    public function updateDevice(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "devices/{$id}", $request->all());
        return $this->formatResponse($response, 'device');
    }

    /**
     * Delete a Device
     * DELETE /devices/{id}
     */
    public function deleteDevice(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "devices/{$id}");
        return $this->formatResponse($response, 'result');
    }

    /**
     * Update total distance and hours of the Device
     * PUT /devices/{id}/accumulators
     */
    public function updateDeviceAccumulators(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "devices/{$id}/accumulators", $request->all());
        return $this->formatResponse($response, 'result');
    }

    // ==================== GROUPS ====================

    /**
     * Fetch a list of Groups
     * GET /groups
     */
    public function getGroups(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId']);
        $response = $this->apiRequest('GET', 'groups', [], $query);
        return $this->formatResponse($response, 'groups');
    }

    /**
     * Create a Group
     * POST /groups
     */
    public function createGroup(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'groups', $request->all());
        return $this->formatResponse($response, 'group');
    }

    /**
     * Update a Group
     * PUT /groups/{id}
     */
    public function updateGroup(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "groups/{$id}", $request->all());
        return $this->formatResponse($response, 'group');
    }

    /**
     * Delete a Group
     * DELETE /groups/{id}
     */
    public function deleteGroup(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "groups/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== USERS ====================

    /**
     * Fetch a list of Users
     * GET /users
     */
    public function getUsers(Request $request): JsonResponse
    {
        $query = $request->only(['userId']);
        $response = $this->apiRequest('GET', 'users', [], $query);
        return $this->formatResponse($response, 'users');
    }

    /**
     * Create a User
     * POST /users
     */
    public function createUser(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'users', $request->all());
        return $this->formatResponse($response, 'user');
    }

    /**
     * Update a User
     * PUT /users/{id}
     */
    public function updateUser(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "users/{$id}", $request->all());
        return $this->formatResponse($response, 'user');
    }

    /**
     * Delete a User
     * DELETE /users/{id}
     */
    public function deleteUser(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "users/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== PERMISSIONS ====================

    /**
     * Fetch all Permissions
     * GET /permissions
     */
    public function getPermissions(Request $request): JsonResponse
    {
        $response = $this->apiRequest('GET', 'permissions', [], []);
        return $this->formatResponse($response, 'permissions');
    }

    /**
     * Link an Object to another Object
     * POST /permissions
     */
    public function createPermission(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'permissions', $request->all());
        return $this->formatResponse($response, 'result');
    }

    /**
     * Unlink an Object from another Object
     * DELETE /permissions
     */
    public function deletePermission(Request $request): JsonResponse
    {
        $response = $this->apiRequest('DELETE', 'permissions', $request->all());
        return $this->formatResponse($response, 'result');
    }

    // ==================== POSITIONS ====================

    /**
     * Fetches a list of Positions
     * GET /positions
     */
    public function getPositions(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = $request->only(['deviceId', 'from', 'to', 'id']);
        
        // Si l'utilisateur n'est pas admin et a spécifié un deviceId
        if (!$user->administrator && isset($query['deviceId'])) {
            // Vérifier que l'utilisateur a accès à ce device
            $userDevice = UserDevice::where('userid', $user->id)
                ->where('deviceid', $query['deviceId'])
                ->first();
            
            // Si le device n'est pas assigné à cet utilisateur, refuser l'accès
            if (!$userDevice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé à ce device',
                ], 403);
            }
        }
        
        $response = $this->apiRequest('GET', 'positions', [], $query);
        return $this->formatResponse($response, 'positions');
    }

    /**
     * Deletes all the Positions of a device in the time span specified
     * DELETE /positions
     */
    public function deletePositions(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'from', 'to']);
        $response = Http::withBasicAuth($this->username, $this->password)
            ->delete($this->traccarUrl . 'positions?' . http_build_query($query));
        return $this->formatResponse($response, 'result');
    }

    /**
     * Delete a Position
     * DELETE /positions/{id}
     */
    public function deletePosition(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "positions/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== EVENTS ====================

    /**
     * Get event by id
     * GET /events/{id}
     */
    public function getEvent(int $id): JsonResponse
    {
        $response = $this->apiRequest('GET', "events/{$id}");
        return $this->formatResponse($response, 'event');
    }

    // ==================== REPORTS ====================

    /**
     * Fetch a list of Positions within the time period for the Devices or Groups
     * GET /reports/route
     */
    public function getReportRoute(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'groupId', 'from', 'to']);
        $response = $this->apiRequest('GET', 'reports/route', [], $query);
        return $this->formatResponse($response, 'route');
    }

    /**
     * Fetch a list of Events within the time period for the Devices or Groups
     * GET /reports/events
     */
    public function getReportEvents(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'groupId', 'type', 'from', 'to']);
        $response = $this->apiRequest('GET', 'reports/events', [], $query);
        return $this->formatResponse($response, 'events');
    }

    /**
     * Fetch a list of ReportSummary within the time period for the Devices or Groups
     * GET /reports/summary
     */
    public function getReportSummary(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'groupId', 'from', 'to']);
        $response = $this->apiRequest('GET', 'reports/summary', [], $query);
        return $this->formatResponse($response, 'summary');
    }

    /**
     * Fetch a list of ReportTrips within the time period for the Devices or Groups
     * GET /reports/trips
     */
    public function getReportTrips(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'groupId', 'from', 'to']);
        $response = $this->apiRequest('GET', 'reports/trips', [], $query);
        return $this->formatResponse($response, 'trips');
    }

    /**
     * Fetch a list of ReportStops within the time period for the Devices or Groups
     * GET /reports/stops
     */
    public function getReportStops(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'groupId', 'from', 'to']);
        $response = $this->apiRequest('GET', 'reports/stops', [], $query);
        return $this->formatResponse($response, 'stops');
    }

    // ==================== NOTIFICATIONS ====================

    /**
     * Fetch a list of Notifications
     * GET /notifications
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'deviceId', 'groupId', 'refresh']);
        $response = $this->apiRequest('GET', 'notifications', [], $query);
        return $this->formatResponse($response, 'notifications');
    }

    /**
     * Create a Notification
     * POST /notifications
     */
    public function createNotification(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'notifications', $request->all());
        return $this->formatResponse($response, 'notification');
    }

    /**
     * Update a Notification
     * PUT /notifications/{id}
     */
    public function updateNotification(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "notifications/{$id}", $request->all());
        return $this->formatResponse($response, 'notification');
    }

    /**
     * Delete a Notification
     * DELETE /notifications/{id}
     */
    public function deleteNotification(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "notifications/{$id}");
        return $this->formatResponse($response, 'result');
    }

    /**
     * Fetch a list of available Notification types
     * GET /notifications/types
     */
    public function getNotificationTypes(): JsonResponse
    {
        $response = $this->apiRequest('GET', 'notifications/types');
        return $this->formatResponse($response, 'types');
    }

    /**
     * Send test notification to current user via Email and SMS
     * POST /notifications/test
     */
    public function testNotification(): JsonResponse
    {
        $response = $this->apiRequest('POST', 'notifications/test');
        return $this->formatResponse($response, 'result');
    }

    /**
     * Send a custom notification to selected users using the specified notificator
     * POST /notifications/send/{notificator}
     */
    public function sendNotification(Request $request, string $notificator): JsonResponse
    {
        $query = $request->only(['userId']);
        $url = "notifications/send/{$notificator}";
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        $response = $this->apiRequest('POST', $url, $request->except(['userId']));
        return $this->formatResponse($response, 'result');
    }

    // ==================== GEOFENCES ====================

    /**
     * Fetch a list of Geofences
     * GET /geofences
     */
    public function getGeofences(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'deviceId', 'groupId', 'refresh']);
        $response = $this->apiRequest('GET', 'geofences', [], $query);
        return $this->formatResponse($response, 'geofences');
    }

    /**
     * Create a Geofence
     * POST /geofences
     * Attribue automatiquement la geofence à l'utilisateur créateur
     */
    public function createGeofence(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'geofences', $request->all());
        
        if ($response->successful()) {
            $geofence = $response->json();
            $geofenceId = $geofence['id'] ?? null;
            $userId = auth()->user()->id ?? null;
            
            // Attribuer automatiquement la geofence à l'utilisateur créateur
            if ($geofenceId && $userId) {
                $this->apiRequest('POST', 'permissions', [
                    'userId' => $userId,
                    'geofenceId' => $geofenceId
                ]);
            }
            
            return response()->json([
                'success' => true,
                'geofence' => $geofence,
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur API Traccar',
            'error' => $response->body()
        ], $response->status());
    }

    /**
     * Update a Geofence
     * PUT /geofences/{id}
     */
    public function updateGeofence(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "geofences/{$id}", $request->all());
        return $this->formatResponse($response, 'geofence');
    }

    /**
     * Delete a Geofence
     * DELETE /geofences/{id}
     */
    public function deleteGeofence(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "geofences/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== COMMANDS ====================

    /**
     * Fetch a list of Saved Commands
     * GET /commands
     */
    public function getCommands(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'deviceId', 'groupId', 'refresh']);
        $response = $this->apiRequest('GET', 'commands', [], $query);
        return $this->formatResponse($response, 'commands');
    }

    /**
     * Create a Saved Command
     * POST /commands
     */
    public function createCommand(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'commands', $request->all());
        return $this->formatResponse($response, 'command');
    }

    /**
     * Update a Saved Command
     * PUT /commands/{id}
     */
    public function updateCommand(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "commands/{$id}", $request->all());
        return $this->formatResponse($response, 'command');
    }

    /**
     * Delete a Saved Command
     * DELETE /commands/{id}
     */
    public function deleteCommand(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "commands/{$id}");
        return $this->formatResponse($response, 'result');
    }

    /**
     * Fetch a list of Saved Commands supported by Device at the moment
     * GET /commands/send
     */
    public function getCommandsSend(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId']);
        $response = $this->apiRequest('GET', 'commands/send', [], $query);
        return $this->formatResponse($response, 'commands');
    }

    /**
     * Dispatch commands to device
     * POST /commands/send
     */
    public function sendCommand(Request $request): JsonResponse
    {
        $query = $request->only(['groupId']);
        $url = 'commands/send';
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        $response = $this->apiRequest('POST', $url, $request->except(['groupId']));
        return $this->formatResponse($response, 'command');
    }

    /**
     * Fetch a list of available Commands for the Device
     * GET /commands/types
     */
    public function getCommandTypes(Request $request): JsonResponse
    {
        $query = $request->only(['deviceId', 'textChannel']);
        $response = $this->apiRequest('GET', 'commands/types', [], $query);
        return $this->formatResponse($response, 'types');
    }

    // ==================== STATISTICS ====================

    /**
     * Fetch server Statistics
     * GET /statistics
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $query = $request->only(['from', 'to']);
        $response = $this->apiRequest('GET', 'statistics', [], $query);
        return $this->formatResponse($response, 'statistics');
    }

    // ==================== CALENDARS ====================

    /**
     * Fetch a list of Calendars
     * GET /calendars
     */
    public function getCalendars(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId']);
        $response = $this->apiRequest('GET', 'calendars', [], $query);
        return $this->formatResponse($response, 'calendars');
    }

    /**
     * Create a Calendar
     * POST /calendars
     */
    public function createCalendar(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'calendars', $request->all());
        return $this->formatResponse($response, 'calendar');
    }

    /**
     * Update a Calendar
     * PUT /calendars/{id}
     */
    public function updateCalendar(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "calendars/{$id}", $request->all());
        return $this->formatResponse($response, 'calendar');
    }

    /**
     * Delete a Calendar
     * DELETE /calendars/{id}
     */
    public function deleteCalendar(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "calendars/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== ATTRIBUTES ====================

    /**
     * Fetch a list of Attributes
     * GET /attributes/computed
     */
    public function getAttributes(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'deviceId', 'groupId', 'refresh']);
        $response = $this->apiRequest('GET', 'attributes/computed', [], $query);
        return $this->formatResponse($response, 'attributes');
    }

    /**
     * Create an Attribute
     * POST /attributes/computed
     */
    public function createAttribute(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'attributes/computed', $request->all());
        return $this->formatResponse($response, 'attribute');
    }

    /**
     * Update an Attribute
     * PUT /attributes/computed/{id}
     */
    public function updateAttribute(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "attributes/computed/{$id}", $request->all());
        return $this->formatResponse($response, 'attribute');
    }

    /**
     * Delete an Attribute
     * DELETE /attributes/computed/{id}
     */
    public function deleteAttribute(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "attributes/computed/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== DRIVERS ====================

    /**
     * Fetch a list of Drivers
     * GET /drivers
     */
    public function getDrivers(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'deviceId', 'groupId', 'refresh']);
        $response = $this->apiRequest('GET', 'drivers', [], $query);
        return $this->formatResponse($response, 'drivers');
    }

    /**
     * Create a Driver
     * POST /drivers
     */
    public function createDriver(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'drivers', $request->all());
        return $this->formatResponse($response, 'driver');
    }

    /**
     * Update a Driver
     * PUT /drivers/{id}
     */
    public function updateDriver(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "drivers/{$id}", $request->all());
        return $this->formatResponse($response, 'driver');
    }

    /**
     * Delete a Driver
     * DELETE /drivers/{id}
     */
    public function deleteDriver(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "drivers/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== MAINTENANCE ====================

    /**
     * Fetch a list of Maintenance
     * GET /maintenance
     */
    public function getMaintenance(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'deviceId', 'groupId', 'refresh']);
        $response = $this->apiRequest('GET', 'maintenance', [], $query);
        return $this->formatResponse($response, 'maintenance');
    }

    /**
     * Create a Maintenance
     * POST /maintenance
     */
    public function createMaintenance(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'maintenance', $request->all());
        return $this->formatResponse($response, 'maintenance');
    }

    /**
     * Update a Maintenance
     * PUT /maintenance/{id}
     */
    public function updateMaintenance(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "maintenance/{$id}", $request->all());
        return $this->formatResponse($response, 'maintenance');
    }

    /**
     * Delete a Maintenance
     * DELETE /maintenance/{id}
     */
    public function deleteMaintenance(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "maintenance/{$id}");
        return $this->formatResponse($response, 'result');
    }

    // ==================== ORDERS ====================

    /**
     * Fetch a list of Orders
     * GET /orders
     */
    public function getOrders(Request $request): JsonResponse
    {
        $query = $request->only(['all', 'userId', 'excludeAttributes']);
        $response = $this->apiRequest('GET', 'orders', [], $query);
        return $this->formatResponse($response, 'orders');
    }

    /**
     * Create an Order
     * POST /orders
     */
    public function createOrder(Request $request): JsonResponse
    {
        $response = $this->apiRequest('POST', 'orders', $request->all());
        return $this->formatResponse($response, 'order');
    }

    /**
     * Update an Order
     * PUT /orders/{id}
     */
    public function updateOrder(Request $request, int $id): JsonResponse
    {
        $response = $this->apiRequest('PUT', "orders/{$id}", $request->all());
        return $this->formatResponse($response, 'order');
    }

    /**
     * Delete an Order
     * DELETE /orders/{id}
     */
    public function deleteOrder(int $id): JsonResponse
    {
        $response = $this->apiRequest('DELETE', "orders/{$id}");
        return $this->formatResponse($response, 'result');
    }
}