@extends('layouts.app')

@section('title', 'Dashboard - GeoTrack Pro')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
:root {
    --primary: #1e88e5;
    --secondary: #7556D6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #06b6d4;
    --dark: #0f172a;
    --gray-100: #f8fafc;
    --gray-200: #e2e8f0;
    --gray-600: #64748b;
}

.dashboard-container {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
    min-height: calc(100vh - 60px);
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.dashboard-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark);
}

.dashboard-title span {
    color: var(--primary);
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.refresh-btn {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.refresh-btn.spinning i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Server Status Card */
.server-status-card {
    background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 2rem;
    align-items: center;
    margin-top: 50px;
}

.server-indicator {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.server-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
}

.server-info h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.server-info p {
    font-size: 0.85rem;
    opacity: 0.7;
    margin: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.5rem;
}

.status-badge.online {
    background: rgba(16, 185, 129, 0.2);
    color: #34d399;
}

.status-badge.offline {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-dot.online { background: #34d399; }
.status-dot.offline { background: #f87171; }

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.server-metrics {
    display: flex;
    gap: 2rem;
}

.metric-item {
    text-align: center;
}

.metric-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.metric-label {
    font-size: 0.75rem;
    opacity: 0.7;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metric-bar {
    width: 80px;
    height: 6px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
    margin-top: 6px;
    overflow: hidden;
}

.metric-bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.5s ease;
}

.metric-bar-fill.cpu { background: linear-gradient(90deg, #10b981, #34d399); }
.metric-bar-fill.memory { background: linear-gradient(90deg, #1e88e5, #60a5fa); }
.metric-bar-fill.disk { background: linear-gradient(90deg, #7556D6, #a78bfa); }

.server-uptime {
    text-align: right;
}

.uptime-value {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #10b981, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.uptime-label {
    font-size: 0.85rem;
    opacity: 0.7;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    border: 1px solid transparent;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--primary);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.stat-icon.devices { background: linear-gradient(135deg, #1e88e5, #42a5f5); }
.stat-icon.online { background: linear-gradient(135deg, #10b981, #34d399); }
.stat-icon.offline { background: linear-gradient(135deg, #ef4444, #f87171); }
.stat-icon.users { background: linear-gradient(135deg, #7556D6, #a78bfa); }
.stat-icon.groups { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
.stat-icon.geofences { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
.stat-icon.expired { background: linear-gradient(135deg, #dc2626, #ef4444); }
.stat-icon.expiring { background: linear-gradient(135deg, #ea580c, #fb923c); }

.stat-content h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.stat-content p {
    font-size: 0.85rem;
    color: var(--gray-600);
    margin: 0;
}

.stat-trend {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
}

.stat-trend.up { color: var(--success); }
.stat-trend.down { color: var(--danger); }

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.chart-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
}

.chart-actions {
    display: flex;
    gap: 0.5rem;
}

.chart-period-btn {
    background: var(--gray-100);
    border: none;
    border-radius: 6px;
    padding: 4px 12px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s ease;
}

.chart-period-btn.active,
.chart-period-btn:hover {
    background: var(--primary);
    color: white;
}

.chart-container {
    height: 280px;
    position: relative;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.action-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    text-decoration: none;
    color: var(--dark);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.action-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 10px 25px rgba(30, 136, 229, 0.15);
}

.action-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-size: 1.5rem;
    color: white;
}

.action-icon.monitor { background: linear-gradient(135deg, #1e88e5, #42a5f5); }
.action-icon.devices { background: linear-gradient(135deg, #7556D6, #a78bfa); }
.action-icon.geofence { background: linear-gradient(135deg, #10b981, #34d399); }
.action-icon.reports { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
.action-icon.users { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
.action-icon.alerts { background: linear-gradient(135deg, #ef4444, #f87171); }

.action-card h5 {
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0;
}

.action-card p {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin: 0.25rem 0 0;
}

/* Bottom Section */
.bottom-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.info-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.info-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.info-card-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-card-title i {
    color: var(--primary);
}

.device-status-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.device-status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--gray-100);
    border-radius: 10px;
}

.device-status-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.device-status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.device-status-dot.online { background: var(--success); }
.device-status-dot.offline { background: var(--danger); }
.device-status-dot.idle { background: var(--warning); }
.device-status-dot.expired { background: var(--gray-600); }

.device-status-name {
    font-weight: 500;
    color: var(--dark);
}

.device-status-count {
    font-weight: 700;
    color: var(--dark);
}

/* Error Banner */
.error-banner {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.error-banner i {
    font-size: 1.25rem;
}

/* Responsive */
@media (max-width: 1400px) {
    .stats-grid { grid-template-columns: repeat(4, 1fr); }
    .quick-actions { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 1200px) {
    .charts-section { grid-template-columns: 1fr; }
    .server-status-card { grid-template-columns: 1fr; gap: 1rem; text-align: center; }
    .server-metrics { justify-content: center; }
    .server-uptime { text-align: center; }
}

@media (max-width: 992px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .bottom-section { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
    .dashboard-header { flex-direction: column; gap: 1rem; align-items: flex-start; }
}

@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr; }
    .quick-actions { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <!-- <div class="dashboard-header">
        <h1 class="dashboard-title">Tableau de <span>bord</span></h1>
        <div class="header-actions">
            <span class="text-muted" id="last-update">Dernière mise à jour: {{ now()->format('H:i:s') }}</span>
            <button class="refresh-btn" id="refresh-stats-btn">
                <i class="fas fa-sync-alt"></i>
                Actualiser
            </button>
        </div>
    </div> -->

    @if(isset($error))
    <div class="error-banner">
        <i class="fas fa-exclamation-triangle"></i>
        <span>{{ $error }}</span>
    </div>
    @endif

    <!-- Server Status -->
    <div class="server-status-card">
        <div class="server-indicator">
            <div class="server-icon">
                <i class="fas fa-server"></i>
            </div>
            <div class="server-info">
                <h3>Serveur Traccar</h3>
                <p>Version: {{ $serverStatus['version'] ?? 'N/A' }}</p>
                <div class="status-badge {{ ($serverStatus['online'] ?? false) ? 'online' : 'offline' }}">
                    <span class="status-dot {{ ($serverStatus['online'] ?? false) ? 'online' : 'offline' }}"></span>
                    {{ ($serverStatus['online'] ?? false) ? 'En ligne' : 'Hors ligne' }}
                </div>
            </div>
        </div>
        
        <div class="server-metrics">
            <div class="metric-item">
                <div class="metric-value" id="cpu-value">{{ $serverStatus['cpu'] ?? 0 }}%</div>
                <div class="metric-label">CPU</div>
                <div class="metric-bar">
                    <div class="metric-bar-fill cpu" style="width: {{ $serverStatus['cpu'] ?? 0 }}%"></div>
                </div>
            </div>
            <div class="metric-item">
                <div class="metric-value" id="memory-value">{{ $serverStatus['memory'] ?? 0 }}%</div>
                <div class="metric-label">Mémoire</div>
                <div class="metric-bar">
                    <div class="metric-bar-fill memory" style="width: {{ $serverStatus['memory'] ?? 0 }}%"></div>
                </div>
            </div>
            <div class="metric-item">
                <div class="metric-value" id="disk-value">{{ $serverStatus['disk'] ?? 0 }}%</div>
                <div class="metric-label">Disque</div>
                <div class="metric-bar">
                    <div class="metric-bar-fill disk" style="width: {{ $serverStatus['disk'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="server-uptime">
            <div class="uptime-value" id="uptime-value">{{ $serverStatus['uptime'] ?? 'N/A' }}</div>
            <div class="uptime-label">Temps de fonctionnement</div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <a href="{{ route('device') }}" class="stat-card">
            <div class="stat-icon devices">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-devices">{{ $totalDevices ?? 0 }}</h4>
                <p>Appareils totaux</p>
            </div>
        </a>
        
        <a href="{{ route('monitor') }}" class="stat-card">
            <div class="stat-icon online">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-online">{{ $onlineCount ?? 0 }}</h4>
                <p>En ligne</p>
                <span class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    Actifs maintenant
                </span>
            </div>
        </a>
        
        <a href="#" class="stat-card">
            <div class="stat-icon offline">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-offline">{{ $offlineCount ?? 0 }}</h4>
                <p>Hors ligne</p>
            </div>
        </a>
        
        <a href="{{ route('account') }}" class="stat-card">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-users">{{ $userCount ?? 0 }}</h4>
                <p>Utilisateurs</p>
            </div>
        </a>
        
        <a href="{{ route('groupe') }}" class="stat-card">
            <div class="stat-icon groups">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-groups">{{ $groupCount ?? 0 }}</h4>
                <p>Groupes</p>
            </div>
        </a>
        
        <a href="{{ route('geofence') }}" class="stat-card">
            <div class="stat-icon geofences">
                <i class="fas fa-draw-polygon"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-geofences">{{ $geofenceCount ?? 0 }}</h4>
                <p>Géobarrières</p>
            </div>
        </a>
        
        <a href="#" class="stat-card">
            <div class="stat-icon expiring">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-expiring">{{ $expiringCount ?? 0 }}</h4>
                <p>Expirant bientôt</p>
                <span class="stat-trend down">
                    <i class="fas fa-exclamation-circle"></i>
                    Attention requise
                </span>
            </div>
        </a>
        
        <a href="#" class="stat-card">
            <div class="stat-icon expired">
                <i class="fas fa-calendar-times"></i>
            </div>
            <div class="stat-content">
                <h4 id="stat-expired">{{ $expiredCount ?? 0 }}</h4>
                <p>Expirés</p>
            </div>
        </a>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Activité des appareils</h3>
                <div class="chart-actions">
                    <button class="chart-period-btn active" data-period="week">7 jours</button>
                    <button class="chart-period-btn" data-period="month">30 jours</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
        
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Répartition des appareils</h3>
            </div>
            <div class="chart-container">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('monitor') }}" class="action-card">
            <div class="action-icon monitor">
                <i class="fas fa-desktop"></i>
            </div>
            <h5>Moniteur</h5>
            <p>Suivi en temps réel</p>
        </a>
        
        <a href="{{ route('device') }}" class="action-card">
            <div class="action-icon devices">
                <i class="fas fa-microchip"></i>
            </div>
            <h5>Appareils</h5>
            <p>Gérer les trackers</p>
        </a>
        
        <a href="{{ route('geofence') }}" class="action-card">
            <div class="action-icon geofence">
                <i class="fas fa-draw-polygon"></i>
            </div>
            <h5>Géobarrières</h5>
            <p>Zones de surveillance</p>
        </a>
        
        <a href="{{ route('reports') }}" class="action-card">
            <div class="action-icon reports">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h5>Rapports</h5>
            <p>Analyses détaillées</p>
        </a>
        
        <a href="{{ route('account') }}" class="action-card">
            <div class="action-icon users">
                <i class="fas fa-users-cog"></i>
            </div>
            <h5>Utilisateurs</h5>
            <p>Gestion des comptes</p>
        </a>
        
        <a href="{{ route('alerts.index') }}" class="action-card">
            <div class="action-icon alerts">
                <i class="fas fa-bell"></i>
            </div>
            <h5>Alertes</h5>
            <p>Notifications</p>
        </a>
    </div>

    <!-- Bottom Section -->
    <div class="bottom-section">
        <div class="info-card">
            <div class="info-card-header">
                <h4 class="info-card-title">
                    <i class="fas fa-signal"></i>
                    État des appareils
                </h4>
            </div>
            <div class="device-status-list">
                <div class="device-status-item">
                    <div class="device-status-info">
                        <span class="device-status-dot online"></span>
                        <span class="device-status-name">En ligne</span>
                    </div>
                    <span class="device-status-count" id="status-online">{{ $onlineCount ?? 0 }}</span>
                </div>
                <div class="device-status-item">
                    <div class="device-status-info">
                        <span class="device-status-dot offline"></span>
                        <span class="device-status-name">Hors ligne</span>
                    </div>
                    <span class="device-status-count" id="status-offline">{{ $offlineCount ?? 0 }}</span>
                </div>
                <div class="device-status-item">
                    <div class="device-status-info">
                        <span class="device-status-dot idle"></span>
                        <span class="device-status-name">Inactif</span>
                    </div>
                    <span class="device-status-count" id="status-inactive">{{ $inactiveCount ?? 0 }}</span>
                </div>
                <div class="device-status-item">
                    <div class="device-status-info">
                        <span class="device-status-dot expired"></span>
                        <span class="device-status-name">Expirés</span>
                    </div>
                    <span class="device-status-count" id="status-expired">{{ $expiredCount ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-header">
                <h4 class="info-card-title">
                    <i class="fas fa-info-circle"></i>
                    Informations système
                </h4>
            </div>
            <div class="device-status-list">
                <div class="device-status-item">
                    <span class="device-status-name">Version Traccar</span>
                    <span class="device-status-count">{{ $serverStatus['version'] ?? 'N/A' }}</span>
                </div>
                <div class="device-status-item">
                    <span class="device-status-name">Temps de réponse</span>
                    <span class="device-status-count">{{ $serverStatus['responseTime'] ?? 'N/A' }} ms</span>
                </div>
                <div class="device-status-item">
                    <span class="device-status-name">Dernière vérification</span>
                    <span class="device-status-count">{{ $serverStatus['lastCheck'] ?? now()->format('H:i:s') }}</span>
                </div>
                <div class="device-status-item">
                    <span class="device-status-name">Stock disponible</span>
                    <span class="device-status-count" id="status-stock">{{ $stockCount ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js - Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'En ligne',
                data: [{{ $onlineCount ?? 0 }}, {{ ($onlineCount ?? 0) + 2 }}, {{ ($onlineCount ?? 0) - 1 }}, {{ $onlineCount ?? 0 }}, {{ ($onlineCount ?? 0) + 3 }}, {{ ($onlineCount ?? 0) + 1 }}, {{ $onlineCount ?? 0 }}],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Hors ligne',
                data: [{{ $offlineCount ?? 0 }}, {{ ($offlineCount ?? 0) - 1 }}, {{ ($offlineCount ?? 0) + 2 }}, {{ $offlineCount ?? 0 }}, {{ ($offlineCount ?? 0) - 2 }}, {{ ($offlineCount ?? 0) + 1 }}, {{ $offlineCount ?? 0 }}],
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Chart.js - Distribution Chart
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    const distributionChart = new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['En ligne', 'Hors ligne', 'Inactif', 'Expiré'],
            datasets: [{
                data: [{{ $onlineCount ?? 0 }}, {{ $offlineCount ?? 0 }}, {{ $inactiveCount ?? 0 }}, {{ $expiredCount ?? 0 }}],
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#64748b'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                }
            }
        }
    });

    // Refresh Button
    const refreshBtn = document.getElementById('refresh-stats-btn');
    refreshBtn.addEventListener('click', function() {
        this.classList.add('spinning');
        refreshStats();
    });

    // Auto refresh every 30 seconds
    setInterval(refreshStats, 30000);

    function refreshStats() {
        fetch('{{ route("dashboard.stats") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.stats) {
                updateUI(data.stats);
            }
            document.getElementById('refresh-stats-btn').classList.remove('spinning');
            document.getElementById('last-update').textContent = 'Dernière mise à jour: ' + new Date().toLocaleTimeString('fr-FR');
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('refresh-stats-btn').classList.remove('spinning');
        });
    }

    function updateUI(stats) {
        // Update stat cards
        animateValue('stat-devices', stats.totalDevices);
        animateValue('stat-online', stats.onlineCount);
        animateValue('stat-offline', stats.offlineCount);
        animateValue('stat-users', stats.userCount);
        animateValue('stat-groups', stats.groupCount);
        animateValue('stat-geofences', stats.geofenceCount);
        animateValue('stat-expiring', stats.expiringCount);
        animateValue('stat-expired', stats.expiredCount);
        
        // Update status list
        animateValue('status-online', stats.onlineCount);
        animateValue('status-offline', stats.offlineCount);
        animateValue('status-inactive', stats.inactiveCount);
        animateValue('status-expired', stats.expiredCount);
        animateValue('status-stock', stats.stockCount);

        // Update charts
        activityChart.data.datasets[0].data = generateRandomData(stats.onlineCount, 7);
        activityChart.data.datasets[1].data = generateRandomData(stats.offlineCount, 7);
        activityChart.update();

        distributionChart.data.datasets[0].data = [stats.onlineCount, stats.offlineCount, stats.inactiveCount, stats.expiredCount];
        distributionChart.update();
    }

    function animateValue(id, newValue) {
        const el = document.getElementById(id);
        if (el) {
            el.style.transform = 'scale(1.1)';
            el.style.transition = 'transform 0.2s ease';
            setTimeout(() => {
                el.textContent = newValue;
                el.style.transform = 'scale(1)';
            }, 100);
        }
    }

    function generateRandomData(baseValue, count) {
        const data = [];
        for (let i = 0; i < count; i++) {
            data.push(Math.max(0, baseValue + Math.floor(Math.random() * 5) - 2));
        }
        return data;
    }
});
</script>
@endpush
