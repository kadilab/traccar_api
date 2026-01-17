@extends('layouts.app')

@section('title', __('messages.geofence.title') . ' - Traccar TF')

@section('content')

<div class="geofence-container">
    <!-- Header -->
    <div class="geofence-header">
        <div class="header-left">
            <h1>
                <i class="fas fa-draw-polygon"></i>
                <span>{{ __('messages.geofence.management') }}</span>
            </h1>
            <p class="header-subtitle">{{ __('messages.geofence.subtitle') }}</p>
        </div>
        <div class="header-right">
            <div class="stats-mini">
                <div class="stat-mini">
                    <span class="stat-number" id="totalGeofences">0</span>
                    <span class="stat-text">{{ __('messages.common.total') }}</span>
                </div>
                <div class="stat-mini">
                    <span class="stat-number" id="polygonCount">0</span>
                    <span class="stat-text">{{ __('messages.geofence.polygons') }}</span>
                </div>
                <div class="stat-mini">
                    <span class="stat-number" id="circleCount">0</span>
                    <span class="stat-text">{{ __('messages.geofence.circles') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="geofence-content">
        <!-- Sidebar - Liste des géobarrières -->
        <div class="geofence-sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-list"></i> {{ __('messages.geofence.title') }}</h3>
                <button class="btn-add" id="btnNewGeofence" title="{{ __('messages.geofence.new') }}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            
            <div class="sidebar-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchGeofence" placeholder="{{ __('messages.common.search') }}...">
            </div>
            
            <div class="geofence-list" id="geofenceList">
                <div class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>{{ __('messages.common.loading') }}...</span>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-container">
            <div id="geofenceMap" class="geofence-map"></div>
            
            <!-- Map Controls Info -->
            <div class="map-info">
                <div class="info-item">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ __('messages.geofence.draw_hint') }}</span>
                </div>
            </div>

            <!-- Drawing Instructions -->
            <div class="drawing-instructions" id="drawingInstructions" style="display: none;">
                <div class="instruction-content">
                    <i class="fas fa-pencil-alt"></i>
                    <span id="instructionText">{{ __('messages.geofence.click_to_place') }}</span>
                </div>
                <button class="btn-cancel-draw" id="btnCancelDraw">
                    <i class="fas fa-times"></i> {{ __('messages.common.cancel') }}
                </button>
            </div>
        </div>

        <!-- Form Panel -->
        <div class="form-panel" id="formPanel">
            <div class="panel-header">
                <h3 id="formTitle">
                    <i class="fas fa-plus-circle"></i>
                    <span>{{ __('messages.geofence.new') }}</span>
                </h3>
                <button class="btn-close-panel" id="btnClosePanel">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="panel-body">
                <form id="geofenceForm">
                    <input type="hidden" id="geofenceId" value="">
                    <input type="hidden" id="geofenceArea" value="">
                    
                    <div class="form-group">
                        <label for="geofenceName">
                            <i class="fas fa-tag"></i> {{ __('messages.common.name') }} <span class="required">*</span>
                        </label>
                        <input type="text" id="geofenceName" class="form-input" placeholder="{{ __('messages.geofence.name_placeholder') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="geofenceDescription">
                            <i class="fas fa-align-left"></i> {{ __('messages.common.description') }}
                        </label>
                        <textarea id="geofenceDescription" class="form-input" rows="2" placeholder="{{ __('messages.geofence.description_placeholder') }}"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <i class="fas fa-shapes"></i> {{ __('messages.geofence.zone_type') }} <span class="required">*</span>
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
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="circleLat">
                                    <i class="fas fa-map-pin"></i> {{ __('messages.geofence.latitude') }}
                                </label>
                                <input type="number" id="circleLat" class="form-input" step="0.000001" placeholder="36.7538">
                            </div>
                            <div class="form-group half">
                                <label for="circleLng">
                                    <i class="fas fa-map-pin"></i> {{ __('messages.geofence.longitude') }}
                                </label>
                                <input type="number" id="circleLng" class="form-input" step="0.000001" placeholder="3.0588">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="circleRadius">
                                <i class="fas fa-arrows-alt-h"></i> {{ __('messages.geofence.radius') }}
                            </label>
                            <input type="number" id="circleRadius" class="form-input" value="500" min="10" max="50000">
                        </div>
                    </div>
                    
                    <!-- Polygon Info -->
                    <div class="polygon-info" id="polygonInfo">
                        <div class="info-box">
                            <i class="fas fa-mouse-pointer"></i>
                            <p>{{ __('messages.geofence.draw_polygon_hint') }}</p>
                            <button type="button" class="btn-draw" id="btnStartDraw">
                                <i class="fas fa-pencil-alt"></i> {{ __('messages.geofence.draw_on_map') }}
                            </button>
                        </div>
                        <div class="coordinates-preview" id="coordinatesPreview" style="display: none;">
                            <label><i class="fas fa-map"></i> {{ __('messages.geofence.coordinates') }}</label>
                            <div class="coordinates-text" id="coordinatesText"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="geofenceColor">
                            <i class="fas fa-palette"></i> {{ __('messages.geofence.color') }}
                        </label>
                        <div class="color-picker">
                            <input type="color" id="geofenceColor" value="#7556D6">
                            <span class="color-value" id="colorValue">#7556D6</span>
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
/* Geofence Page Styles */
.geofence-container {
    padding: 20px;
    padding-top: 80px;
    max-width: 1800px;
    margin: 0 auto;
    min-height: calc(100vh - 20px);
    display: flex;
    flex-direction: column;
}

/* Header */
.geofence-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    padding: 10px 10px;
    border-radius: 12px;
    color: #fff;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(117, 86, 214, 0.3);
    margin-top: 20px;
    /* max-height: 80px; */
}

.header-left h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-subtitle {
    margin: 5px 0 0;
    font-size: 14px;
    opacity: 0.9;
}

.stats-mini {
    display: flex;
    gap: 25px;
}

.stat-mini {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255, 255, 255, 0.15);
    padding: 12px 20px;
    border-radius: 10px;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
}

.stat-text {
    font-size: 12px;
    opacity: 0.9;
}

/* Main Content Layout */
.geofence-content {
    flex: 1;
    display: flex;
    gap: 20px;
    min-height: 500px;
}

/* Sidebar */
.geofence-sidebar {
    width: 320px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-header h3 i {
    color: #7556D6;
}

.btn-add {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add:hover {
    transform: scale(1.1);
}

.sidebar-search {
    padding: 15px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-search i {
    color: #9ca3af;
}

.sidebar-search input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 14px;
}

/* Geofence List */
.geofence-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
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
    padding: 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 8px;
    border: 2px solid transparent;
}

.geofence-item:hover {
    background: #f3f4f6;
}

.geofence-item.active {
    background: rgba(117, 86, 214, 0.1);
    border-color: #7556D6;
}

.geofence-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-right: 12px;
}

.geofence-icon.polygon {
    background: rgba(117, 86, 214, 0.1);
    color: #7556D6;
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
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.geofence-meta {
    font-size: 12px;
    color: #6b7280;
}

.geofence-actions {
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.2s;
}

.geofence-item:hover .geofence-actions {
    opacity: 1;
}

.btn-action {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 13px;
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

.empty-list p {
    margin: 0;
    font-size: 14px;
}

/* Map Container */
.map-container {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
}

.geofence-map {
    flex: 1;
    width: 100%;
    min-height: 400px;
}

.map-info {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
}

.info-item {
    background: rgba(255, 255, 255, 0.95);
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 13px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.info-item i {
    color: #7556D6;
}

.drawing-instructions {
    position: absolute;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    color: #fff;
    padding: 15px 25px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 15px rgba(117, 86, 214, 0.4);
}

.instruction-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-cancel-draw {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s;
}

.btn-cancel-draw:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Form Panel */
.form-panel {
    width: 380px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    display: none;
    flex-direction: column;
    overflow: visible;
    max-height: calc(100vh - 200px);
}

.form-panel.active {
    display: flex;
}

.panel-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    max-height: calc(100vh - 400px);
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.panel-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.panel-header h3 i {
    color: #7556D6;
}

.btn-close-panel {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e5e7eb;
    border: none;
    border-radius: 6px;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-close-panel:hover {
    background: #d1d5db;
    color: #374151;
}

/* Panel body styles moved above */

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-group label i {
    color: #7556D6;
}

.required {
    color: #ef4444;
}

.form-input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-input:focus {
    outline: none;
    border-color: #7556D6;
    box-shadow: 0 0 0 3px rgba(117, 86, 214, 0.1);
}

.form-row {
    display: flex;
    gap: 15px;
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
    gap: 8px;
    padding: 15px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    transition: all 0.3s;
}

.type-btn i {
    font-size: 24px;
    color: #9ca3af;
}

.type-btn span {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
}

.type-btn:hover {
    border-color: #7556D6;
}

.type-btn.active {
    border-color: #7556D6;
    background: rgba(117, 86, 214, 0.05);
}

.type-btn.active i,
.type-btn.active span {
    color: #7556D6;
}

/* Circle/Polygon Options */
.circle-options {
    background: #f9fafb;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.polygon-info .info-box {
    background: #f0f4ff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border: 2px dashed #7556D6;
}

.polygon-info .info-box i {
    font-size: 32px;
    color: #7556D6;
    margin-bottom: 10px;
}

.polygon-info .info-box p {
    margin: 0 0 15px;
    font-size: 13px;
    color: #6b7280;
}

.btn-draw {
    background: linear-gradient(135deg, #7556D6 0%, #5a3fb3 100%);
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-draw:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(117, 86, 214, 0.4);
}

.coordinates-preview {
    margin-top: 15px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.coordinates-preview label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.coordinates-text {
    font-family: monospace;
    font-size: 11px;
    color: #6b7280;
    max-height: 100px;
    overflow-y: auto;
    word-break: break-all;
}

/* Color Picker */
.color-picker {
    display: flex;
    align-items: center;
    gap: 15px;
}

.color-picker input[type="color"] {
    width: 50px;
    height: 40px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    padding: 2px;
}

.color-value {
    font-family: monospace;
    font-size: 14px;
    color: #6b7280;
}

/* Panel Footer */
.panel-footer {
    display: flex;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.panel-footer .btn {
    flex: 1;
}

/* Buttons */
.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
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

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
}

.btn-danger:hover {
    transform: translateY(-2px);
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
    border-radius: 16px;
    padding: 30px;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.modal-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 32px;
}

.modal-icon.danger {
    background: #fee2e2;
    color: #ef4444;
}

.modal-box h3 {
    margin: 0 0 10px;
    font-size: 20px;
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

.modal-actions .btn {
    flex: 1;
}

/* Responsive */
@media (max-width: 1200px) {
    .form-panel {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        z-index: 1001;
        border-radius: 0;
    }
}

@media (max-width: 992px) {
    .geofence-content {
        flex-direction: column;
    }
    
    .geofence-sidebar {
        width: 100%;
        max-height: 300px;
    }
    
    .geofence-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}

/* Leaflet Draw Custom Styles */
.leaflet-draw-toolbar a {
    background-color: #fff !important;
}

.leaflet-draw-toolbar a:hover {
    background-color: #f3f4f6 !important;
}

/* Fix Leaflet controls z-index to stay below navbar */
.leaflet-top,
.leaflet-bottom {
    z-index: 400 !important;
}

.leaflet-control {
    z-index: 400 !important;
}

.map-container .leaflet-top {
    top: 10px;
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
    let drawnItems;
    let drawControl;
    let geofences = [];
    let currentGeofence = null;
    let isDrawing = false;
    let currentDrawnLayer = null;
    let selectedType = 'polygon';

    // Initialize
    initMap();
    loadGeofences();
    setupEventListeners();

    // Initialize Map
    function initMap() {
        map = L.map('geofenceMap').setView([36.7538, 3.0588], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Layer for drawn items
        drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Draw control
        drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true,
                    shapeOptions: {
                        color: '#7556D6',
                        fillOpacity: 0.3
                    }
                },
                circle: {
                    shapeOptions: {
                        color: '#10b981',
                        fillOpacity: 0.3
                    }
                },
                rectangle: false,
                polyline: false,
                marker: false,
                circlemarker: false
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        map.addControl(drawControl);

        // Draw events
        map.on(L.Draw.Event.CREATED, function(e) {
            handleDrawCreated(e);
        });

        map.on(L.Draw.Event.DRAWSTART, function() {
            isDrawing = true;
            showDrawingInstructions();
        });

        map.on(L.Draw.Event.DRAWSTOP, function() {
            isDrawing = false;
            hideDrawingInstructions();
        });
    }

    // Handle draw created
    function handleDrawCreated(e) {
        const layer = e.layer;
        const type = e.layerType;

        if (currentDrawnLayer) {
            drawnItems.removeLayer(currentDrawnLayer);
        }

        drawnItems.addLayer(layer);
        currentDrawnLayer = layer;

        // Get area string - Traccar expects specific format
        let areaString = '';
        if (type === 'polygon') {
            const coords = layer.getLatLngs()[0].map(ll => `${ll.lng} ${ll.lat}`);
            coords.push(coords[0]); // Close polygon
            areaString = `POLYGON ((${coords.join(', ')}))`;
            selectedType = 'polygon';
        } else if (type === 'circle') {
            const center = layer.getLatLng();
            const radius = layer.getRadius();
            // Traccar circle format: CIRCLE (lat lng, radius)
            areaString = `CIRCLE (${center.lat} ${center.lng}, ${Math.round(radius)})`;
            selectedType = 'circle';
            
            // Update circle form fields
            document.getElementById('circleLat').value = center.lat.toFixed(6);
            document.getElementById('circleLng').value = center.lng.toFixed(6);
            document.getElementById('circleRadius').value = Math.round(radius);
        }

        console.log('Generated area string:', areaString);
        document.getElementById('geofenceArea').value = areaString;
        
        // Show coordinates preview
        const coordPreview = document.getElementById('coordinatesPreview');
        const coordText = document.getElementById('coordinatesText');
        coordPreview.style.display = 'block';
        coordText.textContent = areaString;

        // Open form panel if not already open
        openFormPanel();
        
        // Update type buttons
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.type === selectedType);
        });
        updateTypeOptions();

        hideDrawingInstructions();
    }

    // Load geofences
    async function loadGeofences() {
        try {
            const response = await fetch('/api/traccar/geofences');
            const data = await response.json();
            
            if (data.success && data.geofences) {
                geofences = data.geofences;
            } else if (Array.isArray(data)) {
                geofences = data;
            }

            renderGeofenceList();
            renderGeofencesOnMap();
            updateStats();
        } catch (error) {
            console.error('Error loading geofences:', error);
            showEmptyList();
        }
    }

    // Render geofence list
    function renderGeofenceList() {
        const list = document.getElementById('geofenceList');
        
        if (geofences.length === 0) {
            showEmptyList();
            return;
        }

        list.innerHTML = geofences.map(geo => {
            const type = getGeofenceType(geo.area);
            return `
                <div class="geofence-item" data-id="${geo.id}" onclick="selectGeofence(${geo.id})">
                    <div class="geofence-icon ${type}">
                        <i class="fas fa-${type === 'circle' ? 'circle' : 'draw-polygon'}"></i>
                    </div>
                    <div class="geofence-details">
                        <div class="geofence-name">${geo.name}</div>
                        <div class="geofence-meta">
                            ${type === 'circle' ? 'Cercle' : 'Polygone'} 
                            ${geo.description ? '• ' + geo.description : ''}
                        </div>
                    </div>
                    <div class="geofence-actions">
                        <button class="btn-action edit" onclick="event.stopPropagation(); editGeofence(${geo.id})" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" onclick="event.stopPropagation(); confirmDelete(${geo.id})" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Show empty list
    function showEmptyList() {
        document.getElementById('geofenceList').innerHTML = `
            <div class="empty-list">
                <i class="fas fa-map-marked-alt"></i>
                <p>Aucune géobarrière</p>
                <p style="margin-top: 5px; font-size: 12px;">Cliquez sur + pour en créer une</p>
            </div>
        `;
    }

    // Get geofence type from area string
    function getGeofenceType(area) {
        if (!area) return 'polygon';
        if (area.startsWith('CIRCLE')) return 'circle';
        return 'polygon';
    }

    // Render geofences on map
    function renderGeofencesOnMap() {
        drawnItems.clearLayers();

        geofences.forEach(geo => {
            const layer = parseAreaToLayer(geo.area, geo.id);
            if (layer) {
                layer.geofenceId = geo.id;
                layer.bindPopup(`<b>${geo.name}</b><br>${geo.description || ''}`);
                drawnItems.addLayer(layer);
            }
        });

        // Fit bounds if there are items
        if (drawnItems.getLayers().length > 0) {
            map.fitBounds(drawnItems.getBounds(), { padding: [50, 50] });
        }
    }

    // Parse area string to Leaflet layer
    function parseAreaToLayer(area, id) {
        if (!area) return null;

        const color = getColorForId(id);

        if (area.startsWith('CIRCLE')) {
            // Parse CIRCLE (lat lng, radius)
            const match = area.match(/CIRCLE\s*\(\s*([\d.-]+)\s+([\d.-]+)\s*,\s*([\d.-]+)\s*\)/i);
            if (match) {
                const lat = parseFloat(match[1]);
                const lng = parseFloat(match[2]);
                const radius = parseFloat(match[3]);
                return L.circle([lat, lng], {
                    radius: radius,
                    color: color,
                    fillOpacity: 0.3
                });
            }
        } else if (area.startsWith('POLYGON')) {
            // Parse POLYGON ((lng lat, lng lat, ...)) - note double parentheses and space
            const match = area.match(/POLYGON\s*\(\s*\(\s*(.+)\s*\)\s*\)/i);
            if (match) {
                const coordsStr = match[1];
                const coords = coordsStr.split(',').map(pair => {
                    const parts = pair.trim().split(/\s+/);
                    const lng = parseFloat(parts[0]);
                    const lat = parseFloat(parts[1]);
                    return [lat, lng];
                }).filter(c => !isNaN(c[0]) && !isNaN(c[1]));
                
                if (coords.length > 2) {
                    return L.polygon(coords, {
                        color: color,
                        fillOpacity: 0.3
                    });
                }
            }
        }
        return null;
    }

    // Get color for geofence
    function getColorForId(id) {
        const colors = ['#7556D6', '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];
        return colors[id % colors.length];
    }

    // Update stats
    function updateStats() {
        document.getElementById('totalGeofences').textContent = geofences.length;
        
        const polygons = geofences.filter(g => getGeofenceType(g.area) === 'polygon').length;
        const circles = geofences.filter(g => getGeofenceType(g.area) === 'circle').length;
        
        document.getElementById('polygonCount').textContent = polygons;
        document.getElementById('circleCount').textContent = circles;
    }

    // Select geofence
    window.selectGeofence = function(id) {
        const geo = geofences.find(g => g.id === id);
        if (!geo) return;

        currentGeofence = geo;

        // Update UI
        document.querySelectorAll('.geofence-item').forEach(item => {
            item.classList.toggle('active', parseInt(item.dataset.id) === id);
        });

        // Zoom to geofence
        const layer = drawnItems.getLayers().find(l => l.geofenceId === id);
        if (layer) {
            if (layer.getBounds) {
                map.fitBounds(layer.getBounds(), { padding: [100, 100] });
            } else if (layer.getLatLng) {
                map.setView(layer.getLatLng(), 15);
            }
            layer.openPopup();
        }
    };

    // Edit geofence
    window.editGeofence = function(id) {
        const geo = geofences.find(g => g.id === id);
        if (!geo) return;

        currentGeofence = geo;
        
        document.getElementById('geofenceId').value = geo.id;
        document.getElementById('geofenceName').value = geo.name;
        document.getElementById('geofenceDescription').value = geo.description || '';
        document.getElementById('geofenceArea').value = geo.area;

        // Determine type
        const type = getGeofenceType(geo.area);
        selectedType = type;
        
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.type === type);
        });
        updateTypeOptions();

        if (type === 'circle' && geo.area) {
            const match = geo.area.match(/CIRCLE\s*\(\s*([\d.-]+)\s+([\d.-]+)\s*,\s*([\d.-]+)\s*\)/i);
            if (match) {
                document.getElementById('circleLat').value = match[1];
                document.getElementById('circleLng').value = match[2];
                document.getElementById('circleRadius').value = match[3];
            }
        }

        // Show coordinates
        const coordPreview = document.getElementById('coordinatesPreview');
        const coordText = document.getElementById('coordinatesText');
        coordPreview.style.display = 'block';
        coordText.textContent = geo.area;

        // Update form title
        document.querySelector('#formTitle span').textContent = 'Modifier Géobarrière';
        document.querySelector('#formTitle i').className = 'fas fa-edit';

        openFormPanel();
    };

    // Confirm delete
    window.confirmDelete = function(id) {
        const geo = geofences.find(g => g.id === id);
        if (!geo) return;

        currentGeofence = geo;
        document.getElementById('deleteGeofenceName').textContent = geo.name;
        document.getElementById('deleteModal').style.display = 'flex';
    };

    // Setup event listeners
    function setupEventListeners() {
        // New geofence button
        document.getElementById('btnNewGeofence').addEventListener('click', () => {
            resetForm();
            openFormPanel();
        });

        // Close panel
        document.getElementById('btnClosePanel').addEventListener('click', closeFormPanel);
        document.getElementById('btnCancelForm').addEventListener('click', closeFormPanel);

        // Type selector
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedType = btn.dataset.type;
                updateTypeOptions();
            });
        });

        // Start drawing
        document.getElementById('btnStartDraw').addEventListener('click', () => {
            if (selectedType === 'polygon') {
                new L.Draw.Polygon(map, drawControl.options.draw.polygon).enable();
            } else {
                new L.Draw.Circle(map, drawControl.options.draw.circle).enable();
            }
        });

        // Cancel drawing
        document.getElementById('btnCancelDraw').addEventListener('click', () => {
            map.fire('draw:drawstop');
            hideDrawingInstructions();
        });

        // Color picker
        document.getElementById('geofenceColor').addEventListener('input', (e) => {
            document.getElementById('colorValue').textContent = e.target.value;
        });

        // Circle inputs change
        ['circleLat', 'circleLng', 'circleRadius'].forEach(id => {
            document.getElementById(id).addEventListener('change', updateCircleFromInputs);
        });

        // Save geofence
        document.getElementById('btnSaveGeofence').addEventListener('click', saveGeofence);

        // Delete modal
        document.getElementById('btnCancelDelete').addEventListener('click', () => {
            document.getElementById('deleteModal').style.display = 'none';
        });

        document.getElementById('btnConfirmDelete').addEventListener('click', deleteGeofence);

        // Search
        document.getElementById('searchGeofence').addEventListener('input', (e) => {
            filterGeofences(e.target.value);
        });
    }

    // Update type options visibility
    function updateTypeOptions() {
        document.getElementById('circleOptions').style.display = selectedType === 'circle' ? 'block' : 'none';
        document.getElementById('polygonInfo').style.display = selectedType === 'polygon' ? 'block' : 'none';
    }

    // Update circle from form inputs
    function updateCircleFromInputs() {
        const lat = parseFloat(document.getElementById('circleLat').value);
        const lng = parseFloat(document.getElementById('circleLng').value);
        const radius = parseFloat(document.getElementById('circleRadius').value);

        if (isNaN(lat) || isNaN(lng) || isNaN(radius)) return;

        // Update area string - Traccar format
        const areaString = `CIRCLE (${lat} ${lng}, ${Math.round(radius)})`;
        document.getElementById('geofenceArea').value = areaString;

        // Update preview
        const coordText = document.getElementById('coordinatesText');
        const coordPreview = document.getElementById('coordinatesPreview');
        coordPreview.style.display = 'block';
        coordText.textContent = areaString;

        // Update map
        if (currentDrawnLayer) {
            drawnItems.removeLayer(currentDrawnLayer);
        }
        currentDrawnLayer = L.circle([lat, lng], { radius: radius, color: '#7556D6', fillOpacity: 0.3 });
        drawnItems.addLayer(currentDrawnLayer);
        map.setView([lat, lng], 14);
    }

    // Open form panel
    function openFormPanel() {
        document.getElementById('formPanel').classList.add('active');
    }

    // Close form panel
    function closeFormPanel() {
        document.getElementById('formPanel').classList.remove('active');
        resetForm();
    }

    // Reset form
    function resetForm() {
        document.getElementById('geofenceForm').reset();
        document.getElementById('geofenceId').value = '';
        document.getElementById('geofenceArea').value = '';
        document.getElementById('coordinatesPreview').style.display = 'none';
        document.querySelector('#formTitle span').textContent = 'Nouvelle Géobarrière';
        document.querySelector('#formTitle i').className = 'fas fa-plus-circle';
        
        if (currentDrawnLayer) {
            drawnItems.removeLayer(currentDrawnLayer);
            currentDrawnLayer = null;
        }
        
        currentGeofence = null;
        selectedType = 'polygon';
        
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.type === 'polygon');
        });
        updateTypeOptions();
    }

    // Show/hide drawing instructions
    function showDrawingInstructions() {
        const text = selectedType === 'polygon' 
            ? 'Cliquez pour placer les points. Double-cliquez pour terminer.'
            : 'Cliquez et glissez pour définir le cercle.';
        document.getElementById('instructionText').textContent = text;
        document.getElementById('drawingInstructions').style.display = 'flex';
    }

    function hideDrawingInstructions() {
        document.getElementById('drawingInstructions').style.display = 'none';
    }

    // Save geofence
    async function saveGeofence() {
        const id = document.getElementById('geofenceId').value;
        const name = document.getElementById('geofenceName').value.trim();
        const description = document.getElementById('geofenceDescription').value.trim();
        const area = document.getElementById('geofenceArea').value;

        if (!name) {
            alert('Veuillez saisir un nom pour la géobarrière');
            return;
        }

        if (!area) {
            alert('Veuillez dessiner une zone sur la carte');
            return;
        }

        // Traccar API expects this exact format - no attributes field for creation
        const payload = {
            name: name,
            description: description || '',
            area: area
        };

        // Add id for update
        if (id) {
            payload.id = parseInt(id);
        }

        console.log('Saving geofence with payload:', payload);

        try {
            let response;
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };

            // Add CSRF token if available
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken.content;
            }

            if (id) {
                response = await fetch(`/api/traccar/geofences/${id}`, {
                    method: 'PUT',
                    headers: headers,
                    body: JSON.stringify(payload)
                });
            } else {
                response = await fetch('/api/traccar/geofences', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(payload)
                });
            }

            const data = await response.json();
            console.log('API Response:', data);
            
            if (data.success || response.ok) {
                closeFormPanel();
                loadGeofences();
                alert(id ? 'Géobarrière modifiée avec succès' : 'Géobarrière créée avec succès');
            } else {
                console.error('API Error:', data);
                alert('Erreur: ' + (data.message || data.error || 'Impossible de sauvegarder'));
            }
        } catch (error) {
            console.error('Error saving geofence:', error);
            alert('Erreur lors de la sauvegarde: ' + error.message);
        }
    }

    // Delete geofence
    async function deleteGeofence() {
        if (!currentGeofence) return;

        try {
            const response = await fetch(`/api/traccar/geofences/${currentGeofence.id}`, {
                method: 'DELETE'
            });

            document.getElementById('deleteModal').style.display = 'none';
            
            if (response.ok) {
                loadGeofences();
                alert('Géobarrière supprimée avec succès');
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Error deleting geofence:', error);
            alert('Erreur lors de la suppression');
        }
    }

    // Filter geofences
    function filterGeofences(search) {
        const filtered = geofences.filter(g => 
            g.name.toLowerCase().includes(search.toLowerCase()) ||
            (g.description && g.description.toLowerCase().includes(search.toLowerCase()))
        );

        const list = document.getElementById('geofenceList');
        
        if (filtered.length === 0) {
            list.innerHTML = `
                <div class="empty-list">
                    <i class="fas fa-search"></i>
                    <p>Aucun résultat pour "${search}"</p>
                </div>
            `;
            return;
        }

        list.innerHTML = filtered.map(geo => {
            const type = getGeofenceType(geo.area);
            return `
                <div class="geofence-item" data-id="${geo.id}" onclick="selectGeofence(${geo.id})">
                    <div class="geofence-icon ${type}">
                        <i class="fas fa-${type === 'circle' ? 'circle' : 'draw-polygon'}"></i>
                    </div>
                    <div class="geofence-details">
                        <div class="geofence-name">${geo.name}</div>
                        <div class="geofence-meta">
                            ${type === 'circle' ? 'Cercle' : 'Polygone'}
                        </div>
                    </div>
                    <div class="geofence-actions">
                        <button class="btn-action edit" onclick="event.stopPropagation(); editGeofence(${geo.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" onclick="event.stopPropagation(); confirmDelete(${geo.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }
});
</script>
@endpush
