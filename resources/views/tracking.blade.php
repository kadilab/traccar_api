@extends('layouts.app')

@section('title', __('messages.monitor.track') . ' - Traccar TF')

@section('content')

<div class="tracking-container">

    <!-- Main Content -->
    <div class="tracking-content">
        <!-- Info Panel (Left) -->
        <div class="info-panel">
            <!-- Device Stats Card -->
            <div class="info-card device-card">
                <div class="card-header-modern">
                    <div class="card-icon device-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="card-title-group">
                        <h3 class="card-title-modern">{{ __('messages.device.title') }}</h3>
                        <!-- <span class="card-subtitle" id="infoStatus">--</span> -->
                    </div>
                </div>
                <div class="info-list">
                    <div class="info-row">
                        <div class="info-icon"><i class="fas fa-clock"></i></div>
                        <div class="info-content">
                            <span class="info-label">{{ __('messages.device.last_update') }}</span>
                            <span class="info-value" id="infoLastUpdate">--</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="fas fa-microchip"></i></div>
                        <div class="info-content">
                            <span class="info-label">{{ __('messages.device.model') }}</span>
                            <span class="info-value" id="infoModel">--</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="fas fa-phone"></i></div>
                        <div class="info-content">
                            <span class="info-label">{{ __('messages.device.phone') }}</span>
                            <span class="info-value" id="infoPhone">--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Position Card -->
            <div class="info-card position-card">
                <div class="card-header-modern">
                    <div class="card-icon position-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="card-title-group">
                        <h3 class="card-title-modern">{{ __('messages.monitor.last_position') }}</h3>
                        <span class="card-subtitle coords" id="infoCoords">--</span>
                    </div>
                </div>
                <div class="position-stats">
                    <div class="stat-box speed-stat">
                        <div class="stat-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="infoSpeed">--</span>
                            <span class="stat-label">{{ __('messages.monitor.speed') }}</span>
                        </div>
                    </div>
                    <div class="stat-box course-stat">
                        <div class="stat-icon"><i class="fas fa-compass"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="infoCourse">--</span>
                            <span class="stat-label">{{ __('messages.monitor.course') }}</span>
                        </div>
                    </div>
                    <div class="stat-box altitude-stat">
                        <div class="stat-icon"><i class="fas fa-mountain"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="infoAltitude">--</span>
                            <span class="stat-label">{{ __('messages.monitor.altitude') }}</span>
                        </div>
                    </div>
                </div>
                <div class="coordinates-detail">
                    <div class="coord-item">
                        <span class="coord-label">LAT</span>
                        <span class="coord-value" id="infoLatitude">--</span>
                    </div>
                    <div class="coord-divider"></div>
                    <div class="coord-item">
                        <span class="coord-label">LNG</span>
                        <span class="coord-value" id="infoLongitude">--</span>
                    </div>
                </div>
            </div>

            <!-- Real-time Indicator -->
            <!-- <div class="realtime-card">
                <div class="realtime-indicator active" id="realtimeIndicator">
                    <span class="realtime-dot"></span>
                    <span class="realtime-text">{{ __('messages.monitor.title') }}</span>
                </div>
                <p class="realtime-info">{{ __('messages.common.refresh') }}: <span id="refreshRate">2s</span></p>
            </div> -->
        </div>

        <!-- Map Section (Right) -->
        <div class="map-section">
            <div id="trackingMap" class="tracking-map"></div>
            
            <!-- Floating Header (inside map) -->
            <div class="floating-header">
                <a href="{{ route('device') }}" class="btn-back" title="Retour">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="device-title">
                    <span class="device-name" id="deviceName">{{ __('messages.common.loading') }}...</span>
                    <span class="device-imei" id="deviceIdentifier">--</span>
                </div>
                <div class="status-badge" id="statusBadge">
                    <span class="status-dot"></span>
                    <span class="status-text" id="statusText">--</span>
                </div>
            </div>
            
            <!-- Action Buttons Overlay -->
            <div class="map-actions-overlay">
                <button class="map-action-btn" id="btnCenterMap" title="{{ __('messages.monitor.center_map') }}">
                    <i class="fas fa-crosshairs"></i>
                </button>
                <button class="map-action-btn" id="btnRefresh" title="{{ __('messages.common.refresh') }}">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <a href="#" class="map-action-btn" id="btnHistory" title="{{ __('messages.monitor.view_history') }}">
                    <i class="fas fa-history"></i>
                </a>
                <button class="map-action-btn" id="btnToggleFollow" title="Auto-Follow">
                    <i class="fas fa-location-arrow"></i>
                </button>
            </div>
            
            <!-- Map Controls -->
            <div class="map-controls">
                <button class="map-control-btn" id="btnZoomIn" title="Zoom In">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="map-control-btn" id="btnZoomOut" title="Zoom Out">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="map-control-btn" id="btnFullscreen" title="Fullscreen">
                    <i class="fas fa-expand"></i>
                </button>
            </div>

            <!-- Speed Gauge Overlay -->
            <div class="speed-gauge-overlay">
                <div class="speed-gauge">
                    <span class="speed-value" id="speedGaugeValue">0</span>
                    <span class="speed-unit">km/h</span>
                </div>
            </div>
            
            <!-- Command Button -->
            <div class="command-btn-overlay">
                <button class="command-floating-btn" id="btnOpenCommand" title="Envoyer une commande">
                    <i class="fas fa-terminal"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Command Modal -->
<div class="modal fade" id="commandModal" tabindex="-1" aria-labelledby="commandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="commandModalLabel">
                    <i class="fas fa-terminal me-2"></i>Envoyer une commande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-car me-1"></i>Appareil
                    </label>
                    <div class="form-control bg-light" id="commandDeviceName">-</div>
                    <input type="hidden" id="commandDeviceId">
                </div>
                <div class="mb-3">
                    <label for="commandType" class="form-label fw-bold">
                        <i class="fas fa-list me-1"></i>Type de commande
                    </label>
                    <select class="form-select" id="commandType" onchange="onCommandTypeChange()">
                        <option value="">Chargement des commandes...</option>
                    </select>
                </div>
                <div class="mb-3" id="commandDataGroup" style="display: none;">
                    <label for="commandData" class="form-label fw-bold">
                        <i class="fas fa-code me-1"></i>Données de la commande
                    </label>
                    <textarea class="form-control" id="commandData" rows="3" placeholder="Entrez les données de la commande..."></textarea>
                    <div class="form-text">Pour les commandes personnalisées, entrez la commande brute ici.</div>
                </div>
                <div class="alert alert-info mb-0" id="commandDescription">
                    <i class="fas fa-info-circle me-1"></i>
                    <span>Sélectionnez un type de commande pour voir sa description.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-primary" id="btnSendCommand" onclick="executeCommand()">
                    <i class="fas fa-paper-plane me-1"></i>Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* Tracking Page Styles */
.tracking-container {
    padding: 0;
    padding-top: 60px;
    max-width: 100%;
    margin: 0;
    min-height: calc(100vh - 60px);
    position: relative;
}

/* Floating Header */
.floating-header {
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 8px 16px;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    z-index: 450;
    max-width: calc(100% - 200px);
}

.floating-header .btn-back {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    color: #374151;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 14px;
    flex-shrink: 0;
}

.floating-header .btn-back:hover {
    background: #3b82f6;
    color: #fff;
}

.device-title {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.device-name {
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.device-imei {
    font-size: 11px;
    color: #6b7280;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.floating-header .status-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    flex-shrink: 0;
    background: #f3f4f6;
    color: #374151;
}

.floating-header .status-badge.online {
    background: #dbeafe;
    color: #1e40af;
}

.floating-header .status-badge.offline {
    background: #fee2e2;
    color: #dc2626;
}

.floating-header .status-dot {
    width: 8px;
    height: 8px;
    background: #9ca3af;
    border-radius: 50%;
}

.floating-header .status-badge.online .status-dot {
    background: #3b82f6;
    animation: pulse 1.5s infinite;
}

.floating-header .status-badge.offline .status-dot {
    background: #ef4444;
    animation: none;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}

/* Main Content */
.tracking-content {
    display: flex;
    gap: 15px;
    height: calc(100vh - 60px);
    min-height: 500px;
    padding: 10px;
}

/* Info Panel */
.info-panel {
    width: 280px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.info-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 14px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.8);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.info-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

/* Card Header Modern */
.card-header-modern {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f3f4f6;
}

.card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.device-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.position-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.card-title-group {
    flex: 1;
    min-width: 0;
}

.card-title-modern {
    margin: 0;
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
}

.card-subtitle {
    font-size: 11px;
    color: #6b7280;
    margin-top: 1px;
    display: block;
}

.card-subtitle.coords {
    font-family: 'Monaco', 'Consolas', monospace;
    font-size: 10px;
    color: #10b981;
}

/* Info List Style */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 10px;
    background: #f9fafb;
    border-radius: 8px;
    transition: background 0.2s ease;
}

.info-row:hover {
    background: #f3f4f6;
}

.info-icon {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 12px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.info-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.info-label {
    font-size: 10px;
    color: #9ca3af;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.info-value {
    font-size: 12px;
    font-weight: 600;
    color: #1f2937;
}

/* Position Stats */
.position-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
    margin-bottom: 10px;
}

.stat-box {
    background: #f9fafb;
    border-radius: 8px;
    padding: 8px 6px;
    text-align: center;
    transition: all 0.2s ease;
}

.stat-box:hover {
    background: #f3f4f6;
    transform: scale(1.02);
}

.stat-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    margin: 0 auto 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.speed-stat .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
}

.course-stat .stat-icon {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: #fff;
}

.altitude-stat .stat-icon {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: #fff;
}

.stat-value {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1px;
}

.stat-label {
    font-size: 9px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

/* Coordinates Detail */
.coordinates-detail {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 8px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 8px;
    border: 1px solid #bbf7d0;
}

.coord-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.coord-label {
    font-size: 9px;
    font-weight: 600;
    color: #059669;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.coord-value {
    font-size: 11px;
    font-weight: 600;
    color: #065f46;
    font-family: 'Monaco', 'Consolas', monospace;
}

.coord-divider {
    width: 1px;
    height: 22px;
    background: #86efac;
}

.info-value.address {
    font-size: 13px;
    font-weight: 500;
    line-height: 1.4;
}

/* Actions Card */
.actions-card {
    padding: 15px;
}

.action-buttons-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.action-btn:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: #eff6ff;
}

.action-btn.active {
    border-color: #3b82f6;
    background: #3b82f6;
    color: #fff;
}

.action-btn i {
    font-size: 16px;
}

/* Realtime Card */
.realtime-card {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-radius: 12px;
    padding: 15px 20px;
    border: 2px solid #3b82f6;
}

.realtime-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    color: #1e40af;
}

.realtime-indicator .realtime-dot {
    width: 12px;
    height: 12px;
    background: #3b82f6;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

.realtime-info {
    margin: 8px 0 0;
    font-size: 13px;
    color: #1e40af;
}

/* Map Section */
.map-section {
    flex: 1;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.tracking-map {
    width: 100%;
    height: 100%;
    min-height: 500px;
}

/* Map Action Buttons Overlay */
.map-actions-overlay {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 400;
}

.map-action-btn {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    color: #374151;
    font-size: 18px;
    transition: all 0.2s ease;
    text-decoration: none;
}

.map-action-btn:hover {
    background: #3b82f6;
    color: #fff;
    transform: scale(1.05);
}

.map-action-btn.active {
    background: #3b82f6;
    color: #fff;
}

/* Map Controls */
.map-controls {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 400;
}

.map-control-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    color: #374151;
    font-size: 16px;
    transition: all 0.2s ease;
}

.map-control-btn:hover {
    background: #3b82f6;
    color: #fff;
}

/* Speed Gauge Overlay */
.speed-gauge-overlay {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 400;
}

.speed-gauge {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    width: 100px;
    height: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    border: 4px solid #3b82f6;
}

.speed-value {
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.speed-unit {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
}

/* Leaflet Controls z-index fix */
.leaflet-top, .leaflet-bottom {
    z-index: 400 !important;
}

/* Responsive */
@media (max-width: 1200px) {
    .tracking-content {
        gap: 12px;
        height: auto;
        flex-direction: column;
    }
    
    .info-panel {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        order: 2;
    }
    
    .info-card {
        flex: 1;
        min-width: 250px;
    }
    
    .map-section {
        height: 450px;
        order: 1;
    }
}

@media (max-width: 768px) {
    .floating-header {
        top: 10px;
        padding: 6px 12px;
        gap: 10px;
        max-width: calc(100% - 100px);
    }
    
    .floating-header .btn-back {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .device-name {
        font-size: 13px;
    }
    
    .device-imei {
        font-size: 10px;
    }
    
    .floating-header .status-badge {
        padding: 5px 10px;
        font-size: 11px;
    }
    
    .tracking-content {
        padding: 8px;
        gap: 10px;
    }
    
    .info-panel {
        flex-direction: column;
        gap: 10px;
    }
    
    .info-card {
        padding: 15px;
        min-width: 100%;
        border-radius: 14px;
    }
    
    .card-header-modern {
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
    }
    
    .card-icon {
        width: 42px;
        height: 42px;
        font-size: 18px;
    }
    
    .card-title-modern {
        font-size: 14px;
    }
    
    .info-row {
        padding: 8px 10px;
    }
    
    .info-icon {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
    
    .position-stats {
        gap: 8px;
    }
    
    .stat-box {
        padding: 10px 8px;
    }
    
    .stat-icon {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .stat-value {
        font-size: 14px;
    }
    
    .stat-label {
        font-size: 9px;
    }
    
    .coordinates-detail {
        padding: 10px;
        gap: 12px;
    }
    
    .coord-value {
        font-size: 12px;
    }
    
    .map-section {
        height: 400px;
    }
    
    .map-actions-overlay {
        top: 10px;
        left: 10px;
        gap: 6px;
    }
    
    .map-action-btn {
        width: 38px;
        height: 38px;
        font-size: 16px;
    }
    
    .map-controls {
        top: 10px;
        right: 10px;
        gap: 6px;
    }
    
    .map-control-btn {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    
    .speed-gauge-overlay {
        bottom: 15px;
        left: 15px;
    }
    
    .speed-gauge {
        width: 75px;
        height: 75px;
        border: 3px solid #3b82f6;
    }
    
    .speed-value {
        font-size: 24px;
    }
    
    .speed-unit {
        font-size: 10px;
    }
}

@media (max-width: 480px) {
    .floating-header {
        top: 8px;
        padding: 5px 10px;
        gap: 8px;
        border-radius: 30px;
        max-width: calc(100% - 80px);
    }
    
    .floating-header .btn-back {
        width: 28px;
        height: 28px;
        font-size: 11px;
    }
    
    .device-title {
        max-width: 120px;
    }
    
    .device-name {
        font-size: 12px;
    }
    
    .device-imei {
        font-size: 9px;
    }
    
    .floating-header .status-badge {
        padding: 4px 8px;
        font-size: 10px;
        gap: 4px;
    }
    
    .floating-header .status-dot {
        width: 6px;
        height: 6px;
    }
    
    .tracking-content {
        padding: 5px;
    }
    
    .info-card {
        padding: 12px;
        border-radius: 12px;
    }
    
    .card-header-modern {
        gap: 10px;
        margin-bottom: 12px;
        padding-bottom: 10px;
    }
    
    .card-icon {
        width: 38px;
        height: 38px;
        font-size: 16px;
        border-radius: 10px;
    }
    
    .card-title-modern {
        font-size: 13px;
    }
    
    .card-subtitle {
        font-size: 11px;
    }
    
    .info-list {
        gap: 8px;
    }
    
    .info-row {
        padding: 8px;
        border-radius: 8px;
    }
    
    .info-icon {
        width: 26px;
        height: 26px;
        font-size: 11px;
        border-radius: 6px;
    }
    
    .info-label {
        font-size: 10px;
    }
    
    .info-value {
        font-size: 12px;
    }
    
    .position-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        margin-bottom: 10px;
    }
    
    .stat-box {
        padding: 8px 6px;
        border-radius: 10px;
    }
    
    .stat-icon {
        width: 28px;
        height: 28px;
        font-size: 12px;
        margin-bottom: 6px;
        border-radius: 8px;
    }
    
    .stat-value {
        font-size: 13px;
    }
    
    .stat-label {
        font-size: 8px;
    }
    
    .coordinates-detail {
        padding: 8px;
        gap: 10px;
        border-radius: 8px;
    }
    
    .coord-label {
        font-size: 9px;
    }
    
    .coord-value {
        font-size: 11px;
    }
    
    .coord-divider {
        height: 24px;
    }
    
    .map-section {
        height: 350px;
        border-radius: 10px;
    }
    
    .map-action-btn {
        width: 34px;
        height: 34px;
        font-size: 14px;
    }
    
    .map-control-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .speed-gauge {
        width: 65px;
        height: 65px;
        border: 2px solid #3b82f6;
    }
    
    .speed-value {
        font-size: 20px;
    }
    
    .speed-unit {
        font-size: 9px;
    }
}

/* RTL Support */
[dir="rtl"] .btn-back:hover {
    transform: translateX(3px);
}

[dir="rtl"] .header-left {
    flex-direction: row-reverse;
}

/* Command Button Overlay */
.command-btn-overlay {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 400;
}

.command-floating-btn {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: #fff;
    border: none;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    cursor: pointer;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.command-floating-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.5);
}

/* Command Modal Styles */
#commandModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

#commandModal .modal-header {
    border-radius: 12px 12px 0 0;
    border-bottom: none;
}

#commandModal .modal-body {
    padding: 24px;
}

#commandModal .form-select,
#commandModal .form-control {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    padding: 10px 14px;
}

#commandModal .form-select:focus,
#commandModal .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

#commandModal .btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
}

#commandModal .alert {
    border-radius: 8px;
    border: none;
}

@media (max-width: 768px) {
    .command-btn-overlay {
        bottom: 15px;
        right: 130px;
    }
    
    .command-floating-btn {
        width: 48px;
        height: 48px;
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .command-btn-overlay {
        bottom: 10px;
        right: 100px;
    }
    
    .command-floating-btn {
        width: 42px;
        height: 42px;
        font-size: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get device ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const deviceId = urlParams.get('id');
    
    if (!deviceId) {
        showWarning('{{ __("messages.device.no_devices") }}');
        window.location.href = '{{ route("device") }}';
        return;
    }

    let map = null;
    let marker = null;
    let device = null;
    let position = null;
    let autoFollow = true;
    let refreshInterval = null;
    const REFRESH_RATE = 2000; // 2 seconds

    // Initialize map
    function initMap() {
        map = L.map('trackingMap', {
            zoomControl: false // Disable native zoom controls
        }).setView([36.7538, 3.0588], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Custom car icon - default to offline (0)
        const carIcon = createVehicleIcon(0, 0);

        marker = L.marker([36.7538, 3.0588], { icon: carIcon }).addTo(map);
    }
    
    // Déterminer l'icône du véhicule basée sur son statut
    // 0 = offline, 1 = arrêté moteur éteint, 2 = en mouvement, 3 = moteur allumé mais arrêté (idling)
    function getVehicleIconNumber() {
        // Vérifier si le device est offline
        if (!device || device.status !== 'online') {
            return 0; // offline
        }
        
        // Récupérer la vitesse et l'état d'allumage
        const speed = position ? (position.speed * 1.852) : 0; // knots to km/h
        const ignition = position?.attributes?.ignition ?? false;
        
        if (speed > 1) {
            return 2; // en mouvement
        } else if (ignition) {
            return 3; // moteur allumé mais arrêté (idling)
        } else {
            return 1; // arrêté moteur éteint
        }
    }
    
    // Créer l'icône du véhicule
    function createVehicleIcon(iconNumber, rotation) {
        return L.divIcon({
            className: 'custom-car-marker',
            html: `<div class="car-marker-container" style="transform: rotate(${rotation}deg);">
                <img src="/icons/automobile_${iconNumber}.png" 
                     style="width: 50px; height: 50px; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));" 
                     alt="vehicle"/>
            </div>`,
            iconSize: [50, 50],
            iconAnchor: [25, 25]
        });
    }

    // Load device data
    async function loadDevice() {
        try {
            const response = await fetch(`/api/traccar/devices/${deviceId}`);
            const data = await response.json();
            
            if (data.success && data.device) {
                device = data.device;
                updateDeviceInfo();
                await loadPosition();
            } else {
                showError('{{ __("messages.device.no_devices") }}');
            }
        } catch (error) {
            console.error('Error loading device:', error);
            showError('{{ __("messages.messages.error") }}');
        }
    }

    // Load position
    async function loadPosition() {
        if (!device) return;
        
        try {
            const response = await fetch(`/api/traccar/positions?deviceId=${deviceId}`);
            const data = await response.json();
            
            if (data.success && data.positions && data.positions.length > 0) {
                position = data.positions[0];
                updatePositionInfo();
                updateMap();
            }
        } catch (error) {
            console.error('Error loading position:', error);
        }
    }

    // Update device info in UI
    function updateDeviceInfo() {
        document.getElementById('deviceName').textContent = device.name || 'Unknown';
        document.getElementById('deviceIdentifier').textContent = device.uniqueId || '--';
        
        // Status badge
        const statusBadge = document.getElementById('statusBadge');
        const statusText = document.getElementById('statusText');
        const status = device.status || 'unknown';
        
        statusBadge.className = `status-badge ${status}`;
        statusText.textContent = getStatusLabel(status);
        
        // Info panel - Card subtitle shows status
        // const infoStatus = document.getElementById('infoStatus');
        // if (infoStatus) {
        //     infoStatus.innerHTML = `<span class="status-badge-mini status-${status}">${getStatusLabel(status)}</span>`;
        // }
        
        document.getElementById('infoLastUpdate').textContent = formatDate(device.lastUpdate);
        document.getElementById('infoModel').textContent = device.model || '--';
        document.getElementById('infoPhone').textContent = device.phone || '--';
        
        // History link
        document.getElementById('btnHistory').href = `/history?id=${deviceId}`;
    }

    // Update position info in UI
    function updatePositionInfo() {
        if (!position) return;
        
        const latElem = document.getElementById('infoLatitude');
        const lngElem = document.getElementById('infoLongitude');
        const speedElem = document.getElementById('infoSpeed');
        const courseElem = document.getElementById('infoCourse');
        const altElem = document.getElementById('infoAltitude');
        const coordsElem = document.getElementById('infoCoords');
        
        const lat = position.latitude?.toFixed(6) || '--';
        const lng = position.longitude?.toFixed(6) || '--';
        
        if (latElem) latElem.textContent = lat;
        if (lngElem) lngElem.textContent = lng;
        if (coordsElem) coordsElem.textContent = `${lat}, ${lng}`;
        
        const speedKmh = (position.speed * 1.852).toFixed(1); // knots to km/h
        if (speedElem) speedElem.textContent = `${speedKmh} km/h`;
        document.getElementById('speedGaugeValue').textContent = Math.round(speedKmh);
        
        if (courseElem) courseElem.textContent = `${position.course?.toFixed(0) || 0}°`;
        if (altElem) altElem.textContent = `${position.altitude?.toFixed(0) || 0} m`;
    }

    // Update map
    function updateMap() {
        if (!position || !map || !marker) return;
        
        const lat = position.latitude;
        const lng = position.longitude;
        
        // Update marker position
        marker.setLatLng([lat, lng]);
        
        // Recreate icon with new rotation and status-based icon
        const iconNumber = getVehicleIconNumber();
        const rotation = position.course || 0;
        const carIcon = createVehicleIcon(iconNumber, rotation);
        marker.setIcon(carIcon);
        
        // Center map if auto-follow is enabled
        if (autoFollow) {
            map.setView([lat, lng], map.getZoom());
        }
        
        // Update marker popup
        marker.bindPopup(`
            <div class="marker-popup">
                <strong>${device?.name || 'Device'}</strong><br>
                <small>{{ __('messages.monitor.speed') }}: ${(position.speed * 1.852).toFixed(1)} km/h</small><br>
                <small>${formatDate(position.fixTime)}</small>
            </div>
        `);
    }

    // Reverse geocode to get address
    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
            const data = await response.json();
            
            if (data.display_name) {
                document.getElementById('infoAddress').textContent = data.display_name;
            }
        } catch (error) {
            console.error('Geocoding error:', error);
        }
    }

    // Start real-time updates
    function startRealTimeUpdates() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        refreshInterval = setInterval(async () => {
            await loadPosition();
            await loadDeviceStatus();
        }, REFRESH_RATE);
        
        console.log('Real-time tracking started');
    }

    // Load device status silently
    async function loadDeviceStatus() {
        try {
            const response = await fetch(`/api/traccar/devices/${deviceId}`);
            const data = await response.json();
            
            if (data.success && data.device) {
                device = data.device;
                updateDeviceInfo();
            }
        } catch (error) {
            console.error('Error updating device status:', error);
        }
    }

    // Helpers
    function getStatusLabel(status) {
        const labels = { online: 'Online', offline: 'Offline', unknown: '{{ __("messages.common.loading") }}' };
        return labels[status] || status;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '--';
        const date = new Date(dateStr);
        return date.toLocaleString('{{ app()->getLocale() }}', { 
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }

    function showError(message) {
        document.getElementById('deviceName').textContent = message;
    }

    // Command types descriptions
    const commandDescriptions = {
        'custom': 'Envoyer une commande personnalisée brute à l\'appareil.',
        'positionPeriodic': 'Définir l\'intervalle de mise à jour de position.',
        'positionStop': 'Arrêter les mises à jour de position.',
        'engineStop': 'Couper le moteur du véhicule à distance.',
        'engineResume': 'Réactiver le moteur du véhicule.',
        'alarmArm': 'Activer l\'alarme du véhicule.',
        'alarmDisarm': 'Désactiver l\'alarme du véhicule.',
        'setTimezone': 'Configurer le fuseau horaire de l\'appareil.',
        'requestPhoto': 'Demander une photo depuis la caméra de l\'appareil.',
        'rebootDevice': 'Redémarrer l\'appareil GPS.',
        'sendSms': 'Envoyer un SMS via l\'appareil.',
        'sendUssd': 'Envoyer une commande USSD.',
        'sosNumber': 'Configurer le numéro SOS.',
        'silenceTime': 'Définir une période de silence.',
        'setPhonebook': 'Configurer le répertoire téléphonique.',
        'voiceMessage': 'Envoyer un message vocal.',
        'outputControl': 'Contrôler les sorties de l\'appareil.',
        'voiceMonitoring': 'Activer la surveillance vocale.',
        'setAgps': 'Configurer l\'AGPS.',
        'setIndicator': 'Configurer l\'indicateur LED.',
        'configuration': 'Envoyer une configuration à l\'appareil.',
        'getVersion': 'Obtenir la version du firmware.',
        'firmwareUpdate': 'Mettre à jour le firmware.',
        'setConnection': 'Configurer la connexion serveur.',
        'setOdometer': 'Réinitialiser/configurer l\'odomètre.',
        'getModemStatus': 'Obtenir le statut du modem.',
        'getDeviceStatus': 'Obtenir le statut de l\'appareil.',
        'setSpeedLimit': 'Définir la limite de vitesse.',
        'modePowerSaving': 'Activer le mode économie d\'énergie.',
        'modeDeepSleep': 'Activer le mode veille profonde.',
        'movementAlarm': 'Configurer l\'alarme de mouvement.',
        'setDriverId': 'Définir l\'ID du conducteur.'
    };
    
    // Format command type for display
    function formatCommandType(type) {
        const formats = {
            'custom': 'Commande personnalisée',
            'positionPeriodic': 'Position périodique',
            'positionStop': 'Arrêter le positionnement',
            'engineStop': 'Arrêter le moteur',
            'engineResume': 'Redémarrer le moteur',
            'alarmArm': 'Activer l\'alarme',
            'alarmDisarm': 'Désactiver l\'alarme',
            'setTimezone': 'Définir fuseau horaire',
            'requestPhoto': 'Demander une photo',
            'rebootDevice': 'Redémarrer l\'appareil',
            'sendSms': 'Envoyer SMS',
            'sendUssd': 'Commande USSD',
            'sosNumber': 'Numéro SOS',
            'silenceTime': 'Période de silence',
            'setPhonebook': 'Répertoire téléphonique',
            'voiceMessage': 'Message vocal',
            'outputControl': 'Contrôle des sorties',
            'voiceMonitoring': 'Surveillance vocale',
            'setAgps': 'Configurer AGPS',
            'setIndicator': 'Configurer indicateur',
            'configuration': 'Configuration',
            'getVersion': 'Version firmware',
            'firmwareUpdate': 'Mise à jour firmware',
            'setConnection': 'Configurer connexion',
            'setOdometer': 'Configurer odomètre',
            'getModemStatus': 'Statut modem',
            'getDeviceStatus': 'Statut appareil',
            'setSpeedLimit': 'Limite de vitesse',
            'modePowerSaving': 'Mode économie énergie',
            'modeDeepSleep': 'Mode veille profonde',
            'movementAlarm': 'Alarme mouvement',
            'setDriverId': 'ID conducteur'
        };
        return formats[type] || type;
    }
    
    // Open command modal
    function openCommandModal() {
        if (!device) return;
        
        document.getElementById('commandDeviceId').value = deviceId;
        document.getElementById('commandDeviceName').textContent = device.name || 'Appareil #' + deviceId;
        document.getElementById('commandType').innerHTML = '<option value="">Chargement...</option>';
        document.getElementById('commandDataGroup').style.display = 'none';
        document.getElementById('commandData').value = '';
        document.getElementById('commandDescription').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Chargement des commandes disponibles...';
        
        // Open modal
        const modal = new bootstrap.Modal(document.getElementById('commandModal'));
        modal.show();
        
        // Load available command types for this device
        loadCommandTypes();
    }
    
    // Load command types for device
    async function loadCommandTypes() {
        try {
            const response = await fetch(`/api/traccar/commands/types?deviceId=${deviceId}`);
            const data = await response.json();
            
            const select = document.getElementById('commandType');
            select.innerHTML = '<option value="">-- Sélectionnez une commande --</option>';
            
            if (data.success && data.types) {
                data.types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.type;
                    option.textContent = formatCommandType(type.type);
                    select.appendChild(option);
                });
                
                document.getElementById('commandDescription').innerHTML = 
                    '<i class="fas fa-info-circle me-1"></i> Sélectionnez une commande pour voir sa description.';
            } else {
                select.innerHTML = '<option value="">Aucune commande disponible</option>';
                document.getElementById('commandDescription').innerHTML = 
                    '<i class="fas fa-exclamation-triangle me-1"></i> Aucune commande disponible pour cet appareil.';
            }
        } catch (error) {
            console.error('Error loading command types:', error);
            document.getElementById('commandType').innerHTML = '<option value="">Erreur de chargement</option>';
            document.getElementById('commandDescription').innerHTML = 
                '<i class="fas fa-exclamation-circle me-1"></i> Erreur lors du chargement des commandes.';
        }
    }
    
    // Handle command type change
    window.onCommandTypeChange = function() {
        const type = document.getElementById('commandType').value;
        const dataGroup = document.getElementById('commandDataGroup');
        const description = document.getElementById('commandDescription');
        
        if (type === 'custom') {
            dataGroup.style.display = 'block';
        } else {
            dataGroup.style.display = 'none';
        }
        
        if (type && commandDescriptions[type]) {
            description.innerHTML = '<i class="fas fa-info-circle me-1"></i> ' + commandDescriptions[type];
            description.className = 'alert alert-info mb-0';
        } else if (type) {
            description.innerHTML = '<i class="fas fa-info-circle me-1"></i> Commande: ' + formatCommandType(type);
            description.className = 'alert alert-info mb-0';
        } else {
            description.innerHTML = '<i class="fas fa-info-circle me-1"></i> Sélectionnez une commande pour voir sa description.';
        }
    };
    
    // Execute command
    window.executeCommand = async function() {
        const cmdDeviceId = document.getElementById('commandDeviceId').value;
        const type = document.getElementById('commandType').value;
        const data = document.getElementById('commandData').value;
        
        if (!type) {
            showWarning('Veuillez sélectionner un type de commande.');
            return;
        }
        
        const btn = document.getElementById('btnSendCommand');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Envoi...';
        btn.disabled = true;
        
        try {
            const commandData = {
                deviceId: parseInt(cmdDeviceId),
                type: type
            };
            
            // Add custom data if present
            if (type === 'custom' && data) {
                commandData.data = data;
            }
            
            const response = await fetch('/api/traccar/commands/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(commandData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                document.getElementById('commandDescription').innerHTML = 
                    '<i class="fas fa-check-circle me-1"></i> Commande envoyée avec succès!';
                document.getElementById('commandDescription').className = 'alert alert-success mb-0';
                
                // Close modal after success
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('commandModal')).hide();
                }, 1500);
            } else {
                document.getElementById('commandDescription').innerHTML = 
                    '<i class="fas fa-exclamation-circle me-1"></i> Erreur: ' + (result.message || 'Échec de l\'envoi');
                document.getElementById('commandDescription').className = 'alert alert-danger mb-0';
            }
        } catch (error) {
            console.error('Error sending command:', error);
            document.getElementById('commandDescription').innerHTML = 
                '<i class="fas fa-exclamation-circle me-1"></i> Erreur de connexion au serveur.';
            document.getElementById('commandDescription').className = 'alert alert-danger mb-0';
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    };

    // Event listeners
    document.getElementById('btnCenterMap').addEventListener('click', function() {
        if (position && map) {
            map.setView([position.latitude, position.longitude], 16);
        }
    });

    document.getElementById('btnRefresh').addEventListener('click', async function() {
        this.querySelector('i').classList.add('fa-spin');
        await loadPosition();
        await loadDeviceStatus();
        setTimeout(() => {
            this.querySelector('i').classList.remove('fa-spin');
        }, 500);
    });

    document.getElementById('btnToggleFollow').addEventListener('click', function() {
        autoFollow = !autoFollow;
        this.classList.toggle('active', autoFollow);
        
        if (autoFollow && position) {
            map.setView([position.latitude, position.longitude], map.getZoom());
        }
    });

    document.getElementById('btnZoomIn').addEventListener('click', function() {
        map.zoomIn();
    });

    document.getElementById('btnZoomOut').addEventListener('click', function() {
        map.zoomOut();
    });

    document.getElementById('btnFullscreen').addEventListener('click', function() {
        const mapSection = document.querySelector('.map-section');
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            mapSection.requestFullscreen();
        }
    });
    
    // Command button event listener
    document.getElementById('btnOpenCommand').addEventListener('click', function() {
        openCommandModal();
    });

    // Pause updates when page is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                console.log('Tracking paused');
            }
        } else {
            loadPosition();
            startRealTimeUpdates();
        }
    });

    // Initialize
    initMap();
    loadDevice();
    startRealTimeUpdates();
    
    // Set auto-follow button as active by default
    document.getElementById('btnToggleFollow').classList.add('active');
});
</script>

<!-- Custom marker styles -->
<style>
.custom-car-marker {
    background: transparent !important;
    border: none !important;
}

.car-marker-container {
    position: relative;
    width: 50px;
    height: 50px;
    transition: transform 0.3s ease;
}

.car-marker-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    background: rgba(59, 130, 246, 0.3);
    border-radius: 50%;
    animation: marker-pulse 2s infinite;
}

.car-marker-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

@keyframes marker-pulse {
    0% {
        transform: translate(-50%, -50%) scale(0.5);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.5);
        opacity: 0;
    }
}

.marker-popup {
    font-family: inherit;
    line-height: 1.5;
}

.status-badge-mini {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge-mini.status-online {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge-mini.status-offline {
    background: #fee2e2;
    color: #dc2626;
}

.status-badge-mini.status-unknown {
    background: #fef3c7;
    color: #d97706;
}
</style>
@endpush
