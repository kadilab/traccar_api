@extends('layouts.app')

@section('title', __('messages.monitor.track') . ' - Traccar TF')

@section('content')

<div class="tracking-container">
    <!-- Header -->
    <div class="tracking-header">
        <div class="header-left">
            <a href="{{ route('device') }}" class="btn-back">
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
                <span class="status-text" id="statusText">{{ __('messages.common.loading') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="tracking-content">
        <!-- Info Panel (Left) -->
        <div class="info-panel">
            <!-- Device Stats Card -->
            <div class="info-card">
                <h3 class="info-card-title">
                    <i class="fas fa-info-circle"></i>
                    {{ __('messages.device.title') }}
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.common.status') }}</span>
                        <span class="info-value" id="infoStatus">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.device.last_update') }}</span>
                        <span class="info-value" id="infoLastUpdate">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.device.model') }}</span>
                        <span class="info-value" id="infoModel">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.device.phone') }}</span>
                        <span class="info-value" id="infoPhone">--</span>
                    </div>
                </div>
            </div>

            <!-- Position Card -->
            <div class="info-card">
                <h3 class="info-card-title">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ __('messages.monitor.last_position') }}
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.geofence.latitude') }}</span>
                        <span class="info-value" id="infoLatitude">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.geofence.longitude') }}</span>
                        <span class="info-value" id="infoLongitude">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.monitor.speed') }}</span>
                        <span class="info-value" id="infoSpeed">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.monitor.course') }}</span>
                        <span class="info-value" id="infoCourse">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.monitor.altitude') }}</span>
                        <span class="info-value" id="infoAltitude">--</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.monitor.address') }}</span>
                        <span class="info-value address" id="infoAddress">--</span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="info-card actions-card">
                <div class="action-buttons-grid">
                    <button class="action-btn" id="btnCenterMap" title="{{ __('messages.monitor.center_map') }}">
                        <i class="fas fa-crosshairs"></i>
                        <span>{{ __('messages.monitor.center_map') }}</span>
                    </button>
                    <button class="action-btn" id="btnRefresh" title="{{ __('messages.common.refresh') }}">
                        <i class="fas fa-sync-alt"></i>
                        <span>{{ __('messages.common.refresh') }}</span>
                    </button>
                    <a href="#" class="action-btn" id="btnHistory" title="{{ __('messages.monitor.view_history') }}">
                        <i class="fas fa-history"></i>
                        <span>{{ __('messages.monitor.view_history') }}</span>
                    </a>
                    <button class="action-btn" id="btnToggleFollow" title="Auto-Follow">
                        <i class="fas fa-location-arrow"></i>
                        <span>Auto-Follow</span>
                    </button>
                </div>
            </div>

            <!-- Real-time Indicator -->
            <div class="realtime-card">
                <div class="realtime-indicator active" id="realtimeIndicator">
                    <span class="realtime-dot"></span>
                    <span class="realtime-text">{{ __('messages.monitor.title') }}</span>
                </div>
                <p class="realtime-info">{{ __('messages.common.refresh') }}: <span id="refreshRate">5s</span></p>
            </div>
        </div>

        <!-- Map Section (Right) -->
        <div class="map-section">
            <div id="trackingMap" class="tracking-map"></div>
            
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
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* Tracking Page Styles */
.tracking-container {
    padding: 20px;
    padding-top: 80px;
    max-width: 1600px;
    margin: 0 auto;
    min-height: calc(100vh - 40px);
}

/* Header */
.tracking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    padding: 20px 30px;
    border-radius: 12px;
    color: #fff;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
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

.status-badge.online .status-dot {
    background: #3b82f6;
}

.status-badge.offline .status-dot {
    background: #ef4444;
    animation: none;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

/* Main Content */
.tracking-content {
    display: flex;
    gap: 20px;
    height: calc(100vh - 200px);
    min-height: 500px;
}

/* Info Panel */
.info-panel {
    width: 350px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.info-card-title {
    margin: 0 0 15px;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card-title i {
    color: #3b82f6;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item:has(.address) {
    grid-column: span 2;
}

.info-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.info-value {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
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
    .tracking-container {
        padding: 15px;
        padding-top: 70px;
    }
    
    .tracking-content {
        gap: 15px;
        height: auto;
    }
    
    .info-panel {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .info-card {
        flex: 1;
        min-width: 250px;
    }
    
    .map-section {
        height: 400px;
        order: 1;
    }
}

@media (max-width: 991px) {
    .tracking-header {
        gap: 15px;
        padding: 15px 20px;
    }
    
    .device-info h1 {
        font-size: 20px;
    }
    
    .device-info h1 i {
        font-size: 22px;
    }
    
    .info-panel {
        flex-direction: column;
    }
    
    .info-card {
        min-width: 100%;
    }
    
    .action-buttons-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .map-section {
        height: 450px;
    }
    
    .speed-gauge {
        width: 85px;
        height: 85px;
    }
    
    .speed-value {
        font-size: 28px;
    }
}

@media (max-width: 768px) {
    .tracking-container {
        padding: 10px;
        padding-top: 65px;
    }
    
    .tracking-header {
        flex-direction: column;
        gap: 12px;
        padding: 12px 15px;
        margin-bottom: 15px;
    }
    
    .header-left {
        width: 100%;
        gap: 12px;
    }
    
    .device-info h1 {
        font-size: 18px;
        gap: 8px;
    }
    
    .device-info h1 i {
        font-size: 20px;
    }
    
    .btn-back {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .device-id {
        font-size: 13px;
    }
    
    .status-badge {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .tracking-content {
        gap: 12px;
    }
    
    .info-panel {
        flex-direction: column;
        gap: 12px;
    }
    
    .info-card {
        padding: 15px;
    }
    
    .info-card-title {
        font-size: 14px;
        margin-bottom: 12px;
    }
    
    .info-grid {
        gap: 10px;
    }
    
    .info-label {
        font-size: 11px;
    }
    
    .info-value {
        font-size: 13px;
    }
    
    .actions-card {
        padding: 12px;
    }
    
    .action-buttons-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .action-btn {
        padding: 10px;
        font-size: 12px;
        gap: 6px;
    }
    
    .action-btn i {
        font-size: 14px;
    }
    
    .realtime-card {
        padding: 12px 15px;
    }
    
    .realtime-indicator {
        gap: 8px;
        font-size: 14px;
    }
    
    .realtime-info {
        font-size: 12px;
    }
    
    .map-section {
        height: 350px;
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
        width: 80px;
        height: 80px;
        border: 3px solid #3b82f6;
    }
    
    .speed-value {
        font-size: 26px;
    }
    
    .speed-unit {
        font-size: 11px;
    }
}

@media (max-width: 600px) {
    .tracking-container {
        padding: 8px;
        padding-top: 60px;
    }
    
    .tracking-header {
        padding: 10px 12px;
        margin-bottom: 12px;
        border-radius: 8px;
    }
    
    .device-info h1 {
        font-size: 16px;
    }
    
    .device-id {
        font-size: 12px;
    }
    
    .btn-back {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    
    .status-badge {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .header-left {
        gap: 10px;
    }
    
    .info-card {
        padding: 12px;
        border-radius: 8px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .info-item:has(.address) {
        grid-column: span 1;
    }
    
    .info-card-title {
        font-size: 13px;
        margin-bottom: 10px;
    }
    
    .info-label {
        font-size: 10px;
    }
    
    .info-value {
        font-size: 12px;
    }
    
    .action-btn {
        padding: 8px 10px;
        font-size: 11px;
        gap: 4px;
        border-radius: 6px;
    }
    
    .action-btn i {
        font-size: 12px;
    }
    
    .map-section {
        height: 300px;
        border-radius: 8px;
    }
    
    .map-control-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .speed-gauge {
        width: 70px;
        height: 70px;
        border: 2px solid #3b82f6;
    }
    
    .speed-value {
        font-size: 22px;
    }
    
    .speed-unit {
        font-size: 10px;
    }
}

@media (max-width: 400px) {
    .tracking-header {
        padding: 8px 10px;
        gap: 10px;
    }
    
    .device-info h1 {
        font-size: 14px;
        gap: 6px;
    }
    
    .device-info h1 i {
        font-size: 16px;
    }
    
    .btn-back {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .status-badge {
        padding: 5px 10px;
        font-size: 12px;
        gap: 6px;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
    }
    
    .info-card {
        padding: 10px;
    }
    
    .info-grid {
        gap: 6px;
    }
    
    .info-label {
        font-size: 9px;
    }
    
    .info-value {
        font-size: 11px;
    }
    
    .map-section {
        height: 280px;
    }
    
    .speed-gauge {
        width: 65px;
        height: 65px;
    }
    
    .speed-value {
        font-size: 20px;
    }
}

/* RTL Support */
[dir="rtl"] .btn-back:hover {
    transform: translateX(3px);
}

[dir="rtl"] .header-left {
    flex-direction: row-reverse;
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
        alert('{{ __("messages.device.no_devices") }}');
        window.location.href = '{{ route("device") }}';
        return;
    }

    let map = null;
    let marker = null;
    let device = null;
    let position = null;
    let autoFollow = true;
    let refreshInterval = null;
    const REFRESH_RATE = 5000; // 5 seconds

    // Initialize map
    function initMap() {
        map = L.map('trackingMap').setView([36.7538, 3.0588], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Custom car icon
        const carIcon = L.divIcon({
            className: 'custom-car-marker',
            html: `<div class="car-marker-container">
                <div class="car-marker-pulse"></div>
                <div class="car-marker-icon">
                    <i class="fas fa-car"></i>
                </div>
            </div>`,
            iconSize: [50, 50],
            iconAnchor: [25, 25]
        });

        marker = L.marker([36.7538, 3.0588], { icon: carIcon }).addTo(map);
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
        document.querySelector('#deviceName span').textContent = device.name || 'Unknown';
        document.getElementById('deviceIdentifier').textContent = `IMEI: ${device.uniqueId || '--'}`;
        
        // Status badge
        const statusBadge = document.getElementById('statusBadge');
        const statusText = document.getElementById('statusText');
        const status = device.status || 'unknown';
        
        statusBadge.className = `status-badge ${status}`;
        statusText.textContent = getStatusLabel(status);
        
        // Info panel
        document.getElementById('infoStatus').innerHTML = `<span class="status-badge-mini status-${status}">${getStatusLabel(status)}</span>`;
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
        const addrElem = document.getElementById('infoAddress');
        
        if (latElem) latElem.textContent = position.latitude?.toFixed(6) || '--';
        if (lngElem) lngElem.textContent = position.longitude?.toFixed(6) || '--';
        
        const speedKmh = (position.speed * 1.852).toFixed(1); // knots to km/h
        if (speedElem) speedElem.textContent = `${speedKmh} km/h`;
        document.getElementById('speedGaugeValue').textContent = Math.round(speedKmh);
        
        if (courseElem) courseElem.textContent = `${position.course?.toFixed(0) || 0}°`;
        if (altElem) altElem.textContent = `${position.altitude?.toFixed(0) || 0} m`;
        if (addrElem) addrElem.textContent = position.address || '{{ __("messages.common.loading") }}...';
        
        // If no address, try to get it
        if (!position.address && addrElem) {
            reverseGeocode(position.latitude, position.longitude);
        }
    }

    // Update map
    function updateMap() {
        if (!position || !map || !marker) return;
        
        const lat = position.latitude;
        const lng = position.longitude;
        
        // Update marker position
        marker.setLatLng([lat, lng]);
        
        // Recreate icon with new rotation
        const carIcon = L.divIcon({
            className: 'custom-car-marker',
            html: `<div class="car-marker-container" style="transform: rotate(${position.course || 0}deg);">
                <div class="car-marker-pulse"></div>
                <div class="car-marker-icon">
                    <i class="fas fa-car"></i>
                </div>
            </div>`,
            iconSize: [50, 50],
            iconAnchor: [25, 25]
        });
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
        document.querySelector('#deviceName span').textContent = message;
    }

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
