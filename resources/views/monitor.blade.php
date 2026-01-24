@extends('layouts.app')

@section('title', 'Monitor - Traccar TF')

@section('content')

<div class="monitor-container">
    <!-- Main Content -->
    <div class="monitor-content">
        <!-- Left Panel - Devices List -->
        <div class="info-panel">
            <!-- Search & Filters -->
            <div class="info-card search-card">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="deviceSearch" placeholder="Rechercher un véhicule...">
                </div>
                <div class="quick-filters">
                    <button class="filter-chip active" data-filter="all">
                        <span>Tous</span>
                        <span class="chip-count" id="countAll">0</span>
                    </button>
                    <button class="filter-chip" data-filter="online">
                        <span class="status-dot online"></span>
                        <span>Online</span>
                        <span class="chip-count" id="countOnline">0</span>
                    </button>
                    <button class="filter-chip" data-filter="offline">
                        <span class="status-dot offline"></span>
                        <span>Offline</span>
                    </button>
                </div>
            </div>

            <!-- Devices List -->
            <div class="info-card list-card">
                <div class="device-tree" id="deviceTree">
                    <div class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Chargement des véhicules...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <div id="map" class="main-map"></div>
            
            <!-- Floating Stats Card -->
            <div class="floating-stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <div class="stats-title">
                        <span class="stats-title-text">Suivi en Direct</span>
                        <span class="stats-subtitle">Temps réel</span>
                    </div>
                    <div class="live-badge" id="liveIndicator">
                        <span class="live-dot"></span>
                        <span>LIVE</span>
                    </div>
                </div>
                <div class="stats-row">
                    <div class="mini-stat moving">
                        <i class="fas fa-play-circle"></i>
                        <span class="mini-stat-value" id="countMoving">0</span>
                        <span class="mini-stat-label">En route</span>
                    </div>
                    <div class="mini-stat stopped">
                        <i class="fas fa-stop-circle"></i>
                        <span class="mini-stat-value" id="countStopped">0</span>
                        <span class="mini-stat-label">Arrêt</span>
                    </div>
                    <div class="mini-stat idling">
                        <i class="fas fa-pause-circle"></i>
                        <span class="mini-stat-value" id="countIdling">0</span>
                        <span class="mini-stat-label">Ralenti</span>
                    </div>
                    <div class="mini-stat offline">
                        <i class="fas fa-power-off"></i>
                        <span class="mini-stat-value" id="countOffline">0</span>
                        <span class="mini-stat-label">Offline</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ route('tracking') }}" class="action-btn" title="Tracking">
                    <i class="fas fa-route"></i>
                </a>
                <a href="{{ route('history') }}" class="action-btn" title="Historique">
                    <i class="fas fa-history"></i>
                </a>
                <a href="{{ route('geofence') }}" class="action-btn" title="Géobarrières">
                    <i class="fas fa-draw-polygon"></i>
                </a>
                <div class="action-divider"></div>
                <span class="last-update-badge" id="lastUpdate">--:--:--</span>
            </div>
            
            <!-- Map Controls -->
            <div class="map-controls">
                <button class="map-control-btn" id="btnZoomIn" title="Zoom In">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="map-control-btn" id="btnZoomOut" title="Zoom Out">
                    <i class="fas fa-minus"></i>
                </button>
                <div class="control-divider"></div>
                <button class="map-control-btn" id="btnCenterAll" title="Voir tous">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
                <button class="map-control-btn active" id="btnAutoFollow" title="Suivi auto">
                    <i class="fas fa-crosshairs"></i>
                </button>
                <button class="map-control-btn" id="btnFullscreen" title="Plein écran">
                    <i class="fas fa-expand"></i>
                </button>
            </div>

            <!-- Legend -->
            <div class="map-legend">
                <div class="legend-item">
                    <img src="/icons/automobile_2.png" alt="moving" class="legend-icon">
                    <span>En route</span>
                </div>
                <div class="legend-item">
                    <img src="/icons/automobile_1.png" alt="stopped" class="legend-icon">
                    <span>Arrêté</span>
                </div>
                <div class="legend-item">
                    <img src="/icons/automobile_3.png" alt="idling" class="legend-icon">
                    <span>Ralenti</span>
                </div>
                <div class="legend-item">
                    <img src="/icons/automobile_0.png" alt="offline" class="legend-icon">
                    <span>Offline</span>
                </div>
            </div>

            <!-- Device Info Panel (Floating) -->
            <div class="device-panel" id="devicePanel">
                <div class="panel-header">
                    <div class="panel-device-info">
                        <div class="panel-device-icon" id="panelIcon">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="panel-device-details">
                            <span class="panel-device-name" id="panelDeviceName">Sélectionnez un véhicule</span>
                            <span class="panel-device-imei" id="panelDeviceImei">-</span>
                        </div>
                    </div>
                    <button class="panel-close-btn" id="closePanel">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="panel-body" id="panelBody">
                    <div class="no-selection">
                        <i class="fas fa-hand-pointer"></i>
                        <p>Cliquez sur un véhicule</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* Monitor Page - Modern Design */
html {
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

body {
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
}

.monitor-container {
    position: fixed;
    top: 50px;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 0;
    max-width: 100%;
    margin: 0;
    overflow: hidden;
}

/* Main Content Layout */
.monitor-content {
    display: flex;
    gap: 12px;
    height: 100%;
    padding: 10px;
    box-sizing: border-box;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

/* Left Panel */
.info-panel {
    width: 300px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
    height: 100%;
    overflow: hidden;
}

/* Map Section */
.map-section {
    flex: 1;
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    min-height: 500px;
    background: #d1d5db;
}

#map, .main-map {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 1 !important;
    background: #e5e7eb;
}

/* Leaflet map fixes */
.leaflet-container {
    width: 100% !important;
    height: 100% !important;
    background: #e5e7eb !important;
}

.info-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-radius: 0px;
    padding: 14px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.8);
}

/* Search Card */
.search-card {
    padding: 12px;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f3f4f6;
    padding: 10px 14px;
    border-radius: 10px;
    margin-bottom: 10px;
}

.search-box i {
    color: #9ca3af;
}

.search-box input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
}

.quick-filters {
    display: flex;
    gap: 1px;
    flex-wrap: wrap;
}

.filter-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border: 2px solid #e5e7eb;
    background: #fff;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    color: #374151;
}

.filter-chip:hover {
    border-color: #1976d2;
}

.filter-chip.active {
    background: #1976d2;
    border-color: #1976d2;
    color: #fff;
}

.filter-chip .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.filter-chip .status-dot.online { background: #22c55e; }
.filter-chip .status-dot.offline { background: #9ca3af; }

.chip-count {
    background: rgba(0, 0, 0, 0.1);
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
}

.filter-chip.active .chip-count {
    background: rgba(255, 255, 255, 0.3);
}

/* List Card */
.list-card {
    flex: 1;
    overflow: hidden;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.device-tree {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.device-tree::-webkit-scrollbar {
    width: 6px;
}

.device-tree::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    color: #9ca3af;
    gap: 10px;
}

/* Device Item */
.device-item {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 6px;
    border: 2px solid transparent;
    background: #f9fafb;
}

.device-item:hover {
    background: #f3f4f6;
    border-color: #e5e7eb;
}

.device-item.selected {
    background: rgba(25, 118, 210, 0.1);
    border-color: #1976d2;
}

.device-status-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    flex-shrink: 0;
}

.device-status-icon img {
    width: 32px;
    height: 32px;
}

.device-info {
    flex: 1;
    min-width: 0;
}

.device-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.device-speed {
    font-size: 11px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 4px;
}

.device-speed.moving {
    color: #22c55e;
    font-weight: 600;
}

.device-category {
    font-size: 14px;
    color: #9ca3af;
}

/* Group Styles */
.group-node {
    margin-bottom: 8px;
}

.group-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid #e2e8f0;
}

.group-header:hover {
    background: linear-gradient(135deg, #e2e8f0 0%, #f1f5f9 100%);
}

.group-header .arrow {
    font-size: 10px;
    color: #64748b;
    transition: transform 0.2s;
}

.group-node.expanded .arrow {
    transform: rotate(90deg);
}

.group-header .folder-icon {
    color: #1976d2;
}

.group-name {
    flex: 1;
    font-weight: 600;
    font-size: 13px;
    color: #334155;
}

.group-count {
    background: #e2e8f0;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
}

.group-devices {
    display: none;
    padding: 8px 0 0 16px;
}

.group-node.expanded .group-devices {
    display: block;
}

.tree-empty {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
}

/* Floating Stats Card */
.floating-stats-card {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    color: #000000;
    border-radius: 8px;
    padding: 14px 18px;
    box-shadow: 0 8px 30px rgba(25, 118, 210, 0.35);
    z-index: 450;
    min-width: 320px;
}

.stats-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 0px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 12px;
}

.stats-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.stats-title {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.stats-title-text {
    font-size: 15px;
    font-weight: 700;
}

.stats-subtitle {
    font-size: 11px;
    opacity: 0.85;
}

.floating-stats-card .live-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.floating-stats-card .live-dot {
    width: 8px;
    height: 8px;
    background: #4ade80;
    border-radius: 50%;
    animation: pulse 1.5s ease-in-out infinite;
}

.floating-stats-card .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}

.floating-stats-card .mini-stat {
    background: rgba(255, 255, 255, 0.15);
    padding: 10px 6px;
    border-radius: 10px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.floating-stats-card .mini-stat i {
    font-size: 16px;
    opacity: 0.9;
}

.floating-stats-card .mini-stat-value {
    font-size: 18px;
    font-weight: 700;
}

.floating-stats-card .mini-stat-label {
    font-size: 9px;
    opacity: 0.85;
    text-transform: uppercase;
}

.floating-stats-card .mini-stat.moving { background: rgba(74, 222, 128, 0.25); }
.floating-stats-card .mini-stat.stopped { background: rgba(239, 68, 68, 0.25); }
.floating-stats-card .mini-stat.idling { background: rgba(251, 191, 36, 0.25); }
.floating-stats-card .mini-stat.offline { background: rgba(156, 163, 175, 0.25); }

/* Quick Actions */
.quick-actions {
    position: absolute;
    top: 15px;
    right: 60px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 8px 14px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    z-index: 450;
}

.action-btn {
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
}

.action-btn:hover {
    background: #1976d2;
    color: #fff;
    transform: scale(1.05);
}

.action-divider {
    width: 1px;
    height: 24px;
    background: #e5e7eb;
    margin: 0 4px;
}

.last-update-badge {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    background: #f3f4f6;
    padding: 6px 12px;
    border-radius: 20px;
}

/* Map Controls */
.map-controls {
    position: absolute;
    top: 75px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 6px;
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
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    color: #374151;
    font-size: 16px;
    transition: all 0.2s ease;
}

.map-control-btn:hover {
    background: #1976d2;
    color: #fff;
}

.map-control-btn.active {
    background: #1976d2;
    color: #fff;
}

.control-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 4px 0;
}

/* Map Legend */
.map-legend {
    position: absolute;
    bottom: 15px;
    left: 15px;
    display: flex;
    gap: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 10px 16px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 400;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #374151;
}

.legend-icon {
    width: 20px;
    height: 20px;
}

/* Device Panel */
.device-panel {
    position: absolute;
    bottom: 15px;
    right: 15px;
    width: 320px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    z-index: 500;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.device-panel.hidden {
    transform: translateY(120%);
}

.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px;
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
}

.panel-device-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
}

.panel-device-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.panel-device-details {
    flex: 1;
    min-width: 0;
}

.panel-device-name {
    display: block;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.panel-device-imei {
    display: block;
    font-size: 11px;
    opacity: 0.85;
    font-family: monospace;
}

.panel-close-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 8px;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s;
}

.panel-close-btn:hover {
    background: rgba(255, 255, 255, 0.35);
}

.panel-body {
    padding: 14px;
    max-height: 300px;
    overflow-y: auto;
}

.no-selection {
    text-align: center;
    padding: 30px;
    color: #9ca3af;
}

.no-selection i {
    font-size: 32px;
    margin-bottom: 10px;
    opacity: 0.3;
}

.no-selection p {
    margin: 0;
    font-size: 13px;
}

/* Panel Content Styles */
.status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-badge.online {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.offline {
    background: #f3f4f6;
    color: #6b7280;
}

.speed-display {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    color: #1976d2;
}

.speed-display i {
    color: #60a5fa;
}

/* Dynamic Indicators */
.dynamic-indicators {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    padding: 12px 0;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    margin: 12px 0;
}

.dynamic-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: 10px;
    background: #f9fafb;
}

.dynamic-indicator.active {
    background: rgba(117, 86, 214, 0.08);
}

.dynamic-indicator.inactive {
    opacity: 0.5;
}

.indicator-icon {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.indicator-icon.warning { background: rgba(251, 191, 36, 0.15); color: #d97706; }
.indicator-icon.success { background: rgba(34, 197, 94, 0.15); color: #16a34a; }
.indicator-icon.danger { background: rgba(239, 68, 68, 0.15); color: #dc2626; }
.indicator-icon.info { background: rgba(59, 130, 246, 0.15); color: #2563eb; }
.indicator-icon.primary { background: rgba(117, 86, 214, 0.15); color: #7556D6; }
.indicator-icon.secondary { background: rgba(107, 114, 128, 0.15); color: #4b5563; }

.indicator-info {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}

.indicator-label {
    font-size: 10px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.indicator-value {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
}

/* Last Position */
.last-position {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 12px;
}

.last-position i {
    color: #9ca3af;
}

/* Action Icons */
.action-icons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.action-icon-btn {
    width: 36px;
    height: 36px;
    background: #f3f4f6;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #1976d2;
    cursor: pointer;
    transition: all 0.2s ease;
}

.action-icon-btn:hover {
    background: #1976d2;
    color: #fff;
    transform: translateY(-2px);
}

/* Fullscreen Mode */
.map-section.fullscreen-mode {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    border-radius: 0 !important;
}

/* Leaflet fixes */
.leaflet-top, .leaflet-bottom {
    z-index: 400 !important;
}

/* Responsive */
@media (max-width: 1100px) {
    .monitor-content {
        flex-direction: column;
        height: auto;
    }
    
    .info-panel {
        width: 100%;
        height: auto;
        max-height: 280px;
    }
    
    .list-card {
        max-height: 180px;
    }
    
    .map-section {
        height: 500px;
        min-height: 400px;
        width: 100%;
    }
    
    .device-panel {
        left: 10px;
        right: 10px;
        width: auto;
    }
}

@media (max-width: 768px) {
    .monitor-content {
        padding: 8px;
        gap: 8px;
    }
    
    .info-panel {
        max-height: 220px;
    }
    
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .floating-header {
        padding: 6px 12px;
    }
    
    .header-actions {
        display: none;
    }
    
    .map-legend {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .map-controls {
        right: 10px;
    }
    
    .device-panel {
        bottom: 10px;
        left: 8px;
        right: 8px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Monitor page loaded - Modern Design');
    
    let map;
    let markers = {};
    let allDevices = [];
    let allUsers = [];
    let positions = {};
    let selectedDeviceId = null;
    let autoFollow = true;
    let refreshInterval = null;
    let expandedGroups = {};
    let isCurrentUserAdmin = false;
    const REFRESH_RATE = 3000;
    
    // Initialize
    initMap();
    loadCurrentUserStatus();
    loadGroups();
    loadDevices();
    loadPositions();
    startRealTimeUpdates();
    setupEventListeners();

    // Initialize Map
    function initMap() {
        map = L.map('map', {
            center: [14.6937, -17.4441],
            zoom: 12,
            zoomControl: false
        });
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        // Force map resize after initialization with multiple attempts
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        
        setTimeout(() => {
            map.invalidateSize();
        }, 500);
        
        setTimeout(() => {
            map.invalidateSize();
        }, 1000);
        
        console.log('Map initialized successfully');
    }

    // Setup event listeners
    function setupEventListeners() {
        document.getElementById('deviceSearch').addEventListener('input', debounce(filterDevices, 300));
        document.getElementById('closePanel').addEventListener('click', closePanel);
        document.getElementById('btnCenterAll').addEventListener('click', centerAllDevices);
        document.getElementById('btnAutoFollow').addEventListener('click', toggleAutoFollow);
        document.getElementById('btnZoomIn').addEventListener('click', () => map.zoomIn());
        document.getElementById('btnZoomOut').addEventListener('click', () => map.zoomOut());
        document.getElementById('btnFullscreen').addEventListener('click', toggleFullscreen);
        
        // Filters
        document.querySelectorAll('.filter-chip').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterDevices();
            });
        });
    }

    // Fullscreen
    function toggleFullscreen() {
        const mapSection = document.querySelector('.map-section');
        if (!document.fullscreenElement) {
            mapSection.requestFullscreen().then(() => {
                mapSection.classList.add('fullscreen-mode');
                document.getElementById('btnFullscreen').innerHTML = '<i class="fas fa-compress"></i>';
                setTimeout(() => map.invalidateSize(), 100);
            });
        } else {
            document.exitFullscreen().then(() => {
                mapSection.classList.remove('fullscreen-mode');
                document.getElementById('btnFullscreen').innerHTML = '<i class="fas fa-expand"></i>';
                setTimeout(() => map.invalidateSize(), 100);
            });
        }
    }

    document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
            document.querySelector('.map-section').classList.remove('fullscreen-mode');
            document.getElementById('btnFullscreen').innerHTML = '<i class="fas fa-expand"></i>';
            setTimeout(() => map.invalidateSize(), 100);
        }
    });
    
    // Load user status
    async function loadCurrentUserStatus() {
        try {
            const response = await fetch('/api/user-status');
            const data = await response.json();
            isCurrentUserAdmin = data.isAdmin === true;
        } catch (error) {
            isCurrentUserAdmin = false;
        }
    }

    // Load groups
    async function loadGroups() {
        try {
            const response = await fetch('/api/traccar/users');
            const data = await response.json();
            if (data.success) {
                allUsers = data.users || [];
            }
        } catch (error) {
            allUsers = [];
        }
    }
    
    // Load devices
    async function loadDevices() {
        try {
            const response = await fetch('/api/traccar/devices?all=true');
            const data = await response.json();
            if (data.success) {
                allDevices = data.devices || [];
                updateCounts();
                buildDeviceTree();
            }
        } catch (error) {
            allDevices = [];
        }
    }
    
    // Load positions
    async function loadPositions() {
        try {
            const response = await fetch('/api/traccar/positions');
            const data = await response.json();
            if (data.success) {
                const positionsArray = data.positions || [];
                positions = {};
                positionsArray.forEach(pos => {
                    positions[pos.deviceId] = pos;
                });
                updateMarkers();
                updateLastRefreshTime();
                
                if (selectedDeviceId) {
                    updateDevicePanel(selectedDeviceId);
                }
            }
        } catch (error) {
            console.error('Error loading positions:', error);
        }
    }
    
    // Real-time updates
    function startRealTimeUpdates() {
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(() => {
            loadPositions();
            loadDevices();
        }, REFRESH_RATE);
    }
    
    // Update time
    function updateLastRefreshTime() {
        const now = new Date();
        document.getElementById('lastUpdate').textContent = now.toLocaleTimeString('fr-FR');
    }
    
    // Update counts
    function updateCounts() {
        let moving = 0, stopped = 0, idling = 0, offline = 0;
        
        allDevices.forEach(device => {
            const iconNum = getVehicleIconNumber(device);
            if (iconNum === 0) offline++;
            else if (iconNum === 1) stopped++;
            else if (iconNum === 2) moving++;
            else if (iconNum === 3) idling++;
        });
        
        const online = allDevices.filter(d => d.status === 'online').length;
        
        document.getElementById('countMoving').textContent = moving;
        document.getElementById('countStopped').textContent = stopped;
        document.getElementById('countIdling').textContent = idling;
        document.getElementById('countOffline').textContent = offline;
        document.getElementById('countAll').textContent = allDevices.length;
        document.getElementById('countOnline').textContent = online;
    }

    // Build device tree
    function buildDeviceTree() {
        const container = document.getElementById('deviceTree');
        const search = document.getElementById('deviceSearch').value.toLowerCase();
        const activeFilter = document.querySelector('.filter-chip.active')?.dataset.filter || 'all';
        
        // Save expanded state
        if (isCurrentUserAdmin) {
            const currentExpanded = {};
            document.querySelectorAll('.group-node.expanded').forEach(node => {
                const header = node.querySelector('.group-header');
                if (header) {
                    const name = header.querySelector('.group-name').textContent;
                    currentExpanded[name] = true;
                }
            });
            expandedGroups = currentExpanded;
        }
        
        // Filter devices
        let filteredDevices = allDevices.filter(device => {
            const user = allUsers.find(u => u.id === device.userId);
            const userName = user ? user.name.toLowerCase() : 'non assigné';
            
            const matchSearch = !search || 
                device.name?.toLowerCase().includes(search) ||
                device.uniqueId?.toLowerCase().includes(search) ||
                userName.includes(search);
            
            let matchFilter = true;
            if (activeFilter === 'online') matchFilter = device.status === 'online';
            if (activeFilter === 'offline') matchFilter = device.status === 'offline';
            
            return matchSearch && matchFilter;
        });
        
        let html = '';
        
        if (isCurrentUserAdmin) {
            const grouped = {};
            grouped['Non assigné'] = filteredDevices.filter(d => !d.userId);
            
            allUsers.forEach(user => {
                const userDevices = filteredDevices.filter(d => d.userId === user.id);
                if (userDevices.length > 0) {
                    grouped[user.name] = userDevices;
                }
            });
            
            for (const [userName, devices] of Object.entries(grouped)) {
                if (devices.length > 0) {
                    const isExpanded = expandedGroups[userName] ? 'expanded' : '';
                    html += `
                        <div class="group-node ${isExpanded}">
                            <div class="group-header" onclick="toggleGroup(this)">
                                <i class="fas fa-chevron-right arrow"></i>
                                <i class="fas fa-user folder-icon"></i>
                                <span class="group-name">${userName}</span>
                                <span class="group-count">${devices.length}</span>
                            </div>
                            <div class="group-devices">
                                ${devices.map(device => renderDeviceItem(device)).join('')}
                            </div>
                        </div>
                    `;
                }
            }
        } else {
            html = filteredDevices.map(device => renderDeviceItem(device)).join('');
        }
        
        container.innerHTML = html || '<div class="tree-empty"><i class="fas fa-car"></i><p>Aucun véhicule trouvé</p></div>';
    }

    // Render device item
    function renderDeviceItem(device) {
        const pos = positions[device.id];
        const speed = pos ? Math.round(pos.speed * 1.852) : 0;
        const iconNum = getVehicleIconNumber(device);
        const isMoving = speed > 1;
        
        return `
            <div class="device-item ${selectedDeviceId === device.id ? 'selected' : ''}" 
                 data-id="${device.id}" 
                 onclick="selectDevice(${device.id})">
                <div class="device-status-icon">
                    <img src="/icons/automobile_${iconNum}.png" alt="status">
                </div>
                <div class="device-info">
                    <div class="device-name">${device.name}</div>
                    <div class="device-speed ${isMoving ? 'moving' : ''}">
                        ${device.status === 'online' ? `<i class="fas fa-tachometer-alt"></i> ${speed} km/h` : '<i class="fas fa-power-off"></i> Hors ligne'}
                    </div>
                </div>
                <span class="device-category">${getCategoryIcon(device.category)}</span>
            </div>
        `;
    }
    
    // Get vehicle icon number
    function getVehicleIconNumber(device) {
        const pos = positions[device.id];
        
        if (device.status !== 'online') return 0;
        
        const speed = pos ? (pos.speed * 1.852) : 0;
        const ignition = pos?.attributes?.ignition ?? false;
        
        if (speed > 1) return 2;
        else if (ignition) return 3;
        else return 1;
    }
    
    // Update markers
    function updateMarkers() {
        allDevices.forEach(device => {
            const pos = positions[device.id];
            if (!pos || !pos.latitude || !pos.longitude) return;
            
            const latLng = [pos.latitude, pos.longitude];
            
            if (markers[device.id]) {
                markers[device.id].setLatLng(latLng);
                markers[device.id].setIcon(createMarkerIcon(device));
                markers[device.id].setPopupContent(createPopupContent(device, pos));
            } else {
                const marker = L.marker(latLng, {
                    icon: createMarkerIcon(device)
                }).addTo(map);
                
                marker.bindPopup(createPopupContent(device, pos));
                marker.on('click', () => selectDevice(device.id));
                markers[device.id] = marker;
            }
        });
        
        buildDeviceTree();
        
        if (autoFollow && selectedDeviceId && positions[selectedDeviceId]) {
            const pos = positions[selectedDeviceId];
            map.panTo([pos.latitude, pos.longitude]);
        }
    }
    
    // Create marker icon
    function createMarkerIcon(device) {
        const iconNumber = getVehicleIconNumber(device);
        const pos = positions[device.id];
        const rotation = pos?.course || 0;
        
        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transform: rotate(${rotation}deg);
                ">
                    <img src="/icons/automobile_${iconNumber}.png" 
                         style="width: 40px; height: 40px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));" 
                         alt="vehicle"/>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
    }
    
    // Create popup content
    function createPopupContent(device, pos) {
        const speed = pos ? Math.round(pos.speed * 1.852) : 0;
        return `<div style="min-width: 120px;"><h6 style="margin: 0 0 5px 0; font-weight: 600;">${device.name}</h6><p style="margin: 0; font-size: 12px; color: #666;"><i class="fas fa-tachometer-alt"></i> ${speed} km/h</p></div>`;
    }
    
    // Get category icon
    function getCategoryIcon(category) {
        const icons = {
            'car': '<i class="fas fa-car"></i>',
            'truck': '<i class="fas fa-truck"></i>',
            'motorcycle': '<i class="fas fa-motorcycle"></i>',
            'bus': '<i class="fas fa-bus"></i>',
            'person': '<i class="fas fa-user"></i>',
            'default': '<i class="fas fa-map-marker-alt"></i>'
        };
        return icons[category] || icons['default'];
    }
    
    // Select device
    window.selectDevice = function(deviceId) {
        selectedDeviceId = deviceId;
        
        document.querySelectorAll('.device-item').forEach(item => {
            item.classList.remove('selected');
        });
        document.querySelector(`.device-item[data-id="${deviceId}"]`)?.classList.add('selected');
        
        const pos = positions[deviceId];
        if (pos && pos.latitude && pos.longitude) {
            map.setView([pos.latitude, pos.longitude], 16);
            if (markers[deviceId]) markers[deviceId].openPopup();
        }
        
        updateDevicePanel(deviceId);
    };
    
    // Update device panel
    function updateDevicePanel(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        const pos = positions[deviceId];
        
        if (!device) return;
        
        const iconNum = getVehicleIconNumber(device);
        document.getElementById('panelIcon').innerHTML = `<img src="/icons/automobile_${iconNum}.png" style="width: 24px; height: 24px;">`;
        document.getElementById('panelDeviceName').textContent = device.name;
        document.getElementById('panelDeviceImei').textContent = device.uniqueId || '-';
        
        const speed = pos ? Math.round(pos.speed * 1.852) : 0;
        const isOnline = device.status === 'online';
        const lastUpdate = pos?.fixTime ? new Date(pos.fixTime).toLocaleString('fr-FR') : '-';
        
        const monitorAttrs = device.attributes?.monitorAttributes || [];
        const posAttrs = pos?.attributes || {};
        let dynamicIndicators = generateDynamicIndicators(monitorAttrs, posAttrs);
        
        document.getElementById('panelBody').innerHTML = `
            <div class="status-row">
                <div class="status-badge ${isOnline ? 'online' : 'offline'}">
                    ${isOnline ? 'EN LIGNE' : 'HORS LIGNE'}
                </div>
                <div class="speed-display">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>${speed} km/h</span>
                </div>
            </div>
            ${dynamicIndicators ? `<div class="dynamic-indicators">${dynamicIndicators}</div>` : ''}
            <div class="last-position">
                <i class="fas fa-clock"></i>
                <span>${lastUpdate}</span>
            </div>
            <div class="action-icons">
                <button class="action-icon-btn" onclick="viewHistory(${deviceId})" title="Historique">
                    <i class="fas fa-history"></i>
                </button>
                <button class="action-icon-btn" onclick="sendCommand(${deviceId})" title="Commande">
                    <i class="fas fa-terminal"></i>
                </button>
                <button class="action-icon-btn" onclick="viewDeviceDetails(${deviceId})" title="Détails">
                    <i class="fas fa-info-circle"></i>
                </button>
                <button class="action-icon-btn" onclick="viewGeofences(${deviceId})" title="Géozones">
                    <i class="fas fa-draw-polygon"></i>
                </button>
                <button class="action-icon-btn" onclick="viewAlerts(${deviceId})" title="Alertes">
                    <i class="fas fa-bell"></i>
                </button>
            </div>
        `;
    }
    
    // Generate dynamic indicators
    function generateDynamicIndicators(monitorAttrs, posAttrs) {
        if (!monitorAttrs || monitorAttrs.length === 0) return '';
        
        const attrConfig = {
            ignition: { icon: 'fa-key', label: 'Moteur', color: 'warning', getValue: (v) => v ? 'ON' : 'OFF', isActive: (v) => v === true },
            batteryLevel: { icon: 'fa-battery-three-quarters', label: 'Batterie', color: 'success', getValue: (v) => v !== undefined ? `${Math.round(v)}%` : '-', isActive: (v) => v > 20 },
            battery: { icon: 'fa-car-battery', label: 'Batterie V', color: 'info', getValue: (v) => v !== undefined ? `${Number(v).toFixed(1)}V` : '-', isActive: (v) => v > 11 },
            fuel: { icon: 'fa-gas-pump', label: 'Carburant', color: 'danger', getValue: (v) => v !== undefined ? `${Math.round(v)}%` : '-', isActive: (v) => v > 15 },
            alarm: { icon: 'fa-bell', label: 'Alarme', color: 'danger', getValue: (v) => v || '-', isActive: (v) => !!v },
            charge: { icon: 'fa-plug', label: 'Charge', color: 'primary', getValue: (v) => v ? 'Oui' : 'Non', isActive: (v) => v === true },
            blocked: { icon: 'fa-lock', label: 'Bloqué', color: 'secondary', getValue: (v) => v ? 'Oui' : 'Non', isActive: (v) => v === true },
            door: { icon: 'fa-door-open', label: 'Porte', color: 'warning', getValue: (v) => v ? 'Ouverte' : 'Fermée', isActive: (v) => v === true },
            motion: { icon: 'fa-running', label: 'Mouvement', color: 'success', getValue: (v) => v ? 'Oui' : 'Non', isActive: (v) => v === true },
            temperature: { icon: 'fa-thermometer-half', label: 'Temp.', color: 'danger', getValue: (v) => v !== undefined ? `${Number(v).toFixed(1)}°C` : '-', isActive: () => true },
            sat: { icon: 'fa-satellite', label: 'Satellites', color: 'info', getValue: (v) => v !== undefined ? v : '0', isActive: (v) => v > 0 },
            power: { icon: 'fa-bolt', label: 'Alim.', color: 'warning', getValue: (v) => v !== undefined ? `${Number(v).toFixed(1)}V` : '-', isActive: (v) => v > 10 }
        };
        
        let indicators = '';
        monitorAttrs.forEach(attr => {
            const config = attrConfig[attr];
            if (!config) return;
            
            const value = posAttrs[attr];
            const isActive = config.isActive(value);
            const displayValue = config.getValue(value);
            
            indicators += `
                <div class="dynamic-indicator ${isActive ? 'active' : 'inactive'}">
                    <div class="indicator-icon ${config.color}">
                        <i class="fas ${config.icon}"></i>
                    </div>
                    <div class="indicator-info">
                        <span class="indicator-label">${config.label}</span>
                        <span class="indicator-value">${displayValue}</span>
                    </div>
                </div>
            `;
        });
        
        return indicators;
    }
    
    // Close panel
    function closePanel() {
        selectedDeviceId = null;
        document.querySelectorAll('.device-item').forEach(item => {
            item.classList.remove('selected');
        });
        document.getElementById('panelDeviceName').textContent = 'Sélectionnez un véhicule';
        document.getElementById('panelDeviceImei').textContent = '-';
        document.getElementById('panelIcon').innerHTML = '<i class="fas fa-car"></i>';
        document.getElementById('panelBody').innerHTML = `
            <div class="no-selection">
                <i class="fas fa-hand-pointer"></i>
                <p>Cliquez sur un véhicule</p>
            </div>
        `;
    }
    
    // Center all devices
    function centerAllDevices() {
        const bounds = [];
        for (const deviceId in markers) {
            bounds.push(markers[deviceId].getLatLng());
        }
        if (bounds.length > 0) {
            map.fitBounds(L.latLngBounds(bounds), { padding: [50, 50] });
        }
    }
    
    // Toggle auto follow
    function toggleAutoFollow() {
        autoFollow = !autoFollow;
        document.getElementById('btnAutoFollow').classList.toggle('active', autoFollow);
    }
    
    // Toggle group
    window.toggleGroup = function(header) {
        header.parentElement.classList.toggle('expanded');
    };
    
    // Filter devices
    function filterDevices() {
        buildDeviceTree();
    }
    
    // Actions
    window.viewHistory = function(deviceId) {
        window.location.href = `/history?id=${deviceId}`;
    };
    
    window.sendCommand = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        alert(`Commande vers ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    window.viewDeviceDetails = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        alert(`Détails de ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    window.viewGeofences = function(deviceId) {
        window.location.href = '/geofence';
    };
    
    window.viewAlerts = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        alert(`Alertes de ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    // Debounce
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    
    // Visibility change
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                document.getElementById('liveIndicator').classList.add('paused');
            }
        } else {
            loadPositions();
            startRealTimeUpdates();
            document.getElementById('liveIndicator').classList.remove('paused');
        }
    });
});
</script>
@endpush

@endsection
