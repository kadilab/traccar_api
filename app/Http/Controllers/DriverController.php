<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Services\SecurityAuditService;

class DriverController extends Controller
{
    private string $traccarUrl;

    public function __construct()
    {
        $this->traccarUrl = config('traccar.url');
    }

    /**
     * Helper pour les requêtes API Traccar
     */
    private function apiRequest(string $method, string $endpoint, array $data = [], array $query = [])
    {
        $sessionId = Session::get('traccar_session_id');
        
        if ($sessionId) {
            $domain = parse_url($this->traccarUrl, PHP_URL_HOST);
            $http = Http::withCookies(['JSESSIONID' => $sessionId], $domain);
        } else {
            $credentials = Session::get('traccar_credentials');
            if ($credentials) {
                $decrypted = decrypt($credentials);
                $http = Http::withBasicAuth($decrypted['email'], $decrypted['password']);
            } else {
                $http = Http::withBasicAuth(config('traccar.username'), config('traccar.password'));
            }
        }

        $url = $this->traccarUrl . $endpoint;
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return match (strtoupper($method)) {
            'GET' => $http->get($url),
            'POST' => $http->post($url, $data),
            'PUT' => $http->put($url, $data),
            'DELETE' => $http->delete($url),
            default => $http->get($url),
        };
    }

    /**
     * Afficher la page de gestion des conducteurs
     */
    public function index()
    {
        return view('drivers.index');
    }

    /**
     * Récupérer tous les conducteurs (API) - Alias pour list
     */
    public function list(Request $request)
    {
        return $this->getDrivers($request);
    }

    /**
     * Récupérer tous les conducteurs (API)
     */
    public function getDrivers(Request $request)
    {
        try {
            $response = $this->apiRequest('GET', 'drivers');
            
            if ($response->successful()) {
                $drivers = $response->json() ?? [];
                
                // Récupérer les devices pour mapper les assignations
                $devicesResponse = $this->apiRequest('GET', 'devices');
                $devices = $devicesResponse->successful() ? $devicesResponse->json() : [];
                
                // Créer un mapping device ID -> device
                $deviceMap = collect($devices)->keyBy('id')->toArray();
                
                return response()->json([
                    'success' => true,
                    'drivers' => $drivers,
                    'devices' => $devices,
                    'deviceMap' => $deviceMap,
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des conducteurs',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Créer un nouveau conducteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'uniqueId' => 'required|string|max:255',
            'attributes' => 'nullable|array',
        ]);

        try {
            $driverData = [
                'name' => $validated['name'],
                'uniqueId' => $validated['uniqueId'],
                'attributes' => $validated['attributes'] ?? new \stdClass(),
            ];

            $response = $this->apiRequest('POST', 'drivers', $driverData);
            
            if ($response->successful()) {
                $driver = $response->json();
                
                SecurityAuditService::log('driver_created', [
                    'driver_id' => $driver['id'] ?? null,
                    'name' => $validated['name'],
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Conducteur créé avec succès',
                    'driver' => $driver,
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . ($response->json()['message'] ?? 'Erreur inconnue'),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupérer un conducteur spécifique
     */
    public function show($id)
    {
        try {
            $response = $this->apiRequest('GET', 'drivers', [], ['id' => $id]);
            
            if ($response->successful()) {
                $drivers = $response->json();
                $driver = collect($drivers)->firstWhere('id', (int) $id);
                
                if ($driver) {
                    return response()->json([
                        'success' => true,
                        'driver' => $driver,
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Conducteur non trouvé',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mettre à jour un conducteur
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'uniqueId' => 'required|string|max:255',
            'attributes' => 'nullable|array',
        ]);

        try {
            $driverData = [
                'id' => (int) $id,
                'name' => $validated['name'],
                'uniqueId' => $validated['uniqueId'],
                'attributes' => $validated['attributes'] ?? new \stdClass(),
            ];

            $response = $this->apiRequest('PUT', "drivers/{$id}", $driverData);
            
            if ($response->successful()) {
                $driver = $response->json();
                
                SecurityAuditService::log('driver_updated', [
                    'driver_id' => $id,
                    'name' => $validated['name'],
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Conducteur mis à jour avec succès',
                    'driver' => $driver,
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . ($response->json()['message'] ?? 'Erreur inconnue'),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Supprimer un conducteur
     */
    public function destroy($id)
    {
        try {
            $response = $this->apiRequest('DELETE', "drivers/{$id}");
            
            if ($response->successful() || $response->status() === 204) {
                SecurityAuditService::log('driver_deleted', ['driver_id' => $id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Conducteur supprimé avec succès',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Assigner un conducteur à un appareil
     */
    public function linkDevice(Request $request)
    {
        return $this->assignDevice($request);
    }

    /**
     * Retirer l'assignation d'un conducteur à un appareil
     */
    public function unlinkDevice(Request $request)
    {
        return $this->unassignDevice($request);
    }

    /**
     * Récupérer les appareils assignés à un conducteur
     */
    public function getDeviceLinks($id)
    {
        return $this->getDriverDevices($id);
    }

    /**
     * Assigner un conducteur à un appareil (internal)
     */
    private function assignDevice(Request $request)
    {
        $validated = $request->validate([
            'driverId' => 'required|integer',
            'deviceId' => 'required|integer',
        ]);

        try {
            $response = $this->apiRequest('POST', 'permissions', [
                'driverId' => $validated['driverId'],
                'deviceId' => $validated['deviceId'],
            ]);
            
            if ($response->successful() || $response->status() === 204) {
                SecurityAuditService::log('driver_device_assigned', $validated);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Conducteur assigné à l\'appareil avec succès',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'assignation: ' . ($response->json()['message'] ?? 'Erreur inconnue'),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retirer l'assignation d'un conducteur à un appareil (internal)
     */
    private function unassignDevice(Request $request)
    {
        $validated = $request->validate([
            'driverId' => 'required|integer',
            'deviceId' => 'required|integer',
        ]);

        try {
            $response = $this->apiRequest('DELETE', 'permissions', [
                'driverId' => $validated['driverId'],
                'deviceId' => $validated['deviceId'],
            ]);
            
            if ($response->successful() || $response->status() === 204) {
                SecurityAuditService::log('driver_device_unassigned', $validated);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Assignation retirée avec succès',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du retrait de l\'assignation',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupérer les appareils assignés à un conducteur (internal)
     */
    private function getDriverDevices($id)
    {
        try {
            $response = $this->apiRequest('GET', 'devices', [], ['driverId' => $id]);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'devices' => $response->json() ?? [],
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des appareils',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }
}
