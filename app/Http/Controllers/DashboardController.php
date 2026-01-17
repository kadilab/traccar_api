<?php

namespace App\Http\Controllers;

use App\Services\TraccarService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private TraccarService $traccarService;

    public function __construct(TraccarService $traccarService)
    {
        $this->traccarService = $traccarService;
    }

    /**
     * Afficher le dashboard avec les statistiques
     */
    public function index()
    {
        try {
            $stats = $this->traccarService->getDashboardStats();
            
            return view('dashboard', [
                'totalDevices' => $stats['totalDevices'],
                'onlineCount' => $stats['onlineCount'],
                'offlineCount' => $stats['offlineCount'],
                'activeCount' => $stats['activeCount'],
                'inactiveCount' => $stats['inactiveCount'],
                'expiredCount' => $stats['expiredCount'],
                'expiringCount' => $stats['expiringCount'],
                'alertingCount' => $stats['alertingCount'],
                'followingCount' => $stats['followingCount'],
                'stockCount' => $stats['stockCount'],
                'geofenceCount' => $stats['geofenceCount'],
                'userCount' => $stats['userCount'],
                'groupCount' => $stats['groupCount'],
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, afficher le dashboard avec des valeurs par défaut
            return view('dashboard', [
                'totalDevices' => 0,
                'onlineCount' => 0,
                'offlineCount' => 0,
                'activeCount' => 0,
                'inactiveCount' => 0,
                'expiredCount' => 0,
                'expiringCount' => 0,
                'alertingCount' => 0,
                'followingCount' => 0,
                'stockCount' => 0,
                'geofenceCount' => 0,
                'userCount' => 0,
                'groupCount' => 0,
                'error' => 'Impossible de récupérer les statistiques: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Rafraîchir les statistiques via AJAX
     */
    public function refreshStats()
    {
        try {
            $stats = $this->traccarService->refreshDashboardStats();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API endpoint pour récupérer les stats
     */
    public function getStats()
    {
        try {
            $stats = $this->traccarService->getDashboardStats();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }
}
