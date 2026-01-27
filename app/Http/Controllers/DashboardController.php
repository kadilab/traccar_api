<?php

namespace App\Http\Controllers;

use App\Services\TraccarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            $serverStatus = $this->getServerStatus();
            
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
                'serverStatus' => $serverStatus,
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
                'serverStatus' => $this->getServerStatus(),
                'error' => 'Impossible de récupérer les statistiques: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Obtenir le statut du serveur Traccar
     */
    private function getServerStatus(): array
    {
        $status = [
            'online' => false,
            'version' => 'N/A',
            'uptime' => 'N/A',
            'cpu' => 0,
            'memory' => 0,
            'disk' => 0,
            'lastCheck' => now()->format('H:i:s'),
        ];

        try {
            $traccarUrl = config('traccar.url');
            $startTime = microtime(true);
            
            $response = Http::timeout(5)->get($traccarUrl . 'server');
            
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            if ($response->successful()) {
                $serverInfo = $response->json();
                $status['online'] = true;
                $status['version'] = $serverInfo['version'] ?? 'N/A';
                $status['responseTime'] = $responseTime;
                
                // Récupérer les infos système du serveur local
                $status['cpu'] = $this->getCpuUsage();
                $status['memory'] = $this->getMemoryUsage();
                $status['disk'] = $this->getDiskUsage();
                $status['uptime'] = $this->getUptime();
            }
        } catch (\Exception $e) {
            $status['error'] = $e->getMessage();
        }

        return $status;
    }

    /**
     * Obtenir l'utilisation CPU
     */
    private function getCpuUsage(): float
    {
        if (PHP_OS_FAMILY === 'Linux') {
            $load = sys_getloadavg();
            $cores = (int) shell_exec('nproc') ?: 1;
            return min(100, round(($load[0] / $cores) * 100, 1));
        }
        return 0;
    }

    /**
     * Obtenir l'utilisation mémoire
     */
    private function getMemoryUsage(): float
    {
        if (PHP_OS_FAMILY === 'Linux') {
            $memInfo = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $memInfo, $total);
            preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $available);
            
            if (isset($total[1]) && isset($available[1])) {
                $totalMem = (int) $total[1];
                $availableMem = (int) $available[1];
                return round((($totalMem - $availableMem) / $totalMem) * 100, 1);
            }
        }
        return 0;
    }

    /**
     * Obtenir l'utilisation disque
     */
    private function getDiskUsage(): float
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        if ($total > 0) {
            return round((($total - $free) / $total) * 100, 1);
        }
        return 0;
    }

    /**
     * Obtenir l'uptime du serveur
     */
    private function getUptime(): string
    {
        if (PHP_OS_FAMILY === 'Linux' && file_exists('/proc/uptime')) {
            $uptime = (float) file_get_contents('/proc/uptime');
            $days = floor($uptime / 86400);
            $hours = floor(($uptime % 86400) / 3600);
            $minutes = floor(($uptime % 3600) / 60);
            
            if ($days > 0) {
                return "{$days}j {$hours}h {$minutes}m";
            } elseif ($hours > 0) {
                return "{$hours}h {$minutes}m";
            }
            return "{$minutes}m";
        }
        return 'N/A';
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

    /**
     * API endpoint pour le statut du serveur
     */
    public function getServerStatusApi()
    {
        return response()->json([
            'success' => true,
            'status' => $this->getServerStatus(),
        ]);
    }
}
