@extends('layouts.app')

@section('title', __('messages.history.title') . ' - Traccar TF')

@section('content')

<div class="history-container">
    <!-- Header -->
    <div class="history-header">
        <div class="header-left">
            <a href="{{ route('monitor') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="device-info">
                <h1 id="deviceName">
                    <i class="fas fa-car"></i>
                    <span>{{ __('messages.common.loading') }}...</span>
                </h1>
                <p class="device-id" id="deviceIdentifier">ID: --</p>
            </div>
        </div>
        <div class="header-right">
            <div class="status-badge" id="statusBadge">
                <span class="status-dot"></span>
                <span class="status-text">{{ __('messages.history.waiting') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content: Filters + Map Side by Side -->
    <div class="main-content-row">
        <!-- Filters Section (Left) -->
        <div class="filters-section">
            <div class="filter-card">
                <h3 class="filter-title">
                    <i class="fas fa-filter"></i> {{ __('messages.history.filters') }}
                </h3>
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="dateFrom">
                            <i class="fas fa-calendar-alt"></i> {{ __('messages.history.date_from') }}
                        </label>
                        <input type="date" id="dateFrom" class="filter-input">
                    </div>
                    <div class="filter-group">
                        <label for="timeFrom">
                            <i class="fas fa-clock"></i> {{ __('messages.history.time_from') }}
                        </label>
                        <input type="time" id="timeFrom" class="filter-input" value="00:00">
                    </div>
                    <div class="filter-group">
                        <label for="dateTo">
                            <i class="fas fa-calendar-alt"></i> {{ __('messages.history.date_to') }}
                        </label>
                        <input type="date" id="dateTo" class="filter-input">
                    </div>
                    <div class="filter-group">
                        <label for="timeTo">
                            <i class="fas fa-clock"></i> {{ __('messages.history.time_to') }}
                        </label>
                        <input type="time" id="timeTo" class="filter-input" value="23:59">
                    </div>
                </div>
                <div class="quick-filters">
                    <button class="btn btn-secondary" id="btnToday">
                        <i class="fas fa-calendar-day"></i> {{ __('messages.history.today') }}
                    </button>
                    <button class="btn btn-secondary" id="btnYesterday">
                        <i class="fas fa-calendar-minus"></i> {{ __('messages.history.yesterday') }}
                    </button>
                    <button class="btn btn-secondary" id="btnWeek">
                        <i class="fas fa-calendar-week"></i> {{ __('messages.history.last_7_days') }}
                    </button>
                </div>
                <button class="btn btn-primary btn-load" id="btnLoadHistory">
                    <i class="fas fa-search"></i> {{ __('messages.history.load_history') }}
                </button>
            </div>
        </div>

        <!-- Map Section (Right) -->
        <div class="map-section">
            <div id="historyMap" class="history-map"></div>
            
            <!-- Playback Controls -->
            <div class="playback-controls">
                <div class="playback-buttons">
                    <button class="playback-btn" id="btnRewind" title="{{ __('messages.history.rewind') }}">
                        <i class="fas fa-backward"></i>
                    </button>
                    <button class="playback-btn" id="btnPlayPause" title="{{ __('messages.history.play_pause') }}">
                        <i class="fas fa-play" id="playPauseIcon"></i>
                    </button>
                    <button class="playback-btn" id="btnForward" title="{{ __('messages.history.forward') }}">
                        <i class="fas fa-forward"></i>
                    </button>
                    <button class="playback-btn" id="btnStop" title="{{ __('messages.history.stop') }}">
                        <i class="fas fa-stop"></i>
                    </button>
                </div>
                
                <div class="speed-control">
                    <label>Vitesse:</label>
                    <select id="playbackSpeed" class="speed-select">
                        <option value="0.5">0.5x</option>
                        <option value="1" selected>1x</option>
                        <option value="2">2x</option>
                        <option value="5">5x</option>
                        <option value="10">10x</option>
                        <option value="20">20x</option>
                    </select>
                </div>
                
                <div class="current-info">
                    <span id="currentTime">--:--:--</span>
                    <span class="separator">|</span>
                    <span id="currentSpeed">-- km/h</span>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-section">
                <div class="timeline-track">
                    <input type="range" id="timelineSlider" min="0" max="100" value="0" class="timeline-slider">
                </div>
                <div class="timeline-labels">
                    <span id="startTimeLabel">--:--</span>
                    <span id="endTimeLabel">--:--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-icon distance">
                <i class="fas fa-road"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="totalDistance">0 km</span>
                <span class="stat-label">Distance Totale</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon duration">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="totalDuration">0h 0m</span>
                <span class="stat-label">Durée Totale</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon speed">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="avgSpeed">0 km/h</span>
                <span class="stat-label">Vitesse Moyenne</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon maxspeed">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="maxSpeed">0 km/h</span>
                <span class="stat-label">Vitesse Max</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon points">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value" id="totalPoints">0</span>
                <span class="stat-label">Points GPS</span>
            </div>
        </div>
    </div>

    <!-- Position List -->
    <div class="positions-section">
        <div class="positions-header">
            <h3><i class="fas fa-list"></i> Liste des Positions</h3>
            <button class="btn btn-sm btn-secondary" id="btnExport">
                <i class="fas fa-download"></i> Exporter
            </button>
        </div>
        <div class="positions-table-wrapper">
            <table class="positions-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date/Heure</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Vitesse</th>
                        <th>Cap</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="positionsTableBody">
                    <tr class="empty-row">
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-route"></i>
                                <p>Sélectionnez une période et chargez l'historique</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* History Page Styles */
.history-container {
    padding: 20px;
    padding-top: 80px;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    padding: 20px 30px;
    border-radius: 12px;
    color: #fff;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(117, 86, 214, 0.3);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.btn-back {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 18px;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-3px);
    color: #fff;
}

.device-info h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.device-info h1 i {
    font-size: 28px;
}

.device-id {
    margin: 5px 0 0;
    font-size: 14px;
    opacity: 0.9;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
}

.status-dot {
    width: 10px;
    height: 10px;
    background: #fbbf24;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-badge.loading .status-dot {
    background: #3b82f6;
}

.status-badge.ready .status-dot {
    background: #10b981;
}

.status-badge.playing .status-dot {
    background: #10b981;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

/* Main Content Row - Filters Left + Map Right */
.main-content-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

/* Filters Section */
.filters-section {
    width: 320px;
    flex-shrink: 0;
}

.filter-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.filter-title {
    margin: 0 0 20px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-title i {
    color: #7556D6;
}

.filter-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-group label i {
    color: #7556D6;
}

.filter-input {
    padding: 12px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    width: 100%;
}

.filter-input:focus {
    outline: none;
    border-color: #7556D6;
    box-shadow: 0 0 0 3px rgba(117, 86, 214, 0.1);
}

.quick-filters {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.quick-filters .btn {
    width: 100%;
    justify-content: center;
}

.btn-load {
    width: 100%;
    justify-content: center;
    margin-top: auto;
    padding: 15px 20px;
}

.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(117, 86, 214, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(117, 86, 214, 0.4);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-sm {
    padding: 8px 15px;
    font-size: 13px;
}

/* Map Section */
.map-section {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
}

.history-map {
    height: 400px;
    width: 100%;
    flex: 1;
}

/* Playback Controls */
.playback-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    color: #fff;
}

.playback-buttons {
    display: flex;
    gap: 10px;
}

.playback-btn {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.playback-btn:hover {
    background: #7556D6;
    border-color: #7556D6;
    transform: scale(1.1);
}

.playback-btn.active {
    background: #7556D6;
    border-color: #7556D6;
}

#btnPlayPause {
    width: 60px;
    height: 60px;
    font-size: 22px;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    border-color: transparent;
}

#btnPlayPause:hover {
    transform: scale(1.15);
}

.speed-control {
    display: flex;
    align-items: center;
    gap: 10px;
}

.speed-control label {
    font-size: 14px;
    font-weight: 600;
}

.speed-select {
    padding: 8px 15px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
}

.speed-select option {
    background: #1f2937;
    color: #fff;
}

.current-info {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 16px;
    font-weight: 600;
}

.separator {
    opacity: 0.5;
}

/* Timeline */
.timeline-section {
    padding: 20px 30px 25px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.timeline-track {
    position: relative;
}

.timeline-slider {
    width: 100%;
    height: 8px;
    -webkit-appearance: none;
    appearance: none;
    background: #e5e7eb;
    border-radius: 4px;
    cursor: pointer;
}

.timeline-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(117, 86, 214, 0.4);
    transition: transform 0.2s ease;
}

.timeline-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

.timeline-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    border-radius: 50%;
    cursor: pointer;
    border: none;
}

.timeline-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 13px;
    color: #6b7280;
    font-weight: 600;
}

/* Statistics */
.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 22px;
}

.stat-icon.distance {
    background: rgba(117, 86, 214, 0.1);
    color: #7556D6;
}

.stat-icon.duration {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.stat-icon.speed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-icon.maxspeed {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-icon.points {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 13px;
    color: #6b7280;
}

/* Positions Table */
.positions-section {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.positions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.positions-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.positions-header h3 i {
    color: #7556D6;
}

.positions-table-wrapper {
    max-height: 400px;
    overflow-y: auto;
}

.positions-table {
    width: 100%;
    border-collapse: collapse;
}

.positions-table th,
.positions-table td {
    padding: 14px 20px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.positions-table th {
    background: #f9fafb;
    font-weight: 600;
    font-size: 13px;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
}

.positions-table td {
    font-size: 14px;
    color: #4b5563;
}

.positions-table tbody tr:hover {
    background: #f0f4ff;
    cursor: pointer;
}

.positions-table tbody tr.active {
    background: rgba(117, 86, 214, 0.1);
}

.empty-row td {
    padding: 60px 20px;
}

.empty-state {
    text-align: center;
    color: #9ca3af;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #d1d5db;
}

.empty-state p {
    margin: 0;
    font-size: 15px;
}

.btn-goto {
    padding: 6px 12px;
    background: #7556D6;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-goto:hover {
    background: #5a3fb3;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    display: none;
}

.loading-overlay.active {
    display: flex;
}

.loading-spinner {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    text-align: center;
}

.loading-spinner i {
    font-size: 48px;
    color: #7556D6;
    animation: spin 1s linear infinite;
}

.loading-spinner p {
    margin: 15px 0 0;
    font-size: 16px;
    color: #374151;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 1024px) {
    .main-content-row {
        flex-direction: column;
    }
    
    .filters-section {
        width: 100%;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-filters {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .quick-filters .btn {
        flex: 1;
        min-width: 120px;
    }
}

@media (max-width: 768px) {
    .history-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        margin-top: 30px;
    }

    .header-left {
        flex-direction: column;
    }

    .playback-controls {
        flex-direction: column;
        gap: 15px;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-filters {
        flex-direction: column;
    }
    
    .quick-filters .btn {
        width: 100%;
    }

    .stats-section {
        grid-template-columns: repeat(2, 1fr);
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
    let polyline = null;
    let currentMarker = null;
    let startMarker = null;
    let endMarker = null;
    let playbackIndex = 0;
    let isPlaying = false;
    let playbackInterval = null;
    let playbackSpeed = 1;

    // Initialize map
    initMap();
    
    // Load device info
    loadDeviceInfo(deviceId);
    
    // Set default dates (today)
    setTodayDates();

    // Event listeners
    document.getElementById('btnToday').addEventListener('click', setTodayDates);
    document.getElementById('btnYesterday').addEventListener('click', setYesterdayDates);
    document.getElementById('btnWeek').addEventListener('click', setWeekDates);
    document.getElementById('btnLoadHistory').addEventListener('click', loadHistory);
    document.getElementById('btnPlayPause').addEventListener('click', togglePlayPause);
    document.getElementById('btnStop').addEventListener('click', stopPlayback);
    document.getElementById('btnRewind').addEventListener('click', rewind);
    document.getElementById('btnForward').addEventListener('click', forward);
    document.getElementById('playbackSpeed').addEventListener('change', updatePlaybackSpeed);
    document.getElementById('timelineSlider').addEventListener('input', seekTimeline);
    document.getElementById('btnExport').addEventListener('click', exportData);

    // Initialize map
    function initMap() {
        map = L.map('historyMap').setView([36.7538, 3.0588], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    }

    // Load device info
    async function loadDeviceInfo(id) {
        try {
            const response = await fetch(`/api/traccar/devices`);
            const data = await response.json();
            
            // Handle API response format
            let devices = [];
            if (data.success && data.devices) {
                devices = data.devices;
            } else if (Array.isArray(data)) {
                devices = data;
            }
            
            const device = devices.find(d => d.id == id);
            
            if (device) {
                document.querySelector('#deviceName span').textContent = device.name;
                document.getElementById('deviceIdentifier').textContent = `IMEI: ${device.uniqueId}`;
            } else {
                document.querySelector('#deviceName span').textContent = 'Appareil inconnu';
                document.getElementById('deviceIdentifier').textContent = `ID: ${id}`;
            }
        } catch (error) {
            console.error('Error loading device info:', error);
            document.querySelector('#deviceName span').textContent = 'Erreur de chargement';
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

            // Handle API response format
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

            // Draw route
            drawRoute();
            
            // Update statistics
            updateStatistics();
            
            // Update table
            updatePositionsTable();
            
            // Update timeline
            updateTimeline();

            updateStatus('ready', `${positions.length} positions`);

        } catch (error) {
            console.error('Error loading history:', error);
            alert('Erreur lors du chargement de l\'historique');
            updateStatus('ready', 'Erreur');
        }
    }

    // Draw route on map
    function drawRoute() {
        // Clear existing
        if (polyline) map.removeLayer(polyline);
        if (currentMarker) map.removeLayer(currentMarker);
        if (startMarker) map.removeLayer(startMarker);
        if (endMarker) map.removeLayer(endMarker);

        const latLngs = positions.map(p => [p.latitude, p.longitude]);

        // Draw polyline
        polyline = L.polyline(latLngs, {
            color: '#7556D6',
            weight: 4,
            opacity: 0.8
        }).addTo(map);

        // Start marker
        const startIcon = L.divIcon({
            className: 'custom-marker start-marker',
            html: '<div style="background: #10b981; color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-flag"></i></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        startMarker = L.marker(latLngs[0], { icon: startIcon }).addTo(map);
        startMarker.bindPopup(`<b>Départ</b><br>${formatDateTime(positions[0].fixTime)}`);

        // End marker
        const endIcon = L.divIcon({
            className: 'custom-marker end-marker',
            html: '<div style="background: #ef4444; color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-flag-checkered"></i></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        endMarker = L.marker(latLngs[latLngs.length - 1], { icon: endIcon }).addTo(map);
        endMarker.bindPopup(`<b>Arrivée</b><br>${formatDateTime(positions[positions.length - 1].fixTime)}`);

        // Current position marker
        const currentIcon = L.divIcon({
            className: 'custom-marker current-marker',
            html: '<div style="background: #7556D6; color: #fff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid #fff; box-shadow: 0 2px 12px rgba(117,86,214,0.5);"><i class="fas fa-car"></i></div>',
            iconSize: [35, 35],
            iconAnchor: [17, 17]
        });
        currentMarker = L.marker(latLngs[0], { icon: currentIcon }).addTo(map);

        // Fit bounds
        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });

        playbackIndex = 0;
        updateCurrentInfo();
    }

    // Update statistics
    function updateStatistics() {
        let totalDistance = 0;
        let maxSpeed = 0;
        let speedSum = 0;

        for (let i = 0; i < positions.length; i++) {
            const pos = positions[i];
            
            // Speed
            const speed = pos.speed * 1.852; // knots to km/h
            speedSum += speed;
            if (speed > maxSpeed) maxSpeed = speed;

            // Distance
            if (i > 0) {
                const prev = positions[i - 1];
                totalDistance += calculateDistance(
                    prev.latitude, prev.longitude,
                    pos.latitude, pos.longitude
                );
            }
        }

        const avgSpeed = positions.length > 0 ? speedSum / positions.length : 0;
        
        // Duration
        const startTime = new Date(positions[0].fixTime);
        const endTime = new Date(positions[positions.length - 1].fixTime);
        const durationMs = endTime - startTime;
        const hours = Math.floor(durationMs / 3600000);
        const minutes = Math.floor((durationMs % 3600000) / 60000);

        document.getElementById('totalDistance').textContent = `${totalDistance.toFixed(2)} km`;
        document.getElementById('totalDuration').textContent = `${hours}h ${minutes}m`;
        document.getElementById('avgSpeed').textContent = `${avgSpeed.toFixed(1)} km/h`;
        document.getElementById('maxSpeed').textContent = `${maxSpeed.toFixed(1)} km/h`;
        document.getElementById('totalPoints').textContent = positions.length;
    }

    // Calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Update positions table
    function updatePositionsTable() {
        const tbody = document.getElementById('positionsTableBody');
        
        if (positions.length === 0) {
            tbody.innerHTML = `
                <tr class="empty-row">
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-route"></i>
                            <p>Aucune position trouvée</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = positions.map((pos, index) => `
            <tr data-index="${index}" onclick="goToPosition(${index})">
                <td>${index + 1}</td>
                <td>${formatDateTime(pos.fixTime)}</td>
                <td>${pos.latitude.toFixed(6)}</td>
                <td>${pos.longitude.toFixed(6)}</td>
                <td>${(pos.speed * 1.852).toFixed(1)} km/h</td>
                <td>${pos.course ? pos.course.toFixed(0) + '°' : '-'}</td>
                <td><button class="btn-goto" onclick="event.stopPropagation(); goToPosition(${index})">
                    <i class="fas fa-crosshairs"></i>
                </button></td>
            </tr>
        `).join('');
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

    function updatePlaybackSpeed() {
        playbackSpeed = parseFloat(document.getElementById('playbackSpeed').value);
        if (isPlaying) {
            stopPlaybackInterval();
            startPlayback();
        }
    }

    function seekTimeline() {
        playbackIndex = parseInt(document.getElementById('timelineSlider').value);
        updatePlaybackPosition();
    }

    function updatePlaybackPosition() {
        if (positions.length === 0) return;

        const pos = positions[playbackIndex];
        
        // Update marker
        if (currentMarker) {
            currentMarker.setLatLng([pos.latitude, pos.longitude]);
        }

        // Update slider
        document.getElementById('timelineSlider').value = playbackIndex;

        // Update current info
        updateCurrentInfo();

        // Highlight table row
        document.querySelectorAll('.positions-table tbody tr').forEach((row, index) => {
            row.classList.toggle('active', index === playbackIndex);
        });
    }

    function updateCurrentInfo() {
        if (positions.length === 0) return;

        const pos = positions[playbackIndex];
        document.getElementById('currentTime').textContent = formatTime(pos.fixTime);
        document.getElementById('currentSpeed').textContent = `${(pos.speed * 1.852).toFixed(1)} km/h`;
    }

    // Go to specific position
    window.goToPosition = function(index) {
        playbackIndex = index;
        updatePlaybackPosition();
        
        const pos = positions[index];
        map.setView([pos.latitude, pos.longitude], 17);
    };

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
        return date.toLocaleTimeString('fr-FR');
    }

    function updateStatus(status, text) {
        const badge = document.getElementById('statusBadge');
        badge.className = `status-badge ${status}`;
        badge.querySelector('.status-text').textContent = text;
    }
});
</script>
@endpush
