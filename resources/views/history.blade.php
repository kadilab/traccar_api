@extends('layouts.app')

@section('title', __('messages.history.title') . ' - Traccar TF')

@section('content')

<div class="history-container">
    <!-- Main Content -->
    <div class="history-content">
        <!-- Info Panel (Left) -->
        <div class="info-panel">
            <!-- Device Info Card -->
            <div class="info-card device-card">
                <div class="card-header-modern">
                    <div class="card-icon device-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="card-title-group">
                        <h3 class="card-title-modern" id="deviceName">{{ __('messages.common.loading') }}...</h3>
                        <span class="card-subtitle" id="deviceIdentifier">--</span>
                    </div>
                </div>
            </div>

            <!-- Date Filter Card -->
            <div class="info-card filter-card">
                <div class="card-header-modern">
                    <div class="card-icon filter-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-title-group">
                        <h3 class="card-title-modern">{{ __('messages.history.filters') }}</h3>
                    </div>
                </div>
                <div class="filter-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-calendar"></i> {{ __('messages.history.date_from') }}</label>
                            <input type="date" id="dateFrom" class="filter-input">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-clock"></i> {{ __('messages.history.time_from') }}</label>
                            <input type="time" id="timeFrom" class="filter-input" value="00:00">
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-calendar"></i> {{ __('messages.history.date_to') }}</label>
                            <input type="date" id="dateTo" class="filter-input">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-clock"></i> {{ __('messages.history.time_to') }}</label>
                            <input type="time" id="timeTo" class="filter-input" value="23:59">
                        </div>
                    </div>
                    <div class="quick-filters">
                        <button class="quick-btn" id="btnToday">
                            <i class="fas fa-calendar-day"></i> {{ __('messages.history.today') }}
                        </button>
                        <button class="quick-btn" id="btnYesterday">
                            <i class="fas fa-calendar-minus"></i> {{ __('messages.history.yesterday') }}
                        </button>
                        <button class="quick-btn" id="btnWeek">
                            <i class="fas fa-calendar-week"></i> 7 jours
                        </button>
                    </div>
                    <button class="btn-load-history" id="btnLoadHistory">
                        <i class="fas fa-search"></i> {{ __('messages.history.load_history') }}
                    </button>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="info-card stats-card">
                <div class="card-header-modern">
                    <div class="card-icon stats-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="card-title-group">
                        <h3 class="card-title-modern">Statistiques</h3>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-box distance-stat">
                        <div class="stat-icon"><i class="fas fa-road"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="totalDistance">0 km</span>
                            <span class="stat-label">Distance</span>
                        </div>
                    </div>
                    <div class="stat-box duration-stat">
                        <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="totalDuration">0h 0m</span>
                            <span class="stat-label">Durée</span>
                        </div>
                    </div>
                    <div class="stat-box speed-stat">
                        <div class="stat-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="avgSpeed">0 km/h</span>
                            <span class="stat-label">Moy.</span>
                        </div>
                    </div>
                    <div class="stat-box maxspeed-stat">
                        <div class="stat-icon"><i class="fas fa-bolt"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="maxSpeed">0 km/h</span>
                            <span class="stat-label">Max</span>
                        </div>
                    </div>
                    <div class="stat-box points-stat">
                        <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="totalPoints">0</span>
                            <span class="stat-label">Points</span>
                        </div>
                    </div>
                    <div class="stat-box stops-stat">
                        <div class="stat-icon"><i class="fas fa-flag"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" id="totalStops">0</span>
                            <span class="stat-label">Arrêts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section (Right) -->
        <div class="map-section">
            <div id="historyMap" class="history-map"></div>
            
            <!-- Floating Header -->
            <div class="floating-header">
                <a href="{{ route('monitor') }}" class="btn-back" title="Retour">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <span class="title-text">Historique des trajets</span>
                </div>
                <div class="status-badge" id="statusBadge">
                    <span class="status-dot"></span>
                    <span class="status-text">{{ __('messages.history.waiting') }}</span>
                </div>
            </div>
            
            <!-- Map Actions Overlay -->
            <div class="map-actions-overlay">
                <button class="map-action-btn" id="btnCenterRoute" title="Centrer sur le trajet">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
                <button class="map-action-btn active" id="btnToggleStops" title="Afficher/Masquer les arrêts">
                    <i class="fas fa-flag"></i>
                </button>
                <button class="map-action-btn" id="btnExport" title="Exporter CSV">
                    <i class="fas fa-download"></i>
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

            <!-- Playback Controls -->
            <div class="playback-panel">
                <!-- Single Row Layout -->
                <div class="playback-row">
                    <!-- Speed Buttons -->
                    <div class="speed-control">
                        <button class="speed-btn" data-speed="0.5">0.5x</button>
                        <button class="speed-btn active" data-speed="1">1x</button>
                        <button class="speed-btn" data-speed="2">2x</button>
                        <button class="speed-btn" data-speed="5">5x</button>
                        <button class="speed-btn" data-speed="10">10x</button>
                    </div>

                    <!-- Playback Buttons -->
                    <div class="playback-controls">
                        <button class="playback-btn" id="btnRewind" title="Reculer">
                            <i class="fas fa-backward"></i>
                        </button>
                        <button class="playback-btn play-btn" id="btnPlayPause" title="Lecture/Pause">
                            <i class="fas fa-play" id="playPauseIcon"></i>
                        </button>
                        <button class="playback-btn" id="btnForward" title="Avancer">
                            <i class="fas fa-forward"></i>
                        </button>
                        <button class="playback-btn" id="btnStop" title="Stop">
                            <i class="fas fa-stop"></i>
                        </button>
                    </div>

                    <!-- Timeline -->
                    <div class="timeline-section">
                        <span class="time-label" id="startTimeLabel">00:00</span>
                        <div class="timeline-track">
                            <div class="timeline-progress" id="timelineProgress"></div>
                            <input type="range" id="timelineSlider" min="0" max="100" value="0" class="timeline-slider">
                        </div>
                        <span class="time-label" id="endTimeLabel">00:00</span>
                    </div>

                    <!-- Current Info -->
                    <div class="current-info">
                        <span class="info-time" id="currentTime">--:--:--</span>
                        <span class="info-speed" id="currentSpeed">0 km/h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* History Page - Modern Design */
.history-container {
    padding: 0;
    padding-top: 60px;
    max-width: 100%;
    margin: 0;
    min-height: calc(100vh - 60px);
    position: relative;
}

/* Main Content Layout */
.history-content {
    display: flex;
    gap: 15px;
    height: calc(100vh - 60px);
    min-height: 500px;
    padding: 10px;
}

/* Info Panel (Left) */
.info-panel {
    width: 300px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    max-height: calc(100vh - 80px);
}

.info-panel::-webkit-scrollbar {
    width: 6px;
}

.info-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.info-panel::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.info-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-radius: 0px;
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

.filter-icon {
    background: linear-gradient(135deg, #1976d2 0%, #7c3aed 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.stats-icon {
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
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-subtitle {
    font-size: 11px;
    color: #6b7280;
    margin-top: 2px;
    display: block;
}

/* Filter Form */
.filter-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.filter-group label {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 4px;
}

.filter-group label i {
    color: #1976d2;
    font-size: 10px;
}

.filter-input {
    padding: 8px 10px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 12px;
    transition: all 0.2s ease;
    width: 50%;
}

.filter-input:focus {
    outline: none;
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.quick-filters {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.quick-btn {
    flex: 1;
    min-width: 80px;
    padding: 8px 10px;
    background: #f3f4f6;
    border: none;
    border-radius: 0px;
    font-size: 11px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.quick-btn:hover {
    background: #e5e7eb;
}

.quick-btn.active {
    background: #5007fa;
    color: #fff;
}

.btn-load-history {
    width: 100%;
    padding: 12px 16px;
    background: linear-gradient(135deg, #1976d2 0%, #1c6cbc 100%);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.btn-load-history:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.stat-box {
    background: #f9fafb;
    border-radius: 10px;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s ease;
}

.stat-box:hover {
    background: #f3f4f6;
    transform: scale(1.02);
}

.stat-box .stat-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.distance-stat .stat-icon {
    background: linear-gradient(135deg, #1976d2 0%, #7c3aed 100%);
    color: #fff;
}

.duration-stat .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
}

.speed-stat .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
}

.maxspeed-stat .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
}

.points-stat .stat-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
}

.stops-stat .stat-icon {
    background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    color: #fff;
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-box .stat-value {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
}

.stat-box .stat-label {
    font-size: 10px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Map Section */
.map-section {
    flex: 1;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

.history-map {
    width: 100%;
    flex: 1;
    min-height: 400px;
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
    background: #1976d2;
    color: #fff;
}

.header-title {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.title-text {
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
}

.status-badge {
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

.status-badge.loading {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.ready {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.playing {
    background: #fef3c7;
    color: #92400e;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #9ca3af;
    border-radius: 50%;
}

.status-badge.loading .status-dot {
    background: #3b82f6;
    animation: pulse 1.5s infinite;
}

.status-badge.ready .status-dot {
    background: #10b981;
}

.status-badge.playing .status-dot {
    background: #f59e0b;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}

/* Map Actions Overlay */
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
    background: #1976d2;
    color: #fff;
    transform: scale(1.05);
}

.map-action-btn.active {
    background: #1976d2;
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
    background: #1976d2;
    color: #fff;
}

/* Speed Gauge Overlay */
.speed-gauge-overlay {
    position: absolute;
    bottom: 70px;
    left: 15px;
    z-index: 400;
}

.speed-gauge {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    width: 70px;
    height: 70px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
    border: 3px solid #1976d2;
}

.speed-value {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.speed-unit {
    font-size: 9px;
    color: #6b7280;
    font-weight: 600;
}

/* Playback Panel - Compact Single Row */
.playback-panel {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    padding: 8px 15px;
}

.playback-row {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Speed Control */
.speed-control {
    display: flex;
    gap: 3px;
    flex-shrink: 0;
}

.speed-btn {
    padding: 5px 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.speed-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.speed-btn.active {
    background: #1976d2;
    border-color: transparent;
    color: #fff;
}

/* Playback Controls */
.playback-controls {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}

.playback-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 50%;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.playback-btn:hover {
    background: #1976d2;
    border-color: #1976d2;
}

.playback-btn.play-btn {
    width: 40px;
    height: 40px;
    font-size: 14px;
    background: #1976d2;
    border-color: transparent;
}

.playback-btn.play-btn:hover {
    background: #1565c0;
    transform: scale(1.05);
}

/* Timeline Section */
.timeline-section {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 150px;
}

.time-label {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.6);
    font-family: monospace;
    white-space: nowrap;
}

.timeline-track {
    flex: 1;
    position: relative;
    height: 6px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 3px;
}

.timeline-progress {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    background: #1976d2;
    border-radius: 3px;
    width: 0%;
    transition: width 0.1s ease;
}

.timeline-slider {
    position: absolute;
    top: -5px;
    left: 0;
    width: 100%;
    height: 16px;
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
    cursor: pointer;
    margin: 0;
}

.timeline-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 14px;
    height: 14px;
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
}

.timeline-slider::-moz-range-thumb {
    width: 14px;
    height: 14px;
    background: #fff;
    border-radius: 50%;
    cursor: pointer;
    border: none;
}

/* Current Info */
.current-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.info-time {
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
}

.info-speed {
    font-size: 11px;
    font-weight: 700;
    color: #10b981;
    min-width: 55px;
}

/* Leaflet Controls z-index fix */
.leaflet-top, .leaflet-bottom {
    z-index: 400 !important;
}

/* Stop Marker Style */
.stop-marker {
    background: transparent !important;
}

/* Responsive */
@media (max-width: 1200px) {
    .history-content {
        flex-direction: column;
        height: auto;
    }
    
    .info-panel {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        max-height: none;
        overflow-y: visible;
        order: 2;
    }
    
    .info-card {
        flex: 1;
        min-width: 290px;
    }
    
    .map-section {
        height: 500px;
        order: 1;
    }
}

@media (max-width: 768px) {
    .history-content {
        padding: 8px;
        gap: 10px;
    }
    
    .info-panel {
        flex-direction: column;
    }
    
    .info-card {
        min-width: 100%;
    }
    
    .floating-header {
        top: 10px;
        padding: 6px 12px;
        max-width: calc(100% - 100px);
    }
    
    .title-text {
        font-size: 12px;
    }
    
    .playback-row {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .timeline-section {
        order: 3;
        width: 100%;
        margin-top: 5px;
    }
    
    .speed-gauge-overlay {
        bottom: 80px;
    }
    
    .speed-gauge {
        width: 60px;
        height: 60px;
    }
    
    .speed-value {
        font-size: 18px;
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
        alert('Aucun appareil sélectionné');
        window.location.href = '{{ route("monitor") }}';
        return;
    }

    // Variables
    let map;
    let positions = [];
    let stops = []; // Points d'arrêt
    let polyline = null;
    let trailPolyline = null; // Ligne de trajectoire qui suit le véhicule
    let currentMarker = null;
    let startMarker = null;
    let endMarker = null;
    let stopMarkers = []; // Marqueurs des arrêts
    let playbackIndex = 0;
    let isPlaying = false;
    let playbackInterval = null;
    let playbackSpeed = 1;
    let showStops = true;

    // Initialize map
    initMap();
    
    // Load device info
    loadDeviceInfo(deviceId);
    
    // Set default dates (today)
    setTodayDates();

    // Event listeners
    document.getElementById('btnToday').addEventListener('click', () => {
        setTodayDates();
        setActiveQuickBtn('btnToday');
    });
    document.getElementById('btnYesterday').addEventListener('click', () => {
        setYesterdayDates();
        setActiveQuickBtn('btnYesterday');
    });
    document.getElementById('btnWeek').addEventListener('click', () => {
        setWeekDates();
        setActiveQuickBtn('btnWeek');
    });
    document.getElementById('btnLoadHistory').addEventListener('click', loadHistory);
    document.getElementById('btnPlayPause').addEventListener('click', togglePlayPause);
    document.getElementById('btnStop').addEventListener('click', stopPlayback);
    document.getElementById('btnRewind').addEventListener('click', rewind);
    document.getElementById('btnForward').addEventListener('click', forward);
    document.getElementById('timelineSlider').addEventListener('input', seekTimeline);

    // Speed buttons event listeners
    document.querySelectorAll('.speed-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const speed = parseFloat(this.dataset.speed);
            playbackSpeed = speed;
            // Update active button
            document.querySelectorAll('.speed-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Restart playback with new speed if playing
            if (isPlaying) {
                stopPlaybackInterval();
                startPlayback();
            }
        });
    });
    document.getElementById('btnExport').addEventListener('click', exportData);
    document.getElementById('btnCenterRoute').addEventListener('click', centerOnRoute);
    document.getElementById('btnToggleStops').addEventListener('click', toggleStopsVisibility);

    function setActiveQuickBtn(btnId) {
        document.querySelectorAll('.quick-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(btnId).classList.add('active');
    }

    // Créer l'icône du véhicule
    function createVehicleIcon(iconNumber, rotation) {
        return L.divIcon({
            className: 'custom-marker current-marker',
            html: `<div style="transform: rotate(${rotation}deg); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <img src="/icons/automobile_${iconNumber}.png" 
                     style="width: 45px; height: 45px; filter: drop-shadow(0 3px 8px rgba(139,92,246,0.5));" 
                     alt="vehicle"/>
            </div>`,
            iconSize: [45, 45],
            iconAnchor: [22, 22]
        });
    }

    // Créer l'icône d'arrêt (drapeau rouge)
    function createStopIcon(stopNumber, duration) {
        return L.divIcon({
            className: 'stop-marker',
            html: `<div style="position: relative;">
                <div style="
                    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                    color: #fff;
                    width: 28px;
                    height: 28px;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    font-size: 11px;
                    border: 2px solid #fff;
                    box-shadow: 0 2px 8px rgba(239,68,68,0.5);
                ">
                    <span style="transform: rotate(45deg);">${stopNumber}</span>
                </div>
            </div>`,
            iconSize: [28, 28],
            iconAnchor: [4, 28]
        });
    }

    // Initialize map
    function initMap() {
        map = L.map('historyMap', {
            zoomControl: false
        }).setView([14.6937, -17.4441], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Zoom controls
        document.getElementById('btnZoomIn').addEventListener('click', () => map.zoomIn());
        document.getElementById('btnZoomOut').addEventListener('click', () => map.zoomOut());
        document.getElementById('btnFullscreen').addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.getElementById('historyMap').requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });
    }

    // Load device info
    async function loadDeviceInfo(id) {
        try {
            const response = await fetch(`/api/traccar/devices`);
            const data = await response.json();
            
            let devices = [];
            if (data.success && data.devices) {
                devices = data.devices;
            } else if (Array.isArray(data)) {
                devices = data;
            }
            
            const device = devices.find(d => d.id == id);
            
            if (device) {
                document.getElementById('deviceName').textContent = device.name;
                document.getElementById('deviceIdentifier').textContent = `IMEI: ${device.uniqueId}`;
            } else {
                document.getElementById('deviceName').textContent = 'Appareil inconnu';
                document.getElementById('deviceIdentifier').textContent = `ID: ${id}`;
            }
        } catch (error) {
            console.error('Error loading device info:', error);
            document.getElementById('deviceName').textContent = 'Erreur de chargement';
        }
    }

    // Date helpers
    function setTodayDates() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dateFrom').value = today;
        document.getElementById('dateTo').value = today;
    }

    function setYesterdayDates() {
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const dateStr = yesterday.toISOString().split('T')[0];
        document.getElementById('dateFrom').value = dateStr;
        document.getElementById('dateTo').value = dateStr;
    }

    function setWeekDates() {
        const today = new Date();
        const weekAgo = new Date();
        weekAgo.setDate(weekAgo.getDate() - 7);
        document.getElementById('dateFrom').value = weekAgo.toISOString().split('T')[0];
        document.getElementById('dateTo').value = today.toISOString().split('T')[0];
    }

    // Load history
    async function loadHistory() {
        const dateFrom = document.getElementById('dateFrom').value;
        const timeFrom = document.getElementById('timeFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        const timeTo = document.getElementById('timeTo').value;

        if (!dateFrom || !dateTo) {
            alert('Veuillez sélectionner une période');
            return;
        }

        const from = new Date(`${dateFrom}T${timeFrom}:00`).toISOString();
        const to = new Date(`${dateTo}T${timeTo}:59`).toISOString();

        updateStatus('loading', 'Chargement...');

        try {
            const response = await fetch(`/api/traccar/positions?deviceId=${deviceId}&from=${from}&to=${to}`);
            const data = await response.json();

            if (data.success && data.positions) {
                positions = data.positions;
            } else if (Array.isArray(data)) {
                positions = data;
            } else {
                console.error('Invalid response format:', data);
                alert('Erreur: Format de réponse invalide');
                updateStatus('ready', 'Erreur');
                return;
            }

            if (!positions || positions.length === 0) {
                alert('Aucune position trouvée pour cette période');
                updateStatus('ready', 'Aucune donnée');
                positions = [];
                return;
            }

            // Sort by time
            positions.sort((a, b) => new Date(a.fixTime) - new Date(b.fixTime));

            // Detect stops
            detectStops();

            // Draw route
            drawRoute();
            
            // Update statistics
            updateStatistics();
            
            // Update timeline
            updateTimeline();

            updateStatus('ready', `${positions.length} positions`);

        } catch (error) {
            console.error('Error loading history:', error);
            alert('Erreur lors du chargement de l\'historique');
            updateStatus('ready', 'Erreur');
        }
    }

    // Detect stop points (vitesse < 2 km/h pendant plus de 2 minutes)
    function detectStops() {
        stops = [];
        const STOP_SPEED_THRESHOLD = 2; // km/h
        const STOP_DURATION_THRESHOLD = 120000; // 2 minutes en ms
        
        let stopStart = null;
        let stopStartIndex = null;
        
        for (let i = 0; i < positions.length; i++) {
            const pos = positions[i];
            const speed = pos.speed * 1.852; // knots to km/h
            const time = new Date(pos.fixTime).getTime();
            
            if (speed < STOP_SPEED_THRESHOLD) {
                if (stopStart === null) {
                    stopStart = time;
                    stopStartIndex = i;
                }
            } else {
                if (stopStart !== null) {
                    const duration = time - stopStart;
                    if (duration >= STOP_DURATION_THRESHOLD) {
                        const middleIndex = Math.floor((stopStartIndex + i - 1) / 2);
                        stops.push({
                            index: middleIndex,
                            position: positions[middleIndex],
                            startTime: new Date(positions[stopStartIndex].fixTime),
                            endTime: new Date(positions[i - 1].fixTime),
                            duration: duration
                        });
                    }
                    stopStart = null;
                    stopStartIndex = null;
                }
            }
        }
        
        // Check if still stopped at the end
        if (stopStart !== null) {
            const lastTime = new Date(positions[positions.length - 1].fixTime).getTime();
            const duration = lastTime - stopStart;
            if (duration >= STOP_DURATION_THRESHOLD) {
                const middleIndex = Math.floor((stopStartIndex + positions.length - 1) / 2);
                stops.push({
                    index: middleIndex,
                    position: positions[middleIndex],
                    startTime: new Date(positions[stopStartIndex].fixTime),
                    endTime: new Date(positions[positions.length - 1].fixTime),
                    duration: duration
                });
            }
        }
        
        document.getElementById('totalStops').textContent = stops.length;
    }

    // Draw route on map
    function drawRoute() {
        // Clear existing
        clearMap();

        const latLngs = positions.map(p => [p.latitude, p.longitude]);

        // Draw full polyline (grey/faded)
        polyline = L.polyline(latLngs, {
            color: '#d1d5db',
            weight: 4,
            opacity: 0.6
        }).addTo(map);

        // Draw trail polyline (colored - will follow the vehicle)
        trailPolyline = L.polyline([], {
            color: '#1976d2',
            weight: 5,
            opacity: 0.9
        }).addTo(map);

        // Start marker (drapeau vert)
        const startIcon = L.divIcon({
            className: 'custom-marker start-marker',
            html: `<div style="
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: #fff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                border: 3px solid #fff;
                box-shadow: 0 3px 10px rgba(16,185,129,0.5);
            "><i class="fas fa-flag"></i></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
        startMarker = L.marker(latLngs[0], { icon: startIcon }).addTo(map);
        startMarker.bindPopup(`<b>🚀 Départ</b><br>${formatDateTime(positions[0].fixTime)}`);

        // End marker (drapeau à damier)
        const endIcon = L.divIcon({
            className: 'custom-marker end-marker',
            html: `<div style="
                background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
                color: #fff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                border: 3px solid #fff;
                box-shadow: 0 3px 10px rgba(31,41,55,0.5);
            "><i class="fas fa-flag-checkered"></i></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
        endMarker = L.marker(latLngs[latLngs.length - 1], { icon: endIcon }).addTo(map);
        endMarker.bindPopup(`<b>🏁 Arrivée</b><br>${formatDateTime(positions[positions.length - 1].fixTime)}`);

        // Draw stop markers
        drawStopMarkers();

        // Current position marker
        const initialCourse = positions[0]?.course || 0;
        const currentIcon = createVehicleIcon(2, initialCourse);
        currentMarker = L.marker(latLngs[0], { icon: currentIcon, zIndexOffset: 1000 }).addTo(map);

        // Fit bounds
        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });

        playbackIndex = 0;
        updateTrailPolyline();
        updateCurrentInfo();
    }

    // Draw stop markers
    function drawStopMarkers() {
        // Clear existing stop markers
        stopMarkers.forEach(marker => map.removeLayer(marker));
        stopMarkers = [];

        if (!showStops) return;

        stops.forEach((stop, index) => {
            const icon = createStopIcon(index + 1, stop.duration);
            const marker = L.marker([stop.position.latitude, stop.position.longitude], { 
                icon: icon,
                zIndexOffset: 500
            }).addTo(map);
            
            const durationMinutes = Math.round(stop.duration / 60000);
            marker.bindPopup(`
                <div style="min-width: 150px;">
                    <b style="color: #ef4444;">🚩 Arrêt #${index + 1}</b><br>
                    <small><i class="fas fa-clock"></i> Durée: ${durationMinutes} min</small><br>
                    <small><i class="fas fa-play"></i> ${formatTime(stop.startTime)}</small><br>
                    <small><i class="fas fa-stop"></i> ${formatTime(stop.endTime)}</small>
                </div>
            `);
            
            stopMarkers.push(marker);
        });
    }

    // Toggle stops visibility
    function toggleStopsVisibility() {
        showStops = !showStops;
        const btn = document.getElementById('btnToggleStops');
        btn.classList.toggle('active', showStops);
        drawStopMarkers();
    }

    // Clear map
    function clearMap() {
        if (polyline) map.removeLayer(polyline);
        if (trailPolyline) map.removeLayer(trailPolyline);
        if (currentMarker) map.removeLayer(currentMarker);
        if (startMarker) map.removeLayer(startMarker);
        if (endMarker) map.removeLayer(endMarker);
        stopMarkers.forEach(marker => map.removeLayer(marker));
        stopMarkers = [];
    }

    // Update trail polyline (ligne qui suit le véhicule)
    function updateTrailPolyline() {
        if (!trailPolyline || positions.length === 0) return;
        
        const trailLatLngs = positions.slice(0, playbackIndex + 1).map(p => [p.latitude, p.longitude]);
        trailPolyline.setLatLngs(trailLatLngs);
        
        // Update timeline progress
        const progress = (playbackIndex / (positions.length - 1)) * 100;
        document.getElementById('timelineProgress').style.width = `${progress}%`;
    }

    // Center on route
    function centerOnRoute() {
        if (polyline) {
            map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
        }
    }

    // Update statistics
    function updateStatistics() {
        let totalDistance = 0;
        let maxSpeed = 0;
        let speedSum = 0;

        for (let i = 0; i < positions.length; i++) {
            const pos = positions[i];
            const speed = pos.speed * 1.852;
            speedSum += speed;
            if (speed > maxSpeed) maxSpeed = speed;

            if (i > 0) {
                const prev = positions[i - 1];
                totalDistance += calculateDistance(
                    prev.latitude, prev.longitude,
                    pos.latitude, pos.longitude
                );
            }
        }

        const avgSpeed = positions.length > 0 ? speedSum / positions.length : 0;
        
        const startTime = new Date(positions[0].fixTime);
        const endTime = new Date(positions[positions.length - 1].fixTime);
        const durationMs = endTime - startTime;
        const hours = Math.floor(durationMs / 3600000);
        const minutes = Math.floor((durationMs % 3600000) / 60000);

        document.getElementById('totalDistance').textContent = `${totalDistance.toFixed(1)} km`;
        document.getElementById('totalDuration').textContent = `${hours}h ${minutes}m`;
        document.getElementById('avgSpeed').textContent = `${avgSpeed.toFixed(0)} km/h`;
        document.getElementById('maxSpeed').textContent = `${maxSpeed.toFixed(0)} km/h`;
        document.getElementById('totalPoints').textContent = positions.length;
    }

    // Calculate distance
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Update timeline
    function updateTimeline() {
        if (positions.length === 0) return;

        const slider = document.getElementById('timelineSlider');
        slider.max = positions.length - 1;
        slider.value = 0;

        document.getElementById('startTimeLabel').textContent = formatTime(positions[0].fixTime);
        document.getElementById('endTimeLabel').textContent = formatTime(positions[positions.length - 1].fixTime);
    }

    // Playback functions
    function togglePlayPause() {
        if (positions.length === 0) return;

        isPlaying = !isPlaying;
        const icon = document.getElementById('playPauseIcon');

        if (isPlaying) {
            icon.className = 'fas fa-pause';
            updateStatus('playing', 'Lecture');
            startPlayback();
        } else {
            icon.className = 'fas fa-play';
            updateStatus('ready', 'Pause');
            stopPlaybackInterval();
        }
    }

    function startPlayback() {
        const interval = 1000 / playbackSpeed;
        playbackInterval = setInterval(() => {
            if (playbackIndex < positions.length - 1) {
                playbackIndex++;
                updatePlaybackPosition();
            } else {
                stopPlayback();
            }
        }, interval);
    }

    function stopPlaybackInterval() {
        if (playbackInterval) {
            clearInterval(playbackInterval);
            playbackInterval = null;
        }
    }

    function stopPlayback() {
        isPlaying = false;
        document.getElementById('playPauseIcon').className = 'fas fa-play';
        stopPlaybackInterval();
        playbackIndex = 0;
        updatePlaybackPosition();
        updateStatus('ready', `${positions.length} positions`);
    }

    function rewind() {
        if (playbackIndex > 0) {
            playbackIndex = Math.max(0, playbackIndex - 10);
            updatePlaybackPosition();
        }
    }

    function forward() {
        if (playbackIndex < positions.length - 1) {
            playbackIndex = Math.min(positions.length - 1, playbackIndex + 10);
            updatePlaybackPosition();
        }
    }

    function seekTimeline() {
        playbackIndex = parseInt(document.getElementById('timelineSlider').value);
        updatePlaybackPosition();
    }

    function updatePlaybackPosition() {
        if (positions.length === 0) return;

        const pos = positions[playbackIndex];
        
        // Update marker position and icon with rotation
        if (currentMarker) {
            currentMarker.setLatLng([pos.latitude, pos.longitude]);
            const speed = pos.speed * 1.852;
            const iconNumber = speed > 1 ? 2 : 1;
            const newIcon = createVehicleIcon(iconNumber, pos.course || 0);
            currentMarker.setIcon(newIcon);
        }

        // Update trail polyline
        updateTrailPolyline();

        // Update slider
        document.getElementById('timelineSlider').value = playbackIndex;

        // Update current info
        updateCurrentInfo();

        // Center map on vehicle during playback
        if (isPlaying) {
            map.panTo([pos.latitude, pos.longitude]);
        }
    }

    function updateCurrentInfo() {
        if (positions.length === 0) return;

        const pos = positions[playbackIndex];
        const speed = (pos.speed * 1.852).toFixed(0);
        
        document.getElementById('currentTime').textContent = formatTime(pos.fixTime);
        document.getElementById('currentSpeed').textContent = `${speed} km/h`;
        document.getElementById('speedGaugeValue').textContent = speed;
    }

    // Export data
    function exportData() {
        if (positions.length === 0) {
            alert('Aucune donnée à exporter');
            return;
        }

        const csv = [
            'Index,Date/Heure,Latitude,Longitude,Vitesse (km/h),Cap',
            ...positions.map((pos, i) => 
                `${i + 1},"${formatDateTime(pos.fixTime)}",${pos.latitude},${pos.longitude},${(pos.speed * 1.852).toFixed(1)},${pos.course || ''}`
            )
        ].join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `historique_${deviceId}_${document.getElementById('dateFrom').value}.csv`;
        a.click();
    }

    // Helper functions
    function formatDateTime(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleString('fr-FR');
    }

    function formatTime(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    function updateStatus(status, text) {
        const badge = document.getElementById('statusBadge');
        badge.className = `status-badge ${status}`;
        badge.querySelector('.status-text').textContent = text;
    }
});
</script>
@endpush
