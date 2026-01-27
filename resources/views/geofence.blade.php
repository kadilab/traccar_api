@extends('layouts.app')

@section('title', __('messages.geofence.title') . ' - Traccar TF')

@section('content')

<div class="geofence-container">
    <!-- Main Content -->
    <div class="geofence-content">
        <!-- Left Panel - Geofences List -->
        <div class="info-panel">
            <!-- Header Card -->
            <div class="info-card header-card">
                <div class="card-header-modern">
                    <div class="card-icon main-icon">
                        <i class="fas fa-draw-polygon"></i>
                    </div>
                    <div class="card-title-group">
                        <h3 class="card-title-modern">{{ __('messages.geofence.management') }}</h3>
                        <span class="card-subtitle">Zones géographiques</span>
                    </div>
                </div>
                <div class="stats-row">
                    <div class="mini-stat">
                        <span class="mini-stat-value" id="totalGeofences">0</span>
                        <span class="mini-stat-label">Total</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-value" id="polygonCount">0</span>
                        <span class="mini-stat-label">Polygones</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-value" id="circleCount">0</span>
                        <span class="mini-stat-label">Cercles</span>
                    </div>
                </div>
            </div>

            <!-- Search & Add -->
            <div class="info-card search-card">
                <div class="search-row">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchGeofence" placeholder="{{ __('messages.common.search') }}...">
                    </div>
                    <button class="btn-add-new" id="btnNewGeofence" title="{{ __('messages.geofence.new') }}">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Geofences List -->
            <div class="info-card list-card">
                <div class="geofence-list" id="geofenceList">
                    <div class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>{{ __('messages.common.loading') }}...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <div id="geofenceMap" class="geofence-map"></div>
            
            <!-- Floating Header -->
            <div class="floating-header">
                <a href="{{ route('monitor') }}" class="btn-back" title="Retour">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <span class="title-text">Géobarrières</span>
                </div>
                <div class="status-badge" id="statusBadge">
                    <span class="status-dot"></span>
                    <span class="status-text">Prêt</span>
                </div>
            </div>
            
            <!-- Map Controls -->
            <div class="map-controls">
                <button class="map-control-btn" id="btnZoomIn" title="Zoom In">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="map-control-btn" id="btnZoomOut" title="Zoom Out">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="map-control-btn" id="btnCenterMap" title="Centrer">
                    <i class="fas fa-crosshairs"></i>
                </button>
                <button class="map-control-btn" id="btnFullscreen" title="Plein écran">
                    <i class="fas fa-expand"></i>
                </button>
            </div>

            <!-- Drawing Instructions -->
            <div class="drawing-panel" id="drawingPanel" style="display: none;">
                <div class="drawing-content">
                    <div class="drawing-icon">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div class="drawing-info">
                        <span class="drawing-title">Mode Dessin</span>
                        <span class="drawing-text" id="instructionText">Cliquez pour placer les points</span>
                    </div>
                    <button class="btn-finish-draw" id="btnFinishDraw">
                        <i class="fas fa-check"></i> Terminer
                    </button>
                    <button class="btn-cancel-draw" id="btnCancelDraw">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Map Info -->
            <div class="map-info-overlay">
                <div class="info-hint">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ __('messages.geofence.draw_hint') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Panel (Right) -->
        <div class="form-panel" id="formPanel">
            <div class="panel-header">
                <div class="panel-title">
                    <div class="panel-icon">
                        <i class="fas fa-plus-circle" id="formIcon"></i>
                    </div>
                    <div class="panel-title-text">
                        <h3 id="formTitle">{{ __('messages.geofence.new') }}</h3>
                        <span class="panel-subtitle">Configuration de la zone</span>
                    </div>
                </div>
                <button class="btn-close-panel" id="btnClosePanel">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="panel-body">
                <form id="geofenceForm">
                    <input type="hidden" id="geofenceId" value="">
                    <input type="hidden" id="geofenceArea" value="">
                    
                    <!-- Name -->
                    <div class="form-group">
                        <label for="geofenceName">
                            <i class="fas fa-tag"></i> {{ __('messages.common.name') }} <span class="required">*</span>
                        </label>
                        <input type="text" id="geofenceName" class="form-input" placeholder="{{ __('messages.geofence.name_placeholder') }}" required>
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group">
                        <label for="geofenceDescription">
                            <i class="fas fa-align-left"></i> {{ __('messages.common.description') }}
                        </label>
                        <textarea id="geofenceDescription" class="form-input" rows="2" placeholder="{{ __('messages.geofence.description_placeholder') }}"></textarea>
                    </div>
                    
                    <!-- Zone Type -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-shapes"></i> {{ __('messages.geofence.zone_type') }}
                        </label>
                        <div class="type-selector">
                            <button type="button" class="type-btn active" data-type="polygon">
                                <i class="fas fa-draw-polygon"></i>
                                <span>{{ __('messages.geofence.polygon') }}</span>
                            </button>
                            <button type="button" class="type-btn" data-type="circle">
                                <i class="fas fa-circle"></i>
                                <span>{{ __('messages.geofence.circle') }}</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Circle Options -->
                    <div class="circle-options" id="circleOptions" style="display: none;">
                        <div class="options-header">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres du cercle</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="circleLat">Latitude</label>
                                <input type="number" id="circleLat" class="form-input" step="0.000001" placeholder="14.6937">
                            </div>
                            <div class="form-group half">
                                <label for="circleLng">Longitude</label>
                                <input type="number" id="circleLng" class="form-input" step="0.000001" placeholder="-17.4441">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="circleRadius">
                                <i class="fas fa-arrows-alt-h"></i> {{ __('messages.geofence.radius') }} (m)
                            </label>
                            <div class="radius-input">
                                <input type="range" id="radiusSlider" min="50" max="5000" value="500" class="radius-slider">
                                <input type="number" id="circleRadius" class="form-input radius-value" value="500" min="10" max="50000">
                            </div>
                        </div>
                        <button type="button" class="btn-place-circle" id="btnPlaceCircle">
                            <i class="fas fa-map-marker-alt"></i> Placer sur la carte
                        </button>
                    </div>
                    
                    <!-- Polygon Info -->
                    <div class="polygon-info" id="polygonInfo">
                        <div class="draw-box">
                            <div class="draw-icon">
                                <i class="fas fa-pencil-alt"></i>
                            </div>
                            <div class="draw-text">
                                <p>{{ __('messages.geofence.draw_polygon_hint') }}</p>
                            </div>
                            <button type="button" class="btn-draw" id="btnStartDraw">
                                <i class="fas fa-play"></i> {{ __('messages.geofence.draw_on_map') }}
                            </button>
                        </div>
                        <div class="coordinates-preview" id="coordinatesPreview" style="display: none;">
                            <div class="coords-header">
                                <i class="fas fa-map"></i>
                                <span>{{ __('messages.geofence.coordinates') }}</span>
                                <span class="coords-count" id="pointsCount">0 points</span>
                            </div>
                            <div class="coordinates-text" id="coordinatesText"></div>
                        </div>
                    </div>
                    
                    <!-- Color -->
                    <div class="form-group">
                        <label for="geofenceColor">
                            <i class="fas fa-palette"></i> {{ __('messages.geofence.color') }}
                        </label>
                        <div class="color-picker-row">
                            <div class="color-presets">
                                <button type="button" class="color-preset active" data-color="#1976d2" style="background: #1976d2;"></button>
                                <button type="button" class="color-preset" data-color="#7556D6" style="background: #7556D6;"></button>
                                <button type="button" class="color-preset" data-color="#10b981" style="background: #10b981;"></button>
                                <button type="button" class="color-preset" data-color="#f59e0b" style="background: #f59e0b;"></button>
                                <button type="button" class="color-preset" data-color="#ef4444" style="background: #ef4444;"></button>
                                <button type="button" class="color-preset" data-color="#ec4899" style="background: #ec4899;"></button>
                            </div>
                            <input type="color" id="geofenceColor" value="#1976d2" class="color-input">
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="panel-footer">
                <button type="button" class="btn btn-secondary" id="btnCancelForm">
                    <i class="fas fa-times"></i> {{ __('messages.common.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="btnSaveGeofence">
                    <i class="fas fa-save"></i> {{ __('messages.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-box">
        <div class="modal-icon danger">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>{{ __('messages.geofence.delete_confirm') }}</h3>
        <p>{{ __('messages.geofence.delete_warning') }} "<span id="deleteGeofenceName"></span>".</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" id="btnCancelDelete">{{ __('messages.common.cancel') }}</button>
            <button class="btn btn-danger" id="btnConfirmDelete">{{ __('messages.common.delete') }}</button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<style>
/* Geofence Page - Modern Design */
.geofence-container {
    padding: 0;
    padding-top: 60px;
    max-width: 100%;
    margin: 0;
    min-height: calc(100vh - 60px);
}

/* Main Content Layout */
.geofence-content {
    display: flex;
    gap: 15px;
    height: calc(100vh - 60px);
    min-height: 500px;
    padding: 10px;
}

/* Left Panel */
.info-panel {
    width: 320px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: calc(100vh - 80px);
}

.info-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 14px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.8);
}

/* Header Card */
.header-card {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
}

.header-card .card-header-modern {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding-bottom: 12px;
    margin-bottom: 12px;
}

.card-header-modern {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.main-icon {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}

.card-title-group {
    flex: 1;
}

.card-title-modern {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
}

.header-card .card-subtitle {
    font-size: 12px;
    opacity: 0.9;
    color: rgba(255, 255, 255, 0.9);
}

.stats-row {
    display: flex;
    gap: 10px;
}

.mini-stat {
    flex: 1;
    background: rgba(255, 255, 255, 0.15);
    padding: 10px;
    border-radius: 8px;
    text-align: center;
}

.mini-stat-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
}

.mini-stat-label {
    font-size: 10px;
    opacity: 0.9;
    text-transform: uppercase;
}

/* Search Card */
.search-card {
    padding: 10px 14px;
}

.search-row {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-box {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f3f4f6;
    padding: 10px 14px;
    border-radius: 10px;
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

.btn-add-new {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
}

.btn-add-new:hover {
    transform: scale(1.05);
}

/* List Card */
.list-card {
    flex: 1;
    overflow: hidden;
    padding: 0;
    display: flex;
    flex-direction: column;
}

.geofence-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.geofence-list::-webkit-scrollbar {
    width: 6px;
}

.geofence-list::-webkit-scrollbar-thumb {
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

.geofence-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 6px;
    border: 2px solid transparent;
    background: #f9fafb;
}

.geofence-item:hover {
    background: #f3f4f6;
    border-color: #e5e7eb;
}

.geofence-item.active {
    background: rgba(25, 118, 210, 0.1);
    border-color: #1976d2;
}

.geofence-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-right: 12px;
    flex-shrink: 0;
}

.geofence-icon.polygon {
    background: rgba(25, 118, 210, 0.1);
    color: #1976d2;
}

.geofence-icon.circle {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.geofence-details {
    flex: 1;
    min-width: 0;
}

.geofence-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 13px;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.geofence-meta {
    font-size: 11px;
    color: #6b7280;
}

.geofence-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s;
}

.geofence-item:hover .geofence-actions {
    opacity: 1;
}

.btn-action {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 12px;
}

.btn-action.edit {
    background: #dbeafe;
    color: #3b82f6;
}

.btn-action.edit:hover {
    background: #3b82f6;
    color: #fff;
}

.btn-action.delete {
    background: #fee2e2;
    color: #ef4444;
}

.btn-action.delete:hover {
    background: #ef4444;
    color: #fff;
}

.empty-list {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
}

.empty-list i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #d1d5db;
}

/* Map Section */
.map-section {
    flex: 1;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.geofence-map {
    width: 100%;
    height: 100%;
    min-height: 500px;
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
}

.btn-back {
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

.btn-back:hover {
    background: #1976d2;
    color: #fff;
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
    background: #d1fae5;
    color: #065f46;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
}

.status-badge.drawing {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.drawing .status-dot {
    background: #f59e0b;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
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

/* Drawing Panel */
.drawing-panel {
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 500;
}

.drawing-content {
    display: flex;
    align-items: center;
    gap: 15px;
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
    padding: 12px 20px;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(25, 118, 210, 0.4);
}

.drawing-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.drawing-info {
    display: flex;
    flex-direction: column;
}

.drawing-title {
    font-weight: 700;
    font-size: 13px;
}

.drawing-text {
    font-size: 11px;
    opacity: 0.9;
}

.btn-finish-draw {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-finish-draw:hover {
    background: rgba(255, 255, 255, 0.3);
}

.btn-cancel-draw {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel-draw:hover {
    background: rgba(239, 68, 68, 0.8);
}

/* Map Info Overlay */
.map-info-overlay {
    position: absolute;
    bottom: 15px;
    left: 15px;
    z-index: 400;
}

.info-hint {
    background: rgba(255, 255, 255, 0.95);
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 12px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.info-hint i {
    color: #1976d2;
}

/* Form Panel */
.form-panel {
    width: 340px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: none;
    flex-direction: column;
    max-height: calc(100vh - 80px);
    flex-shrink: 0;
}

.form-panel.active {
    display: flex;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 12px 12px 0 0;
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.panel-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.panel-title-text h3 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
}

.panel-subtitle {
    font-size: 11px;
    color: #6b7280;
}

.btn-close-panel {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e5e7eb;
    border: none;
    border-radius: 8px;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-close-panel:hover {
    background: #d1d5db;
    color: #374151;
}

.panel-body {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.form-group label i {
    color: #1976d2;
    font-size: 11px;
}

.required {
    color: #ef4444;
}

.form-input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
}

.form-row {
    display: flex;
    gap: 10px;
}

.form-group.half {
    flex: 1;
}

/* Type Selector */
.type-selector {
    display: flex;
    gap: 10px;
}

.type-btn {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 14px 10px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    transition: all 0.2s;
}

.type-btn i {
    font-size: 20px;
    color: #9ca3af;
}

.type-btn span {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
}

.type-btn:hover {
    border-color: #1976d2;
}

.type-btn.active {
    border-color: #1976d2;
    background: rgba(25, 118, 210, 0.05);
}

.type-btn.active i,
.type-btn.active span {
    color: #1976d2;
}

/* Circle Options */
.circle-options {
    background: #f9fafb;
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 16px;
}

.options-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.options-header i {
    color: #1976d2;
}

.radius-input {
    display: flex;
    gap: 10px;
    align-items: center;
}

.radius-slider {
    flex: 1;
    height: 6px;
    -webkit-appearance: none;
    background: #e5e7eb;
    border-radius: 3px;
}

.radius-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    background: #1976d2;
    border-radius: 50%;
    cursor: pointer;
}

.radius-value {
    width: 80px;
    text-align: center;
}

.btn-place-circle {
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
    transition: all 0.2s;
}

.btn-place-circle:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Polygon Info */
.draw-box {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border: 2px dashed #1976d2;
}

.draw-icon {
    width: 50px;
    height: 50px;
    background: rgba(25, 118, 210, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
}

.draw-icon i {
    font-size: 20px;
    color: #1976d2;
}

.draw-text p {
    margin: 0 0 12px;
    font-size: 12px;
    color: #6b7280;
}

.btn-draw {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-draw:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
}

.coordinates-preview {
    margin-top: 12px;
    background: #fff;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.coords-header {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.coords-header i {
    color: #1976d2;
}

.coords-count {
    margin-left: auto;
    background: #e5e7eb;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
}

.coordinates-text {
    font-family: monospace;
    font-size: 10px;
    color: #6b7280;
    max-height: 80px;
    overflow-y: auto;
    word-break: break-all;
}

/* Color Picker */
.color-picker-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.color-presets {
    display: flex;
    gap: 6px;
}

.color-preset {
    width: 28px;
    height: 28px;
    border: 3px solid transparent;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
}

.color-preset:hover {
    transform: scale(1.1);
}

.color-preset.active {
    border-color: #1f2937;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px currentColor;
}

.color-input {
    width: 40px;
    height: 28px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    padding: 0;
}

/* Panel Footer */
.panel-footer {
    display: flex;
    gap: 10px;
    padding: 16px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

.btn {
    flex: 1;
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}

.btn-primary {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(25, 118, 210, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
}

/* Modal */
.modal-overlay {
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
}

.modal-box {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    text-align: center;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 28px;
}

.modal-icon.danger {
    background: #fee2e2;
    color: #ef4444;
}

.modal-box h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #1f2937;
}

.modal-box p {
    margin: 0 0 25px;
    color: #6b7280;
    font-size: 14px;
}

.modal-actions {
    display: flex;
    gap: 10px;
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
@media (max-width: 1200px) {
    .geofence-content {
        flex-direction: column;
        height: auto;
    }
    
    .info-panel {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        max-height: none;
    }
    
    .info-card {
        flex: 1;
        min-width: 280px;
    }
    
    .list-card {
        max-height: 300px;
    }
    
    .map-section {
        height: 500px;
    }
    
    .form-panel {
        width: 100%;
        max-height: none;
    }
}

@media (max-width: 768px) {
    .geofence-content {
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
        padding: 6px 12px;
    }
    
    .title-text {
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    let map;
    let geofences = [];
    let drawnItems;
    let drawControl;
    let currentDrawing = null;
    let editingGeofence = null;
    let selectedColor = '#1976d2';
    let isDrawing = false;
    let currentType = 'polygon';
    let tempCircle = null;
    let circleMarker = null;
    
    // Initialize
    initMap();
    loadGeofences();
    setupEventListeners();

    // Initialize Map
    function initMap() {
        map = L.map('geofenceMap', {
            zoomControl: false
        }).setView([14.6937, -17.4441], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Drawn items layer
        drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Zoom controls
        document.getElementById('btnZoomIn').addEventListener('click', () => map.zoomIn());
        document.getElementById('btnZoomOut').addEventListener('click', () => map.zoomOut());
        document.getElementById('btnCenterMap').addEventListener('click', () => {
            if (geofences.length > 0) {
                map.fitBounds(drawnItems.getBounds(), { padding: [50, 50] });
            }
        });
        document.getElementById('btnFullscreen').addEventListener('click', toggleFullscreen);
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

    // Load Geofences
    async function loadGeofences() {
        try {
            const response = await fetch('/api/traccar/geofences');
            const data = await response.json();
            
            if (data.success) {
                geofences = data.geofences || [];
            } else if (Array.isArray(data)) {
                geofences = data;
            }
            
            renderGeofenceList();
            renderGeofencesOnMap();
            updateStats();
        } catch (error) {
            console.error('Error loading geofences:', error);
            document.getElementById('geofenceList').innerHTML = `
                <div class="empty-list">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Erreur de chargement</p>
                </div>
            `;
        }
    }

    // Render list
    function renderGeofenceList() {
        const list = document.getElementById('geofenceList');
        
        if (geofences.length === 0) {
            list.innerHTML = `
                <div class="empty-list">
                    <i class="fas fa-draw-polygon"></i>
                    <p>Aucune géobarrière</p>
                </div>
            `;
            return;
        }
        
        list.innerHTML = geofences.map(geo => {
            const isCircle = geo.area && geo.area.startsWith('CIRCLE');
            const type = isCircle ? 'circle' : 'polygon';
            const typeLabel = isCircle ? 'Cercle' : 'Polygone';
            
            return `
                <div class="geofence-item" data-id="${geo.id}">
                    <div class="geofence-icon ${type}">
                        <i class="fas fa-${isCircle ? 'circle' : 'draw-polygon'}"></i>
                    </div>
                    <div class="geofence-details">
                        <div class="geofence-name">${geo.name}</div>
                        <div class="geofence-meta">${typeLabel}</div>
                    </div>
                    <div class="geofence-actions">
                        <button class="btn-action edit" onclick="editGeofence(${geo.id})" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" onclick="confirmDelete(${geo.id}, '${geo.name}')" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        // Click to select
        list.querySelectorAll('.geofence-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (e.target.closest('.geofence-actions')) return;
                selectGeofence(parseInt(item.dataset.id));
            });
        });
    }

    // Render on map
    function renderGeofencesOnMap() {
        drawnItems.clearLayers();
        
        geofences.forEach(geo => {
            const layer = parseArea(geo.area, geo.attributes?.color || '#1976d2');
            if (layer) {
                layer.geofenceId = geo.id;
                layer.bindPopup(`<b>${geo.name}</b>`);
                drawnItems.addLayer(layer);
            }
        });

        if (geofences.length > 0) {
            try {
                map.fitBounds(drawnItems.getBounds(), { padding: [50, 50] });
            } catch (e) {}
        }
    }

    // Parse area string
    function parseArea(area, color) {
        if (!area) return null;
        
        const style = {
            color: color,
            fillColor: color,
            fillOpacity: 0.2,
            weight: 2
        };
        
        if (area.startsWith('CIRCLE')) {
            const match = area.match(/CIRCLE\s*\(\s*([\d.-]+)\s+([\d.-]+)\s*,\s*([\d.]+)\s*\)/);
            if (match) {
                return L.circle([parseFloat(match[1]), parseFloat(match[2])], {
                    radius: parseFloat(match[3]),
                    ...style
                });
            }
        } else if (area.startsWith('POLYGON')) {
            const coordsMatch = area.match(/POLYGON\s*\(\((.*?)\)\)/);
            if (coordsMatch) {
                const coords = coordsMatch[1].split(',').map(pair => {
                    const [lng, lat] = pair.trim().split(' ').map(parseFloat);
                    return [lat, lng];
                });
                return L.polygon(coords, style);
            }
        }
        return null;
    }

    // Update stats
    function updateStats() {
        let polygons = 0, circles = 0;
        geofences.forEach(geo => {
            if (geo.area?.startsWith('CIRCLE')) circles++;
            else polygons++;
        });
        document.getElementById('totalGeofences').textContent = geofences.length;
        document.getElementById('polygonCount').textContent = polygons;
        document.getElementById('circleCount').textContent = circles;
    }

    // Select geofence
    function selectGeofence(id) {
        document.querySelectorAll('.geofence-item').forEach(item => {
            item.classList.toggle('active', parseInt(item.dataset.id) === id);
        });
        
        drawnItems.eachLayer(layer => {
            if (layer.geofenceId === id) {
                map.fitBounds(layer.getBounds(), { padding: [100, 100] });
                layer.openPopup();
            }
        });
    }

    // Setup event listeners
    function setupEventListeners() {
        // New geofence
        document.getElementById('btnNewGeofence').addEventListener('click', showNewForm);
        
        // Close panel
        document.getElementById('btnClosePanel').addEventListener('click', hideForm);
        document.getElementById('btnCancelForm').addEventListener('click', hideForm);
        
        // Type selection
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentType = btn.dataset.type;
                
                document.getElementById('circleOptions').style.display = currentType === 'circle' ? 'block' : 'none';
                document.getElementById('polygonInfo').style.display = currentType === 'polygon' ? 'block' : 'none';
            });
        });
        
        // Color presets
        document.querySelectorAll('.color-preset').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.color-preset').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedColor = btn.dataset.color;
                document.getElementById('geofenceColor').value = selectedColor;
            });
        });
        
        document.getElementById('geofenceColor').addEventListener('change', (e) => {
            selectedColor = e.target.value;
            document.querySelectorAll('.color-preset').forEach(b => b.classList.remove('active'));
        });
        
        // Radius slider
        document.getElementById('radiusSlider').addEventListener('input', (e) => {
            document.getElementById('circleRadius').value = e.target.value;
            updateTempCircle();
        });
        
        document.getElementById('circleRadius').addEventListener('input', (e) => {
            document.getElementById('radiusSlider').value = e.target.value;
            updateTempCircle();
        });
        
        // Place circle
        document.getElementById('btnPlaceCircle').addEventListener('click', startCirclePlacement);
        
        // Start draw
        document.getElementById('btnStartDraw').addEventListener('click', startDrawing);
        
        // Drawing controls
        document.getElementById('btnFinishDraw').addEventListener('click', finishDrawing);
        document.getElementById('btnCancelDraw').addEventListener('click', cancelDrawing);
        
        // Save
        document.getElementById('btnSaveGeofence').addEventListener('click', saveGeofence);
        
        // Delete modal
        document.getElementById('btnCancelDelete').addEventListener('click', () => {
            document.getElementById('deleteModal').style.display = 'none';
        });
        
        document.getElementById('btnConfirmDelete').addEventListener('click', deleteGeofence);
        
        // Search
        document.getElementById('searchGeofence').addEventListener('input', filterGeofences);
    }

    // Show new form
    function showNewForm() {
        editingGeofence = null;
        document.getElementById('formPanel').classList.add('active');
        document.getElementById('formTitle').textContent = 'Nouvelle géobarrière';
        document.getElementById('formIcon').className = 'fas fa-plus-circle';
        resetForm();
    }

    // Hide form
    function hideForm() {
        document.getElementById('formPanel').classList.remove('active');
        cancelDrawing();
        clearTempCircle();
    }

    // Reset form
    function resetForm() {
        document.getElementById('geofenceForm').reset();
        document.getElementById('geofenceId').value = '';
        document.getElementById('geofenceArea').value = '';
        document.getElementById('coordinatesPreview').style.display = 'none';
        selectedColor = '#1976d2';
        document.getElementById('geofenceColor').value = selectedColor;
        document.querySelectorAll('.color-preset').forEach(b => b.classList.remove('active'));
        document.querySelector('.color-preset[data-color="#1976d2"]').classList.add('active');
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('.type-btn[data-type="polygon"]').classList.add('active');
        document.getElementById('circleOptions').style.display = 'none';
        document.getElementById('polygonInfo').style.display = 'block';
        currentType = 'polygon';
    }

    // Start drawing polygon
    function startDrawing() {
        isDrawing = true;
        updateStatus('drawing', 'Dessin en cours');
        document.getElementById('drawingPanel').style.display = 'block';
        document.querySelector('.floating-header').style.display = 'none';
        
        // Enable polygon draw
        const drawHandler = new L.Draw.Polygon(map, {
            shapeOptions: {
                color: selectedColor,
                fillColor: selectedColor,
                fillOpacity: 0.2,
                weight: 2
            }
        });
        drawHandler.enable();
        
        map.on('draw:created', (e) => {
            currentDrawing = e.layer;
            drawnItems.addLayer(currentDrawing);
            showCoordinates(currentDrawing);
            finishDrawing();
        });
    }

    // Finish drawing
    function finishDrawing() {
        isDrawing = false;
        document.getElementById('drawingPanel').style.display = 'none';
        document.querySelector('.floating-header').style.display = 'flex';
        updateStatus('ready', 'Prêt');
        
        if (currentDrawing) {
            const area = layerToArea(currentDrawing);
            document.getElementById('geofenceArea').value = area;
        }
    }

    // Cancel drawing
    function cancelDrawing() {
        isDrawing = false;
        document.getElementById('drawingPanel').style.display = 'none';
        document.querySelector('.floating-header').style.display = 'flex';
        updateStatus('ready', 'Prêt');
        
        if (currentDrawing) {
            drawnItems.removeLayer(currentDrawing);
            currentDrawing = null;
        }
        document.getElementById('coordinatesPreview').style.display = 'none';
    }

    // Show coordinates
    function showCoordinates(layer) {
        const preview = document.getElementById('coordinatesPreview');
        const text = document.getElementById('coordinatesText');
        const count = document.getElementById('pointsCount');
        
        if (layer instanceof L.Polygon) {
            const latlngs = layer.getLatLngs()[0];
            count.textContent = `${latlngs.length} points`;
            text.textContent = latlngs.map(ll => `${ll.lat.toFixed(6)}, ${ll.lng.toFixed(6)}`).join('\n');
        } else if (layer instanceof L.Circle) {
            const center = layer.getLatLng();
            const radius = layer.getRadius();
            count.textContent = 'Cercle';
            text.textContent = `Centre: ${center.lat.toFixed(6)}, ${center.lng.toFixed(6)}\nRayon: ${radius.toFixed(0)}m`;
        }
        
        preview.style.display = 'block';
    }

    // Layer to area string
    function layerToArea(layer) {
        if (layer instanceof L.Circle) {
            const center = layer.getLatLng();
            const radius = layer.getRadius();
            return `CIRCLE (${center.lat} ${center.lng}, ${radius})`;
        } else if (layer instanceof L.Polygon) {
            const latlngs = layer.getLatLngs()[0];
            const coords = latlngs.map(ll => `${ll.lng} ${ll.lat}`).join(', ');
            return `POLYGON ((${coords}, ${latlngs[0].lng} ${latlngs[0].lat}))`;
        }
        return '';
    }

    // Circle placement
    function startCirclePlacement() {
        updateStatus('drawing', 'Cliquez sur la carte');
        map.once('click', (e) => {
            document.getElementById('circleLat').value = e.latlng.lat.toFixed(6);
            document.getElementById('circleLng').value = e.latlng.lng.toFixed(6);
            updateTempCircle();
            updateStatus('ready', 'Prêt');
        });
    }

    // Update temp circle
    function updateTempCircle() {
        const lat = parseFloat(document.getElementById('circleLat').value);
        const lng = parseFloat(document.getElementById('circleLng').value);
        const radius = parseFloat(document.getElementById('circleRadius').value);
        
        if (isNaN(lat) || isNaN(lng) || isNaN(radius)) return;
        
        clearTempCircle();
        
        tempCircle = L.circle([lat, lng], {
            radius: radius,
            color: selectedColor,
            fillColor: selectedColor,
            fillOpacity: 0.2,
            weight: 2
        }).addTo(map);
        
        currentDrawing = tempCircle;
        showCoordinates(tempCircle);
        document.getElementById('geofenceArea').value = layerToArea(tempCircle);
        
        map.fitBounds(tempCircle.getBounds(), { padding: [50, 50] });
    }

    // Clear temp circle
    function clearTempCircle() {
        if (tempCircle) {
            map.removeLayer(tempCircle);
            tempCircle = null;
        }
    }

    // Save geofence
    async function saveGeofence() {
        const name = document.getElementById('geofenceName').value.trim();
        const description = document.getElementById('geofenceDescription').value.trim();
        const area = document.getElementById('geofenceArea').value;
        
        if (!name) {
            showWarning('Veuillez entrer un nom');
            return;
        }
        
        if (!area) {
            showWarning('Veuillez dessiner une zone sur la carte');
            return;
        }
        
        const data = {
            name: name,
            description: description,
            area: area,
            attributes: { color: selectedColor }
        };
        
        const id = document.getElementById('geofenceId').value;
        const url = id ? `/api/traccar/geofences/${id}` : '/api/traccar/geofences';
        const method = id ? 'PUT' : 'POST';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                hideForm();
                loadGeofences();
                showToast('Géobarrière sauvegardée avec succès', 'success');
            } else {
                showError('Erreur lors de la sauvegarde');
            }
        } catch (error) {
            console.error('Error saving geofence:', error);
            showError('Erreur lors de la sauvegarde');
        }
    }

    // Edit geofence
    window.editGeofence = function(id) {
        const geo = geofences.find(g => g.id === id);
        if (!geo) return;
        
        editingGeofence = geo;
        document.getElementById('formPanel').classList.add('active');
        document.getElementById('formTitle').textContent = 'Modifier la géobarrière';
        document.getElementById('formIcon').className = 'fas fa-edit';
        
        document.getElementById('geofenceId').value = geo.id;
        document.getElementById('geofenceName').value = geo.name;
        document.getElementById('geofenceDescription').value = geo.description || '';
        document.getElementById('geofenceArea').value = geo.area;
        
        const color = geo.attributes?.color || '#1976d2';
        selectedColor = color;
        document.getElementById('geofenceColor').value = color;
        
        const isCircle = geo.area?.startsWith('CIRCLE');
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        document.querySelector(`.type-btn[data-type="${isCircle ? 'circle' : 'polygon'}"]`).classList.add('active');
        document.getElementById('circleOptions').style.display = isCircle ? 'block' : 'none';
        document.getElementById('polygonInfo').style.display = isCircle ? 'none' : 'block';
        currentType = isCircle ? 'circle' : 'polygon';
        
        // Parse existing area
        const layer = parseArea(geo.area, color);
        if (layer) {
            currentDrawing = layer;
            drawnItems.addLayer(layer);
            showCoordinates(layer);
            
            if (isCircle) {
                const center = layer.getLatLng();
                document.getElementById('circleLat').value = center.lat.toFixed(6);
                document.getElementById('circleLng').value = center.lng.toFixed(6);
                document.getElementById('circleRadius').value = layer.getRadius().toFixed(0);
                document.getElementById('radiusSlider').value = layer.getRadius();
            }
        }
    };

    // Confirm delete
    window.confirmDelete = function(id, name) {
        document.getElementById('deleteModal').style.display = 'flex';
        document.getElementById('deleteGeofenceName').textContent = name;
        document.getElementById('btnConfirmDelete').dataset.id = id;
    };

    // Delete geofence
    async function deleteGeofence() {
        const id = document.getElementById('btnConfirmDelete').dataset.id;
        
        try {
            const response = await fetch(`/api/traccar/geofences/${id}`, { method: 'DELETE' });
            
            if (response.ok) {
                document.getElementById('deleteModal').style.display = 'none';
                loadGeofences();
                showToast('Géobarrière supprimée avec succès', 'success');
            } else {
                showError('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Error deleting geofence:', error);
            showError('Erreur lors de la suppression');
        }
    }

    // Filter geofences
    function filterGeofences() {
        const search = document.getElementById('searchGeofence').value.toLowerCase();
        document.querySelectorAll('.geofence-item').forEach(item => {
            const name = item.querySelector('.geofence-name').textContent.toLowerCase();
            item.style.display = name.includes(search) ? 'flex' : 'none';
        });
    }

    // Update status
    function updateStatus(status, text) {
        const badge = document.getElementById('statusBadge');
        badge.className = `status-badge ${status}`;
        badge.querySelector('.status-text').textContent = text;
    }
});
</script>
@endpush
