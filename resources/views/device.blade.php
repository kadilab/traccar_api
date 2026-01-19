@extends('layouts.app')

@section('title', 'Devices - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar device-sidebar">
        <div class="sidebar-search">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="treeSearch" class="search-input" placeholder="Rechercher...">
            </div>
        </div>
        
        <div class="tree-view" id="deviceTree">
            <div class="tree-loading">
                <div class="spinner-small"></div>
                <span>Chargement...</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-card">
            <div class="card-header-custom">
                <h3>Gestion des Devices</h3>
                <div class="realtime-indicator" id="realtimeIndicator">
                    <span class="realtime-dot"></span>
                    <span class="realtime-text">Temps réel</span>
                </div>
            </div>
            
            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>Recherche</label>
                        <input type="text" id="searchDevice" class="filter-input" placeholder="Nom, IMEI, Groupe...">
                    </div>
                    <div class="filter-group">
                        <label>Statut</label>
                        <select id="filterStatus" class="filter-select">
                            <option value="">Tous</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="unknown">Unknown</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Groupe</label>
                        <select id="filterGroup" class="filter-select">
                            <option value="">Tous les groupes</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Catégorie</label>
                        <select id="filterCategory" class="filter-select">
                            <option value="">Toutes</option>
                            <option value="car">Voiture</option>
                            <option value="truck">Camion</option>
                            <option value="motorcycle">Moto</option>
                            <option value="bus">Bus</option>
                            <option value="person">Personne</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                @if(Auth::user()->administrator)
                <button class="btn btn-primary" id="btnAddDevice" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                    <i class="fas fa-plus"></i>
                    Ajouter
                </button>
                @endif
                <button class="btn btn-success" id="btnRefresh">
                    <i class="fas fa-sync-alt"></i>
                    Rafraîchir
                </button>
                <button class="btn btn-warning" id="btnExport">
                    <i class="fas fa-download"></i>
                    Exporter
                </button>
                @if(Auth::user()->administrator)
                <button class="btn btn-danger" id="btnDeleteSelected">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
                @endif
            </div>

                        <!-- Modal Ajouter Device -->
            <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-labelledby="addDeviceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDeviceModalLabel">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Ajouter un Device
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDeviceForm">
                        <input type="hidden" id="addDeviceId" name="id" value="0">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addDeviceName" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="addDeviceName" name="name" placeholder="Nom du device">
                            </div>
                            <div class="col-md-6">
                                <label for="addDeviceUniqueId" class="form-label">IMEI / Identifiant unique <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="addDeviceUniqueId" name="uniqueId" placeholder="IMEI ou identifiant unique">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addDevicePhone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="addDevicePhone" name="phone" placeholder="+33 6 00 00 00 00">
                            </div>
                            <div class="col-md-6">
                                <label for="addDeviceModel" class="form-label">Modèle</label>
                                <input type="text" class="form-control" id="addDeviceModel" name="model" placeholder="Ex: GT06N, TK103...">
                            </div>
                        </div>  
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="addDeviceGroup" class="form-label">Groupe</label>
                                <select class="form-select" id="addDeviceGroup" name="groupId">
                                    <option value="">-- Aucun groupe --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="addDeviceCategory" class="form-label">Catégorie</label>
                                <select class="form-select" id="addDeviceCategory" name="category">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="car">🚗 Voiture</option>
                                    <option value="truck">🚛 Camion</option>
                                    <option value="motorcycle">🏍️ Moto</option>
                                    <option value="bus">🚌 Bus</option>
                                    <option value="person">🧑 Personne</option>
                                    <option value="boat">🚤 Bateau</option>
                                    <option value="bicycle">🚲 Vélo</option>
                                    <option value="animal">🐕 Animal</option>
                                    <option value="default">📍 Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="addDeviceContact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="addDeviceContact" name="contact" placeholder="Nom ou email du contact">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="addDeviceDisabled" name="disabled">
                                    <label class="form-check-label" for="addDeviceDisabled">Désactiver le device</label>
                                </div>
                            </div>
                        </div>

                        <div id="addDeviceFormError" class="alert alert-danger d-none" role="alert"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Annuler
                    </button>
                    <button type="button" class="btn btn-primary" id="btnAddSaveDevice">
                        <i class="fas fa-save me-1"></i>
                        <span id="btnAddSaveDeviceText">Enregistrer</span>
                    </button>
                </div>
                </div>
            </div>
            </div>

            <!-- Modal Modifier Device -->
            <div class="modal fade" id="editDeviceModal" tabindex="-1" aria-labelledby="editDeviceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDeviceModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        Modifier Device
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDeviceForm">
                        <input type="hidden" id="editDeviceId" name="id" value="0">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDeviceName" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editDeviceName" name="name" placeholder="Nom du device">
                            </div>
                            <div class="col-md-6">
                                <label for="editDeviceUniqueId" class="form-label">IMEI / Identifiant unique <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editDeviceUniqueId" name="uniqueId" placeholder="IMEI ou identifiant unique">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDevicePhone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="editDevicePhone" name="phone" placeholder="+33 6 00 00 00 00">
                            </div>
                            <div class="col-md-6">
                                <label for="editDeviceModel" class="form-label">Modèle</label>
                                <input type="text" class="form-control" id="editDeviceModel" name="model" placeholder="Ex: GT06N, TK103...">
                            </div>
                        </div>  
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDeviceGroup" class="form-label">Groupe</label>
                                <select class="form-select" id="editDeviceGroup" name="groupId">
                                    <option value="">-- Aucun groupe --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editDeviceCategory" class="form-label">Catégorie</label>
                                <select class="form-select" id="editDeviceCategory" name="category">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="car">🚗 Voiture</option>
                                    <option value="truck">🚛 Camion</option>
                                    <option value="motorcycle">🏍️ Moto</option>
                                    <option value="bus">🚌 Bus</option>
                                    <option value="person">🧑 Personne</option>
                                    <option value="boat">🚤 Bateau</option>
                                    <option value="bicycle">🚲 Vélo</option>
                                    <option value="animal">🐕 Animal</option>
                                    <option value="default">📍 Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editDeviceContact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="editDeviceContact" name="contact" placeholder="Nom ou email du contact">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editDeviceDisabled" name="disabled">
                                    <label class="form-check-label" for="editDeviceDisabled">Désactiver le device</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section Attributs Monitor -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">
                                    <i class="fas fa-sliders-h me-1"></i>
                                    Attributs à afficher sur le Monitor
                                </label>
                                <p class="text-muted small">Sélectionnez les attributs disponibles pour ce device qui seront affichés sur la page Monitor</p>
                                
                                <div class="monitor-attributes-config" id="editMonitorAttributes">
                                    <div class="attributes-grid">
                                        <!-- Attributs standards -->
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_ignition" name="monitorAttrs[]" value="ignition">
                                            <label for="attr_ignition">
                                                <i class="fas fa-key text-warning"></i>
                                                <span>Contact/Moteur</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_battery" name="monitorAttrs[]" value="battery">
                                            <label for="attr_battery">
                                                <i class="fas fa-battery-three-quarters text-success"></i>
                                                <span>Batterie GPS</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_batteryLevel" name="monitorAttrs[]" value="batteryLevel">
                                            <label for="attr_batteryLevel">
                                                <i class="fas fa-car-battery text-info"></i>
                                                <span>Batterie Véhicule</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_fuel" name="monitorAttrs[]" value="fuel">
                                            <label for="attr_fuel">
                                                <i class="fas fa-gas-pump text-danger"></i>
                                                <span>Carburant</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_alarm" name="monitorAttrs[]" value="alarm">
                                            <label for="attr_alarm">
                                                <i class="fas fa-bell text-danger"></i>
                                                <span>Alarme</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_charge" name="monitorAttrs[]" value="charge">
                                            <label for="attr_charge">
                                                <i class="fas fa-plug text-primary"></i>
                                                <span>En charge</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_blocked" name="monitorAttrs[]" value="blocked">
                                            <label for="attr_blocked">
                                                <i class="fas fa-lock text-secondary"></i>
                                                <span>Bloqué</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_armed" name="monitorAttrs[]" value="armed">
                                            <label for="attr_armed">
                                                <i class="fas fa-shield-alt text-info"></i>
                                                <span>Armé</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_door" name="monitorAttrs[]" value="door">
                                            <label for="attr_door">
                                                <i class="fas fa-door-open text-warning"></i>
                                                <span>Porte</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_motion" name="monitorAttrs[]" value="motion">
                                            <label for="attr_motion">
                                                <i class="fas fa-running text-success"></i>
                                                <span>Mouvement</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_temperature" name="monitorAttrs[]" value="temperature">
                                            <label for="attr_temperature">
                                                <i class="fas fa-thermometer-half text-danger"></i>
                                                <span>Température</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_humidity" name="monitorAttrs[]" value="humidity">
                                            <label for="attr_humidity">
                                                <i class="fas fa-tint text-info"></i>
                                                <span>Humidité</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_rpm" name="monitorAttrs[]" value="rpm">
                                            <label for="attr_rpm">
                                                <i class="fas fa-tachometer-alt text-primary"></i>
                                                <span>Tours/min (RPM)</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_totalDistance" name="monitorAttrs[]" value="totalDistance">
                                            <label for="attr_totalDistance">
                                                <i class="fas fa-road text-secondary"></i>
                                                <span>Distance totale</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_distance" name="monitorAttrs[]" value="distance">
                                            <label for="attr_distance">
                                                <i class="fas fa-route text-primary"></i>
                                                <span>Distance trajet</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_hours" name="monitorAttrs[]" value="hours">
                                            <label for="attr_hours">
                                                <i class="fas fa-hourglass-half text-warning"></i>
                                                <span>Heures moteur</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_sat" name="monitorAttrs[]" value="sat">
                                            <label for="attr_sat">
                                                <i class="fas fa-satellite text-info"></i>
                                                <span>Satellites</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_hdop" name="monitorAttrs[]" value="hdop">
                                            <label for="attr_hdop">
                                                <i class="fas fa-crosshairs text-success"></i>
                                                <span>Précision GPS</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_rssi" name="monitorAttrs[]" value="rssi">
                                            <label for="attr_rssi">
                                                <i class="fas fa-signal text-success"></i>
                                                <span>Signal GSM</span>
                                            </label>
                                        </div>
                                        <div class="attribute-checkbox">
                                            <input type="checkbox" id="attr_power" name="monitorAttrs[]" value="power">
                                            <label for="attr_power">
                                                <i class="fas fa-bolt text-warning"></i>
                                                <span>Alimentation</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="editDeviceFormError" class="alert alert-danger d-none" role="alert"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Annuler
                    </button>
                    <button type="button" class="btn btn-primary" id="btnEditSaveDevice">
                        <i class="fas fa-save me-1"></i>
                        <span id="btnEditSaveDeviceText">Modifier</span>
                    </button>
                </div>
                </div>
            </div>
            </div>

            <!-- Modal Lier Utilisateur au Device -->
            <div class="modal fade" id="linkUserModal" tabindex="-1" aria-labelledby="linkUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="linkUserModalLabel">
                                <i class="fas fa-user-tie me-2"></i>
                                Assigner un Utilisateur
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="device-info-header mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-microchip fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0" id="linkUserDeviceName">-</h6>
                                        <small class="text-muted" id="linkUserDeviceImei">-</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="link-section">
                                <label for="selectUserForDevice" class="form-label">Sélectionner un Utilisateur</label>
                                <select id="selectUserForDevice" class="form-select" onchange="addLinkedUserToDevice(this)">
                                    <option value="">-- Aucun utilisateur (Supprimer l'assignation) --</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedUserContainer"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Lier Geofences au Device -->
            <div class="modal fade" id="linkGeofenceModal" tabindex="-1" aria-labelledby="linkGeofenceModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="linkGeofenceModalLabel">
                                <i class="fas fa-link me-2"></i>
                                Associer des Geofences
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="device-info-header mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-microchip fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0" id="linkDeviceName">-</h6>
                                        <small class="text-muted" id="linkDeviceImei">-</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="geofences-list-container">
                                <div class="mb-3">
                                    <input type="text" id="searchGeofence" class="form-control" placeholder="Rechercher une geofence...">
                                </div>
                                
                                <div class="geofences-list" id="geofencesList">
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Fermer
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSaveGeofenceLinks">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Devices Table -->
            <div class="table-container">
                <table class="data-table" id="devicesTable">
                    <thead>
                        <tr>
                            <th class="th-checkbox">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Statut</th>
                            <th>Nom</th>
                            <th>IMEI</th>
                            <th>Modèle</th>
                            <th>Téléphone</th>
                            <th>Groupe</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="devicesTableBody">
                        <tr>
                            <td colspan="9" class="loading-cell">
                                <div class="table-loading">
                                    <div class="spinner"></div>
                                    <span>Chargement des devices...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-footer">
                <div class="table-info">
                    <span id="tableInfo">Affichage de 0 à 0 sur 0 entrées</span>
                </div>
                <div class="pagination" id="pagination">
                    <!-- Pagination générée par JS -->
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Device management script loaded');
    
    // Variable pour savoir si l'utilisateur est admin
    const isAdmin = {{ Auth::user()->administrator ? 'true' : 'false' }};
    
    let allDevices = [];
    let allGroups = [];
    let allUsers = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let refreshInterval = null;
    const REFRESH_RATE = 5000; // Rafraîchissement toutes les 5 secondes

    // Charger les groupes d'abord, puis les devices
    async function initializeData() {
        await loadUsers();
        await loadGroups();
        await loadDevices();
        startRealTimeUpdates();
    }
    
    initializeData();

    // Event listeners
    document.getElementById('btnRefresh').addEventListener('click', loadDevices);
    document.getElementById('searchDevice').addEventListener('input', debounce(filterDevices, 300));
    document.getElementById('filterStatus').addEventListener('change', filterDevices);
    document.getElementById('filterGroup').addEventListener('change', filterDevices);
    document.getElementById('filterCategory').addEventListener('change', filterDevices);
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
    document.getElementById('treeSearch').addEventListener('input', debounce(filterTree, 300));

    // Fonction pour démarrer les mises à jour en temps réel
    function startRealTimeUpdates() {
        // Arrêter l'ancien intervalle s'il existe
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        // Démarrer le rafraîchissement automatique
        refreshInterval = setInterval(async () => {
            await loadDevicesSilent();
        }, REFRESH_RATE);
        
        console.log('Real-time updates started (every ' + (REFRESH_RATE/1000) + 's)');
    }

    // Charger les devices sans afficher le loader (pour le temps réel)
    async function loadDevicesSilent() {
        try {
            const response = await fetch('/api/traccar/devices?all=true');
            const data = await response.json();
            
            if (data.success) {
                const newDevices = data.devices || [];
                
                // Vérifier s'il y a des changements et mettre à jour uniquement les éléments modifiés
                const changes = getDevicesChanges(allDevices, newDevices);
                
                if (changes.hasChanges) {
                    console.log('Devices updated in real-time:', changes);
                    
                    // Mettre à jour uniquement les devices modifiés
                    if (changes.updated.length > 0) {
                        changes.updated.forEach(device => {
                            updateDeviceRow(device);
                            updateDeviceTreeItem(device);
                        });
                    }
                    
                    // Si des devices ont été ajoutés ou supprimés, reconstruire
                    if (changes.added.length > 0 || changes.removed.length > 0) {
                        allDevices = newDevices;
                        filterDevices();
                        buildTreeView();
                    } else {
                        // Sinon, mettre à jour seulement allDevices
                        allDevices = newDevices;
                    }
                    
                    updateLastRefreshTime();
                }
            }
        } catch (error) {
            console.error('Erreur rafraîchissement silencieux:', error);
        }
    }

    // Comparer les devices pour détecter les changements
    function hasDevicesChanged(oldDevices, newDevices) {
        if (oldDevices.length !== newDevices.length) return true;
        
        for (let i = 0; i < newDevices.length; i++) {
            const newDev = newDevices[i];
            const oldDev = oldDevices.find(d => d.id === newDev.id);
            
            if (!oldDev) return true;
            if (oldDev.status !== newDev.status) return true;
            if (oldDev.lastUpdate !== newDev.lastUpdate) return true;
            if (oldDev.name !== newDev.name) return true;
        }
        
        return false;
    }

    // Obtenir les changements détaillés entre anciens et nouveaux devices
    function getDevicesChanges(oldDevices, newDevices) {
        const changes = {
            hasChanges: false,
            added: [],
            removed: [],
            updated: []
        };

        // Trouver les devices ajoutés et modifiés
        newDevices.forEach(newDev => {
            const oldDev = oldDevices.find(d => d.id === newDev.id);
            
            if (!oldDev) {
                changes.added.push(newDev);
                changes.hasChanges = true;
            } else if (
                oldDev.status !== newDev.status ||
                oldDev.lastUpdate !== newDev.lastUpdate ||
                oldDev.name !== newDev.name ||
                oldDev.phone !== newDev.phone ||
                oldDev.model !== newDev.model
            ) {
                changes.updated.push(newDev);
                changes.hasChanges = true;
            }
        });

        // Trouver les devices supprimés
        oldDevices.forEach(oldDev => {
            const exists = newDevices.find(d => d.id === oldDev.id);
            if (!exists) {
                changes.removed.push(oldDev);
                changes.hasChanges = true;
            }
        });

        return changes;
    }

    // Mettre à jour une ligne spécifique du tableau
    function updateDeviceRow(device) {
        const row = document.querySelector(`#devicesTable tbody tr[data-id="${device.id}"]`);
        if (!row) return;

        let hasChanged = false;

        // Mettre à jour le statut
        const statusCell = row.querySelector('.status-badge');
        if (statusCell) {
            const newStatus = device.status || 'unknown';
            const oldStatus = statusCell.className.match(/status-(\w+)/)?.[1];
            
            if (oldStatus !== newStatus) {
                statusCell.className = `status-badge status-${newStatus}`;
                statusCell.textContent = getStatusLabel(newStatus);
                hasChanged = true;
            }
        }

        // Mettre à jour les autres cellules
        const cells = row.querySelectorAll('td');
        
        // Name (index 2)
        if (cells[2] && cells[2].textContent !== (device.name || '-')) {
            cells[2].textContent = device.name || '-';
            hasChanged = true;
        }
        
        // Model (index 4)
        if (cells[4] && cells[4].textContent !== (device.model || '-')) {
            cells[4].textContent = device.model || '-';
            hasChanged = true;
        }
        
        // Phone (index 5)
        if (cells[5] && cells[5].textContent !== (device.phone || '-')) {
            cells[5].textContent = device.phone || '-';
            hasChanged = true;
        }
        
        // Animation de mise en évidence seulement si quelque chose a changé
        if (hasChanged) {
            row.classList.add('row-updated');
            setTimeout(() => row.classList.remove('row-updated'), 2000);
        }
    }

    // Mettre à jour un élément spécifique du tree view
    function updateDeviceTreeItem(device) {
        const treeItem = document.querySelector(`.tree-child[data-id="${device.id}"]`);
        if (!treeItem) return;

        // Mettre à jour le statut
        const statusDot = treeItem.querySelector('.tree-status');
        if (statusDot) {
            const newStatus = device.status || 'unknown';
            statusDot.className = `tree-status status-${newStatus}`;
        }

        // Mettre à jour le nom
        const nameSpan = treeItem.querySelector('.tree-device-name');
        if (nameSpan) {
            nameSpan.textContent = device.name;
        }

        // Animation de mise en évidence
        treeItem.classList.add('item-updated');
        setTimeout(() => treeItem.classList.remove('item-updated'), 2000);
    }

    // Afficher l'heure de dernière mise à jour
    function updateLastRefreshTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('fr-FR');
        const infoElement = document.getElementById('tableInfo');
        if (infoElement) {
            const currentText = infoElement.textContent.split(' | ')[0];
            infoElement.textContent = currentText + ' | Mis à jour: ' + timeStr;
        }
    }

    // Arrêter les mises à jour quand la page n'est pas visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                console.log('Real-time updates paused');
            }
        } else {
            loadDevicesSilent();
            startRealTimeUpdates();
        }
    });

    // Charger les devices depuis l'API (avec loader)
    async function loadDevices() {
        try {
            showTableLoading();
            const response = await fetch('/api/traccar/devices?all=true');
            const data = await response.json();
            console.log('Devices response:', data);
            if (data.success) {
                allDevices = data.devices || [];
                filterDevices();
                buildTreeView();
            } else {
                showTableError('Erreur lors du chargement des devices');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showTableError('Erreur de connexion au serveur');
        }
    }

    // Charger les groupes
    // Charger les utilisateurs
    async function loadUsers() {
        console.log('Chargement des utilisateurs...');
        try {
            const response = await fetch('/api/traccar/users');
            console.log('Users Response status:', response.status);
            
            if (!response.ok) {
                console.error('Erreur HTTP lors du chargement des utilisateurs:', response.status);
                return;
            }
            
            const data = await response.json();
            console.log('Users API response:', JSON.stringify(data));
            
            if (data.success && data.users && Array.isArray(data.users)) {
                allUsers = data.users;
                console.log('Utilisateurs chargés avec succès:', allUsers.length, 'utilisateurs');
            } else if (Array.isArray(data.users)) {
                allUsers = data.users;
                console.log('Utilisateurs chargés avec succès:', allUsers.length, 'utilisateurs');
            } else if (Array.isArray(data)) {
                allUsers = data;
                console.log('Utilisateurs chargés avec succès:', allUsers.length, 'utilisateurs');
            } else {
                console.warn('Format de réponse inattendu pour les utilisateurs:', data);
                allUsers = [];
            }
        } catch (error) {
            console.error('Erreur lors du chargement des utilisateurs:', error);
            allUsers = [];
        }
    }

    async function loadGroups() {
        console.log('Chargement des groupes...');
        try {
            const response = await fetch('/api/traccar/groups?all=true');
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                console.error('Erreur HTTP:', response.status, response.statusText);
                return;
            }
            
            const data = await response.json();
            console.log('Groups API response:', JSON.stringify(data));
            
            if (data.success && data.groups && Array.isArray(data.groups)) {
                allGroups = data.groups;
                console.log('Groupes chargés avec succès:', allGroups.length, 'groupes');
                populateGroupSelects();
                // Reconstruire l'arbre après avoir chargé les groupes (si devices déjà chargés)
                if (allDevices.length > 0) {
                    buildTreeView();
                }
            } else if (data.success && Array.isArray(data.groups) && data.groups.length === 0) {
                console.log('Aucun groupe trouvé dans Traccar');
                allGroups = [];
                populateGroupSelects();
            } else {
                console.warn('Format de réponse inattendu:', data);
                // Essayer de récupérer les groupes directement si la structure est différente
                if (Array.isArray(data)) {
                    allGroups = data;
                    populateGroupSelects();
                }
            }
        } catch (error) {
            console.error('Erreur lors du chargement des groupes:', error);
        }
    }

    // Remplir les sélects de groupes dans les modals
    function populateGroupSelects() {
        const filterSelect = document.getElementById('filterGroup');
        const addDeviceSelect = document.getElementById('addDeviceGroup');
        const editDeviceSelect = document.getElementById('editDeviceGroup');
        
        console.log('Remplissage des sélects de groupes, nombre:', allGroups.length);
        
        // Vider d'abord les options existantes (sauf la première)
        if (filterSelect) {
            filterSelect.innerHTML = '<option value="">Tous les groupes</option>';
        }
        if (addDeviceSelect) {
            addDeviceSelect.innerHTML = '<option value="">-- Aucun groupe --</option>';
        }
        if (editDeviceSelect) {
            editDeviceSelect.innerHTML = '<option value="">-- Aucun groupe --</option>';
        }
        
        if (!allGroups || allGroups.length === 0) {
            console.log('Aucun groupe à afficher');
            return;
        }
        
        allGroups.forEach(group => {
            console.log('Ajout groupe:', group.name, 'ID:', group.id);
            
            // Filtre principal
            if (filterSelect) {
                const option = document.createElement('option');
                option.value = group.id;
                option.textContent = group.name;
                filterSelect.appendChild(option);
            }
            
            // Select modal d'ajout
            if (addDeviceSelect) {
                const addOption = document.createElement('option');
                addOption.value = group.id;
                addOption.textContent = group.name;
                addDeviceSelect.appendChild(addOption);
            }
            
            // Select modal d'édition
            if (editDeviceSelect) {
                const editOption = document.createElement('option');
                editOption.value = group.id;
                editOption.textContent = group.name;
                editDeviceSelect.appendChild(editOption);
            }
        });
        
        console.log('Sélects de groupes remplis avec', allGroups.length, 'groupes');
    }

    // Gérer la soumission du formulaire d'ajout
    const btnAddSaveDevice = document.getElementById('btnAddSaveDevice');
    console.log('Bouton Ajouter Enregistrer trouvé:', btnAddSaveDevice);
    
    if (btnAddSaveDevice) {
        btnAddSaveDevice.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Clic sur Enregistrer (Ajout)');
            saveDevice('add');
        });
    }

    // Gérer la soumission du formulaire d'édition
    const btnEditSaveDevice = document.getElementById('btnEditSaveDevice');
    console.log('Bouton Édition Enregistrer trouvé:', btnEditSaveDevice);
    
    if (btnEditSaveDevice) {
        btnEditSaveDevice.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Clic sur Modifier');
            saveDevice('edit');
        });
    }

    async function saveDevice(mode = 'add') {
        console.log('saveDevice() appelée en mode:', mode);
        
        let form, errorDiv, btnText, btn, idField, nameField, uniqueIdField, phoneField, modelField, groupField, categoryField, contactField, disabledField;
        
        if (mode === 'add') {
            form = document.getElementById('addDeviceForm');
            errorDiv = document.getElementById('addDeviceFormError');
            btn = document.getElementById('btnAddSaveDevice');
            btnText = document.getElementById('btnAddSaveDeviceText');
            idField = document.getElementById('addDeviceId');
            nameField = document.getElementById('addDeviceName');
            uniqueIdField = document.getElementById('addDeviceUniqueId');
            phoneField = document.getElementById('addDevicePhone');
            modelField = document.getElementById('addDeviceModel');
            groupField = document.getElementById('addDeviceGroup');
            categoryField = document.getElementById('addDeviceCategory');
            contactField = document.getElementById('addDeviceContact');
            disabledField = document.getElementById('addDeviceDisabled');
        } else {
            form = document.getElementById('editDeviceForm');
            errorDiv = document.getElementById('editDeviceFormError');
            btn = document.getElementById('btnEditSaveDevice');
            btnText = document.getElementById('btnEditSaveDeviceText');
            idField = document.getElementById('editDeviceId');
            nameField = document.getElementById('editDeviceName');
            uniqueIdField = document.getElementById('editDeviceUniqueId');
            phoneField = document.getElementById('editDevicePhone');
            modelField = document.getElementById('editDeviceModel');
            groupField = document.getElementById('editDeviceGroup');
            categoryField = document.getElementById('editDeviceCategory');
            contactField = document.getElementById('editDeviceContact');
            disabledField = document.getElementById('editDeviceDisabled');
        }
        
        // Validation
        const name = nameField.value.trim();
        const uniqueId = uniqueIdField.value.trim();
        
        if (!name || !uniqueId) {
            errorDiv.textContent = 'Le nom et l\'identifiant unique (IMEI) sont obligatoires.';
            errorDiv.classList.remove('d-none');
            return;
        }
        
        errorDiv.classList.add('d-none');
        
        // Préparer les données
        const deviceData = {
            id: idField.value,
            name: name,
            uniqueId: uniqueId,
            phone: phoneField.value.trim() || null,
            model: modelField.value.trim() || null,
            groupId: groupField.value ? parseInt(groupField.value) : null,
            category: categoryField.value || null,
            contact: contactField.value.trim() || null,
            disabled: disabledField.checked
        };
        
        // Récupérer les attributs monitor sélectionnés (uniquement en mode edit)
        if (mode === 'edit') {
            const monitorAttrs = [];
            document.querySelectorAll('#editMonitorAttributes input[type="checkbox"]:checked').forEach(cb => {
                monitorAttrs.push(cb.value);
            });
            
            // Récupérer les attributs existants du device
            const existingDevice = allDevices.find(d => d.id == idField.value);
            const existingAttrs = existingDevice?.attributes || {};
            
            // Fusionner avec les nouveaux attributs monitor
            deviceData.attributes = {
                ...existingAttrs,
                monitorAttributes: monitorAttrs
            };
        }
        
        const deviceId = idField.value;
        const isEdit = deviceId && deviceId !== '0';
        
        // Afficher loading
        btn.disabled = true;
        const originalText = btnText.textContent;
        btnText.textContent = isEdit ? 'Modification en cours...' : 'Enregistrement en cours...';
        
        try {
            const url = isEdit ? `/api/traccar/devices/${deviceId}` : '/api/traccar/devices';
            const method = isEdit ? 'PUT' : 'POST';
            console.log(`Envoi de la requête ${method} à ${url} avec les données:`, deviceData);
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(deviceData)
            });
            
            const data = await response.json();
            
            if (data.success || response.ok) {
                // Déterminer quel modal fermer
                const modalId = mode === 'add' ? 'addDeviceModal' : 'editDeviceModal';
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                if (modal) {
                    modal.hide();
                }
                
                // Reset form
                form.reset();
                idField.value = '0';
                
                // Recharger les devices
                await loadDevices();
                
                // Message de succès
                const message = isEdit ? 'Device modifié avec succès !' : 'Device créé avec succès !';
                console.log(message);
                
                // Optionnel: Afficher une notification toast
                if (typeof showNotification === 'function') {
                    showNotification(message, 'success');
                }
            } else {
                errorDiv.textContent = data.message || data.error || 'Erreur lors de l\'enregistrement du device';
                errorDiv.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Erreur:', error);
            errorDiv.textContent = 'Erreur de connexion au serveur. Veuillez réessayer.';
            errorDiv.classList.remove('d-none');
        } finally {
            btn.disabled = false;
            btnText.textContent = originalText;
        }
    }

    // Reset du formulaire d'ajout à l'ouverture du modal
    document.getElementById('addDeviceModal').addEventListener('show.bs.modal', function(event) {
        console.log('Modal d\'ajout en cours d\'ouverture');
        
        // Reset complet du formulaire pour "Ajouter"
        document.getElementById('addDeviceForm').reset();
        document.getElementById('addDeviceId').value = '0';
        document.getElementById('addDeviceGroup').value = '';
        document.getElementById('addDeviceCategory').value = '';
        document.getElementById('addDeviceDisabled').checked = false;
        
        document.getElementById('addDeviceFormError').classList.add('d-none');
    });

    // Lorsque le modal d'édition se ferme
    document.getElementById('editDeviceModal').addEventListener('hide.bs.modal', function(event) {
        console.log('Modal d\'édition en cours de fermeture');
        // Optionnel: nettoyer les données
    });

    // Filtrer les devices
    function filterDevices() {
        const search = document.getElementById('searchDevice').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const groupId = document.getElementById('filterGroup').value;
        const category = document.getElementById('filterCategory').value;

        let filtered = allDevices.filter(device => {
            // Récupérer le nom du groupe pour la recherche
            const groupName = getGroupName(device.groupId)?.toLowerCase() || '';
            
            const matchSearch = !search || 
                device.name?.toLowerCase().includes(search) ||
                device.uniqueId?.toLowerCase().includes(search) ||
                device.phone?.toLowerCase().includes(search) ||
                groupName.includes(search);
            
            const matchStatus = !status || device.status === status;
            const matchGroup = !groupId || device.groupId == groupId;
            const matchCategory = !category || device.category === category;

            return matchSearch && matchStatus && matchGroup && matchCategory;
        });

        currentPage = 1;
        renderTable(filtered);
    }

    // Afficher le tableau
    function renderTable(devices) {
        const tbody = document.getElementById('devicesTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedDevices = devices.slice(start, end);

        if (devices.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="empty-cell">
                        <div class="empty-state">
                            <i class="fas fa-mobile-alt fa-3x"></i>
                            <p>Aucun device trouvé</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedDevices.map(device => `
                <tr data-id="${device.id}">
                    <td>${isAdmin ? `<input type="checkbox" class="device-checkbox" value="${device.id}">` : ''}</td>
                    <td>
                        <span class="status-badge status-${device.status || 'unknown'}">
                            ${getStatusLabel(device.status)}
                        </span>
                    </td>
                    <td>${device.name || '-'}</td>
                    <td><code>${device.uniqueId || '-'}</code></td>
                    <td>${device.model || '-'}</td>
                    <td>${device.phone || '-'}</td>
                    <td>${getGroupName(device.groupId)}</td>
                    <td class="actions-cell">
                        ${isAdmin ? `<button class="btn-icon btn-edit" title="Modifier" onclick="editDevice(${device.id})">
                            <i class="fas fa-edit"></i>
                        </button>` : ''}
                        <button class="btn-icon btn-link-user ${device.userId ? 'has-links' : ''}" 
                                title="${device.userId ? 'Utilisateur assigné' : 'Assigner Utilisateur'}" 
                                onclick="openLinkUserModal(${device.id})">
                            <i class="fas fa-user-tie"></i>
                            ${device.userId ? `<span class="link-badge"><i class="fas fa-check"></i></span>` : ''}
                        </button>
                        <button class="btn-icon btn-link-geofence ${device.geofenceIds && device.geofenceIds.length > 0 ? 'has-links' : ''}" 
                                title="${device.geofenceIds && device.geofenceIds.length > 0 ? device.geofenceIds.length + ' geofence(s) liée(s)' : 'Associer Geofences'}" 
                                onclick="openLinkGeofenceModal(${device.id})">
                            <i class="fas fa-draw-polygon"></i>
                            ${device.geofenceIds && device.geofenceIds.length > 0 ? `<span class="link-badge">${device.geofenceIds.length}</span>` : ''}
                        </button>
                        <button class="btn-icon btn-locate" title="Localiser" onclick="locateDevice(${device.id})">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                        ${isAdmin ? `<button class="btn-icon btn-delete" title="Supprimer" onclick="deleteDevice(${device.id})">
                            <i class="fas fa-trash"></i>
                        </button>` : ''}
                    </td>
                </tr>
            `).join('');
        }

        updateTableInfo(devices.length, start, Math.min(end, devices.length));
        renderPagination(devices.length);
    }

    // Construire le tree view
    function buildTreeView() {
        const treeContainer = document.getElementById('deviceTree');
        
        // Grouper les devices par utilisateur
        const grouped = {};
        grouped['Non assigné'] = allDevices.filter(d => !d.userId);
        
        allUsers.forEach(user => {
            grouped[user.name] = allDevices.filter(d => d.userId === user.id);
        });

        let html = '';
        for (const [userName, devices] of Object.entries(grouped)) {
            if (devices.length > 0) {
                html += `
                    <div class="tree-node">
                        <div class="tree-parent" onclick="toggleTreeNode(this)">
                            <i class="fas fa-chevron-right tree-arrow"></i>
                            <i class="fas fa-user tree-user-icon"></i>
                            <span class="tree-label">${userName}</span>
                            <span class="tree-count">${devices.length}</span>
                        </div>
                        <div class="tree-children">
                            ${devices.map(d => `
                                <div class="tree-child" data-id="${d.id}" onclick="selectDevice(${d.id})">
                                    <span class="tree-status status-${d.status || 'unknown'}"></span>
                                    <span class="tree-device-name">${d.name}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }
        }

        treeContainer.innerHTML = html || '<div class="tree-empty">Aucun device</div>';
    }

    // Filtrer le tree view
    function filterTree() {
        const search = document.getElementById('treeSearch').value.toLowerCase();
        const treeChildren = document.querySelectorAll('.tree-child');
        const treeNodes = document.querySelectorAll('.tree-node');

        treeChildren.forEach(child => {
            const name = child.querySelector('.tree-device-name').textContent.toLowerCase();
            child.style.display = name.includes(search) ? 'flex' : 'none';
        });

        // Afficher/masquer les groupes vides
        treeNodes.forEach(node => {
            const visibleChildren = node.querySelectorAll('.tree-child[style*="flex"], .tree-child:not([style])');
            const hasVisibleChildren = Array.from(node.querySelectorAll('.tree-child')).some(c => c.style.display !== 'none');
            node.style.display = hasVisibleChildren || !search ? 'block' : 'none';
            
            if (search && hasVisibleChildren) {
                node.classList.add('expanded');
            }
        });
    }

    // Helpers
    function getStatusLabel(status) {
        const labels = { online: 'Online', offline: 'Offline', unknown: 'Inconnu' };
        return labels[status] || 'Inconnu';
    }

    function getGroupName(groupId) {
        if (!groupId) return '-';
        const group = allGroups.find(g => g.id === groupId);
        return group ? group.name : '-';
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleString('fr-FR', { 
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    function showTableLoading() {
        document.getElementById('devicesTableBody').innerHTML = `
            <tr>
                <td colspan="9" class="loading-cell">
                    <div class="table-loading">
                        <div class="spinner"></div>
                        <span>Chargement des devices...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    function showTableError(message) {
        document.getElementById('devicesTableBody').innerHTML = `
            <tr>
                <td colspan="9" class="error-cell">
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                        <p>${message}</p>
                        <button class="btn btn-primary btn-sm" onclick="loadDevices()">Réessayer</button>
                    </div>
                </td>
            </tr>
        `;
    }

    function updateTableInfo(total, start, end) {
        document.getElementById('tableInfo').textContent = 
            `Affichage de ${total > 0 ? start + 1 : 0} à ${end} sur ${total} entrées`;
    }

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const pagination = document.getElementById('pagination');
        
        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = '';
        html += `<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="goToPage(${currentPage - 1})">«</button>`;
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<span class="page-dots">...</span>`;
            }
        }
        
        html += `<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="goToPage(${currentPage + 1})">»</button>`;
        pagination.innerHTML = html;
    }

    function toggleSelectAll() {
        const checked = document.getElementById('selectAll').checked;
        document.querySelectorAll('.device-checkbox').forEach(cb => cb.checked = checked);
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Fonctions globales
    window.goToPage = function(page) {
        currentPage = page;
        filterDevices();
    };

    window.toggleTreeNode = function(element) {
        element.parentElement.classList.toggle('expanded');
    };

    window.selectDevice = function(id) {
        document.querySelectorAll('.tree-child').forEach(c => c.classList.remove('selected'));
        document.querySelector(`.tree-child[data-id="${id}"]`)?.classList.add('selected');
        
        // Highlight dans le tableau
        document.querySelectorAll('#devicesTable tbody tr').forEach(r => r.classList.remove('highlighted'));
        document.querySelector(`#devicesTable tbody tr[data-id="${id}"]`)?.classList.add('highlighted');
    };

    window.editDevice = function(id) {
        const device = allDevices.find(d => d.id === id);
        if (!device) {
            console.error('Device not found:', id);
            alert('Device non trouvé');
            return;
        }
        
        console.log('Édition du device:', device);
        // Remplir le formulaire d'édition avec les données du device
        document.getElementById('editDeviceId').value = device.id;
        document.getElementById('editDeviceName').value = device.name || '';
        document.getElementById('editDeviceUniqueId').value = device.uniqueId || '';
        document.getElementById('editDevicePhone').value = device.phone || '';
        document.getElementById('editDeviceModel').value = device.model || '';
        document.getElementById('editDeviceContact').value = device.contact || '';
        document.getElementById('editDeviceDisabled').checked = device.disabled === true;
        
        // Remplir les sélects avec les bonnes valeurs
        const groupSelect = document.getElementById('editDeviceGroup');
        const categorySelect = document.getElementById('editDeviceCategory');
        
        // Définir le groupe
        if (device.groupId) {
            groupSelect.value = device.groupId;
        } else {
            groupSelect.value = '';
        }
        
        // Définir la catégorie
        if (device.category) {
            categorySelect.value = device.category;
        } else {
            categorySelect.value = '';
        }
        
        // Charger les attributs monitor sélectionnés
        const monitorAttrs = device.attributes?.monitorAttributes || [];
        document.querySelectorAll('#editMonitorAttributes input[type="checkbox"]').forEach(cb => {
            cb.checked = monitorAttrs.includes(cb.value);
        });
        
        // Forcer le rafraîchissement des sélects (pour certains navigateurs)
        groupSelect.dispatchEvent(new Event('change'));
        categorySelect.dispatchEvent(new Event('change'));
        
        console.log('Formulaire d\'édition rempli - Groupe:', groupSelect.value, 'Catégorie:', categorySelect.value);
        console.log('Monitor Attributes:', monitorAttrs);
        
        // Masquer les messages d'erreur
        document.getElementById('editDeviceFormError').classList.add('d-none');
        
        // Changer le titre avec le nom du device
        document.getElementById('editDeviceModalLabel').innerHTML = `
            <i class="fas fa-edit me-2"></i>
            Modifier: <strong>${device.name}</strong>
        `;
        
        console.log('Titre du modal d\'édition changé - Device:', device.name);
        
        // Ouvrir le modal d'édition
        try {
            const modalElement = document.getElementById('editDeviceModal');
            const editModal = new bootstrap.Modal(modalElement);
            editModal.show();
        } catch (error) {
            console.error('Erreur lors de l\'ouverture du modal d\'édition:', error);
        }
    };

    window.locateDevice = function(id) {
        console.log('Locate device:', id);
        window.location.href = `/tracking?id=${id}`;
    };

    window.deleteDevice = async function(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce device ?')) return;
        
        try {
            const response = await fetch(`/api/traccar/devices/${id}`, { method: 'DELETE' });
            const data = await response.json();
            
            if (data.success) {
                loadDevices();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        }
    };

    // ==================== GEOFENCE LINKING ====================
    
    let allGeofences = [];
    let deviceGeofenceLinks = [];

    // Charger toutes les geofences
    async function loadGeofences() {
        try {
            const response = await fetch('/api/traccar/geofences');
            const data = await response.json();
            if (data.success) {
                allGeofences = data.geofences || [];
            }
        } catch (error) {
            console.error('Erreur chargement geofences:', error);
        }
    }

    // Charger les liens device-geofence pour un device
    async function loadDeviceGeofenceLinks(deviceId) {
        try {
            // D'abord vérifier si on a déjà les geofenceIds dans allDevices
            const device = allDevices.find(d => d.id === deviceId);
            if (device && device.geofenceIds && device.geofenceIds.length > 0) {
                deviceGeofenceLinks = device.geofenceIds;
                return;
            }
            
            // Sinon, recharger le device depuis l'API
            const response = await fetch(`/api/traccar/devices/${deviceId}`);
            const data = await response.json();
            if (data.success && data.device) {
                deviceGeofenceLinks = data.device.geofenceIds || [];
            } else {
                deviceGeofenceLinks = [];
            }
        } catch (error) {
            console.error('Erreur chargement liens:', error);
            deviceGeofenceLinks = [];
        }
    }

    // Ouvrir le modal de liaison geofence
    // Ouvrir le modal pour lier un utilisateur au device
    window.openLinkUserModal = async function(deviceId) {
        currentLinkDeviceId = deviceId;
        const device = allDevices.find(d => d.id === deviceId);
        
        if (!device) return;
        
        // Afficher les infos du device
        document.getElementById('linkUserDeviceName').textContent = device.name || 'Device';
        document.getElementById('linkUserDeviceImei').textContent = device.uniqueId || '-';
        
        // Remplir le select avec les utilisateurs
        const select = document.getElementById('selectUserForDevice');
        select.innerHTML = '<option value="">-- Aucun utilisateur (Supprimer l\'assignation) --</option>';
        
        allUsers.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = user.name || user.email;
            if (device.userId === user.id) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        
        // Afficher l'utilisateur actuellement assigné
        const linkedUserContainer = document.getElementById('linkedUserContainer');
        linkedUserContainer.innerHTML = '';
        
        if (device.userId) {
            const user = allUsers.find(u => u.id === device.userId);
            if (user) {
                const badge = document.createElement('div');
                badge.className = 'linked-item-badge';
                badge.innerHTML = `
                    <span><i class="fas fa-user me-2"></i>${user.name || user.email}</span>
                    <span class="badge-type">Assigné</span>
                    <button type="button" class="remove-link" onclick="removeUserFromDevice(${deviceId})">×</button>
                `;
                linkedUserContainer.appendChild(badge);
            }
        }
        
        // Ouvrir le modal
        const modal = new bootstrap.Modal(document.getElementById('linkUserModal'));
        modal.show();
    };

    // Ajouter/modifier l'assignation d'utilisateur au device
    window.addLinkedUserToDevice = async function(selectElement) {
        const userId = selectElement.value ? parseInt(selectElement.value) : null;
        const device = allDevices.find(d => d.id === currentLinkDeviceId);
        
        if (!device) return;
        
        try {
            if (userId) {
                // Assigner l'utilisateur
                const response = await fetch('/api/traccar/permissions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        userId: userId,
                        deviceId: device.id
                    })
                });

                const data = await response.json();

                if (data.success || response.ok) {
                    // Mettre à jour le device
                    device.userId = userId;
                    const user = allUsers.find(u => u.id === userId);
                    
                    // Afficher le badge
                    const linkedUserContainer = document.getElementById('linkedUserContainer');
                    linkedUserContainer.innerHTML = '';
                    const badge = document.createElement('div');
                    badge.className = 'linked-item-badge';
                    badge.innerHTML = `
                        <span><i class="fas fa-user me-2"></i>${user.name || user.email}</span>
                        <span class="badge-type">Assigné</span>
                        <button type="button" class="remove-link" onclick="removeUserFromDevice(${device.id})">×</button>
                    `;
                    linkedUserContainer.appendChild(badge);
                    
                    // Reconstruire l'arbre
                    buildTreeView();
                    
                    console.log('Utilisateur assigné avec succès au device');
                } else {
                    alert(data.message || 'Erreur lors de l\'assignation');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur de connexion au serveur');
        }
    };

    // Supprimer l'assignation d'utilisateur
    window.removeUserFromDevice = async function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        
        if (!device || !device.userId) return;
        
        try {
            const response = await fetch('/api/traccar/permissions-test', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    userId: device.userId,
                    deviceId: device.id
                })
            });

            const data = await response.json();

            if (data.success || response.ok) {
                // Mettre à jour le device
                device.userId = null;
                
                // Mettre à jour l'interface
                const linkedUserContainer = document.getElementById('linkedUserContainer');
                linkedUserContainer.innerHTML = '';
                
                const select = document.getElementById('selectUserForDevice');
                select.value = '';
                
                // Reconstruire l'arbre
                buildTreeView();
                
                console.log('Utilisateur supprimé avec succès');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    };

    // Variable pour stocker l'ID du device actuellement lié
    let currentLinkDeviceId = null;

    window.openLinkGeofenceModal = async function(deviceId) {
        currentLinkDeviceId = deviceId;
        const device = allDevices.find(d => d.id === deviceId);
        
        if (!device) return;
        
        // Afficher les infos du device avec le nombre de geofences liées
        const linkedCount = device.geofenceIds ? device.geofenceIds.length : 0;
        document.getElementById('linkDeviceName').textContent = device.name || 'Device';
        document.getElementById('linkDeviceImei').innerHTML = `${device.uniqueId || '-'} <span class="badge bg-primary ms-2">${linkedCount} geofence(s) liée(s)</span>`;
        
        // Afficher loading
        document.getElementById('geofencesList').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        `;
        
        // Ouvrir le modal
        const modal = new bootstrap.Modal(document.getElementById('linkGeofenceModal'));
        modal.show();
        
        // Charger les données
        await loadGeofences();
        await loadDeviceGeofenceLinks(deviceId);
        
        // Afficher la liste
        renderGeofencesList();
    };

    // Afficher la liste des geofences avec checkboxes
    function renderGeofencesList() {
        const container = document.getElementById('geofencesList');
        const searchTerm = document.getElementById('searchGeofence').value.toLowerCase();
        
        let filteredGeofences = allGeofences;
        if (searchTerm) {
            filteredGeofences = allGeofences.filter(g => 
                g.name?.toLowerCase().includes(searchTerm)
            );
        }
        
        if (filteredGeofences.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-draw-polygon fa-3x mb-3"></i>
                    <p>Aucune geofence disponible</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = filteredGeofences.map(geofence => {
            const isLinked = deviceGeofenceLinks.includes(geofence.id);
            return `
                <div class="geofence-item ${isLinked ? 'linked' : ''}" data-id="${geofence.id}">
                    <label class="geofence-label">
                        <input type="checkbox" class="geofence-checkbox" value="${geofence.id}" ${isLinked ? 'checked' : ''}>
                        <div class="geofence-info">
                            <i class="fas fa-draw-polygon"></i>
                            <span class="geofence-name">${geofence.name}</span>
                        </div>
                        <span class="link-status">
                            ${isLinked ? '<i class="fas fa-link text-success"></i>' : '<i class="fas fa-unlink text-muted"></i>'}
                        </span>
                    </label>
                </div>
            `;
        }).join('');
        
        // Ajouter les listeners pour mise à jour visuelle
        container.querySelectorAll('.geofence-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const item = this.closest('.geofence-item');
                const statusIcon = item.querySelector('.link-status');
                if (this.checked) {
                    item.classList.add('linked');
                    statusIcon.innerHTML = '<i class="fas fa-link text-success"></i>';
                } else {
                    item.classList.remove('linked');
                    statusIcon.innerHTML = '<i class="fas fa-unlink text-muted"></i>';
                }
            });
        });
    }

    // Sauvegarder les liens device-geofence
    document.getElementById('btnSaveGeofenceLinks').addEventListener('click', async function() {
        const btn = this;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';
        btn.disabled = true;
        
        try {
            const selectedGeofences = Array.from(document.querySelectorAll('.geofence-checkbox:checked'))
                .map(cb => parseInt(cb.value));
            
            // Trouver les geofences à ajouter et à supprimer
            const toAdd = selectedGeofences.filter(id => !deviceGeofenceLinks.includes(id));
            const toRemove = deviceGeofenceLinks.filter(id => !selectedGeofences.includes(id));
            
            // Ajouter les nouveaux liens
            for (const geofenceId of toAdd) {
                await fetch('/api/traccar/permissions', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        deviceId: currentLinkDeviceId,
                        geofenceId: geofenceId
                    })
                });
            }
            
            // Supprimer les liens retirés
            for (const geofenceId of toRemove) {
                await fetch('/api/traccar/permissions', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        deviceId: currentLinkDeviceId,
                        geofenceId: geofenceId
                    })
                });
            }
            
            // Fermer le modal
            bootstrap.Modal.getInstance(document.getElementById('linkGeofenceModal')).hide();
            
            // Rafraîchir les données pour mettre à jour l'affichage
            await loadDevices();
            
            // Notification de succès
            const addedCount = toAdd.length;
            const removedCount = toRemove.length;
            let message = 'Associations mises à jour avec succès !';
            if (addedCount > 0 || removedCount > 0) {
                message = '';
                if (addedCount > 0) message += `${addedCount} geofence(s) ajoutée(s). `;
                if (removedCount > 0) message += `${removedCount} geofence(s) retirée(s).`;
            }
            alert(message);
            
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la mise à jour des associations');
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    });

    // Recherche dans les geofences
    document.getElementById('searchGeofence').addEventListener('input', renderGeofencesList);

    window.loadDevices = loadDevices;
});
</script>
@endpush

@push('styles')
<style>
/* Device Sidebar Fixed */
.device-sidebar {
    position: fixed !important;
    top: 55px;
    left: 0;
    height: calc(100vh - 60px);
    width: 280px;
    z-index: 100;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-right: 1px solid #e8e8e8;
    margin-top: 0;
}

.device-sidebar .sidebar-search {
    flex-shrink: 0;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    background: #fff;
}

.device-sidebar .tree-view {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 10px;
}

/* Ajuster le main-content pour compenser le sidebar fixe */
.main-container {
    display: flex;
}

.device-sidebar + .main-content {
    margin-left: 280px;
    width: calc(100% - 280px);
}

/* Scrollbar personnalisée pour le tree-view */
.device-sidebar .tree-view::-webkit-scrollbar {
    width: 6px;
}

.device-sidebar .tree-view::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.device-sidebar .tree-view::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.device-sidebar .tree-view::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* ===================== ACTION BUTTONS STYLES ===================== */

/* Boutons d'action plus petits */
.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    padding: 0;
    margin: 0 2px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    background: #f0f0f0;
    color: #333;
}

.btn-icon:hover {
    background: #e0e0e0;
    transform: scale(1.15);
}

.btn-icon.btn-edit {
    background: #cfe8ff;
    color: #0c5dd6;
}

.btn-icon.btn-edit:hover {
    background: #b5d7f0;
}

.btn-icon.btn-locate {
    background: #fff3cd;
    color: #856404;
}

.btn-icon.btn-locate:hover {
    background: #ffeaa7;
}

.btn-icon.btn-delete {
    background: #f8d7da;
    color: #721c24;
}

.btn-icon.btn-delete:hover {
    background: #f5c2c7;
}

/* Actions cell */
.actions-cell {
    text-align: center;
    white-space: nowrap;
    padding: 6px !important;
}

/* Geofence Link Modal Styles */
.geofences-list-container {
    max-height: 400px;
    overflow-y: auto;
}

.geofences-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.geofence-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.geofence-item:hover {
    border-color: #7556D6;
    background: #fafafa;
}

.geofence-item.linked {
    border-color: #28a745;
    background: linear-gradient(135deg, #f0fff4 0%, #dcffe4 100%);
}

.geofence-label {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    cursor: pointer;
    margin: 0;
    gap: 12px;
}

.geofence-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.geofence-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
}

.geofence-info i {
    color: #7556D6;
    font-size: 1.1rem;
}

.geofence-name {
    font-weight: 500;
    color: #333;
}

.link-status {
    font-size: 1rem;
}

.btn-link-geofence {
    background: linear-gradient(135deg, #7556D6 0%, #9b7be8 100%);
    color: white;
    position: relative;
}

.btn-link-geofence:hover {
    background: linear-gradient(135deg, #6244c5 0%, #8a6ad7 100%);
    transform: scale(1.1);
}

.btn-link-geofence.has-links {
    background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
}

.btn-link-geofence.has-links:hover {
    background: linear-gradient(135deg, #218838 0%, #28a745 100%);
}

.btn-link-geofence .link-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #dc3545;
    color: white;
    font-size: 10px;
    font-weight: bold;
    min-width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}

/* Bouton Lier Utilisateur */
.btn-link-user {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    color: white;
    position: relative;
}

.btn-link-user:hover {
    background: linear-gradient(135deg, #138496 0%, #1aa179 100%);
    transform: scale(1.1);
}

.btn-link-user.has-links {
    background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
}

.btn-link-user.has-links:hover {
    background: linear-gradient(135deg, #218838 0%, #28a745 100%);
}

.btn-link-user .link-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #28a745;
    color: white;
    font-size: 10px;
    font-weight: bold;
    min-width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}

.device-info-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

/* Monitor Attributes Config */
.monitor-attributes-config {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

.attributes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
}

.attribute-checkbox {
    background: white;
    border-radius: 8px;
    padding: 10px 12px;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    cursor: pointer;
}

.attribute-checkbox:hover {
    border-color: #7556D6;
    background: #f5f3ff;
}

.attribute-checkbox input[type="checkbox"] {
    display: none;
}

.attribute-checkbox input[type="checkbox"]:checked + label {
    color: #7556D6;
}

.attribute-checkbox input[type="checkbox"]:checked + label i {
    transform: scale(1.2);
}

.attribute-checkbox:has(input:checked) {
    border-color: #7556D6;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    box-shadow: 0 2px 8px rgba(117, 86, 214, 0.15);
}

.attribute-checkbox label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 0.9rem;
    color: #495057;
    margin: 0;
    width: 100%;
}

.attribute-checkbox label i {
    font-size: 1.1rem;
    transition: transform 0.2s ease;
    width: 20px;
    text-align: center;
}

.attribute-checkbox label span {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ===================== RESPONSIVE STYLES ===================== */

/* Tablet - 991px et moins */
@media (max-width: 991px) {
    /* Sidebar devient une barre horizontale en haut */
    .device-sidebar {
        position: fixed !important;
        top: 50px !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        height: auto !important;
        max-height: 180px;
        flex-direction: row;
        border-right: none;
        border-bottom: 2px solid #e8e8e8;
        z-index: 99;
    }
    
    .device-sidebar .sidebar-search {
        width: 40%;
        min-width: 200px;
        padding: 10px;
        border-bottom: none;
        border-right: 1px solid #f0f0f0;
    }
    
    .device-sidebar .tree-view {
        width: 60%;
        max-height: 160px;
        overflow-y: auto;
        padding: 8px;
    }
    
    /* Main content s'adapte */
    .device-sidebar + .main-content {
        margin-left: 0 !important;
        margin-top: 180px;
        width: 100% !important;
    }
    
    /* Filtres en grille 2 colonnes */
    .filters-row {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .filter-group {
        min-width: unset !important;
    }
    
    /* Boutons d'action en wrap */
    .action-buttons {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .action-buttons .btn {
        padding: 8px 12px;
        font-size: 0.85rem;
    }
    
    /* Table responsive */
    .device-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .device-table th,
    .device-table td {
        padding: 10px 8px;
        white-space: nowrap;
    }
    
    /* Colonnes masquées sur tablette */
    .device-table .col-model,
    .device-table .col-contact {
        display: none;
    }
    
    /* Modals */
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .modal-lg {
        max-width: calc(100% - 20px);
    }
    
    /* Card header */
    .card-header-custom {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    /* Attributes grid */
    .attributes-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Mobile - 600px et moins */
@media (max-width: 600px) {
    /* Sidebar empilée verticalement */
    .device-sidebar {
        flex-direction: column;
        max-height: 250px;
    }
    
    .device-sidebar .sidebar-search {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .device-sidebar .tree-view {
        width: 100%;
        max-height: 150px;
    }
    
    /* Main content ajusté */
    .device-sidebar + .main-content {
        margin-top: 250px;
    }
    
    /* Filtres en 1 colonne */
    .filters-row {
        grid-template-columns: 1fr !important;
    }
    
    /* Boutons d'action pleine largeur */
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
    
    /* Masquer plus de colonnes */
    .device-table .col-phone,
    .device-table .col-group,
    .device-table .col-category {
        display: none;
    }
    
    /* Conserver seulement nom, statut, actions */
    .device-table th,
    .device-table td {
        padding: 8px 6px;
        font-size: 0.85rem;
    }
    
    /* Boutons d'action de la table */
    .device-actions {
        flex-direction: column;
        gap: 4px;
    }
    
    .device-actions .btn {
        padding: 6px 8px;
        font-size: 0.75rem;
    }
    
    /* Content card */
    .content-card {
        padding: 10px;
        margin: 10px;
        border-radius: 8px;
    }
    
    /* Modal forms */
    .modal-body .row {
        flex-direction: column;
    }
    
    .modal-body .col-md-6,
    .modal-body .col-md-12 {
        width: 100%;
        margin-bottom: 10px;
    }
    
    /* Attributes grid */
    .attributes-grid {
        grid-template-columns: 1fr;
    }
    
    /* Geofence items */
    .geofences-list-container {
        max-height: 300px;
    }
    
    .geofence-label {
        padding: 10px;
        font-size: 0.9rem;
    }
    
    /* Status badges */
    .status-badge {
        font-size: 0.7rem;
        padding: 3px 6px;
    }
    
    /* Device info header */
    .device-info-header {
        padding: 10px;
    }
    
    .device-info-header h6 {
        font-size: 0.9rem;
    }
}

/* Très petits écrans - 400px et moins */
@media (max-width: 400px) {
    .device-sidebar {
        max-height: 200px;
    }
    
    .device-sidebar .tree-view {
        max-height: 120px;
    }
    
    .device-sidebar + .main-content {
        margin-top: 200px;
    }
    
    .tree-item-content {
        font-size: 0.8rem;
    }
    
    /* Actions table simplifiées */
    .device-actions .btn span {
        display: none;
    }
    
    .device-actions .btn i {
        margin: 0;
    }
    
    /* Card header */
    .card-header-custom h3 {
        font-size: 1.1rem;
    }
    
    /* Filter labels */
    .filter-group label {
        font-size: 0.8rem;
    }
    
    .filter-input,
    .filter-select {
        font-size: 0.85rem;
        padding: 8px 10px;
    }
}

/* Animation pour transition fluide */
.device-sidebar,
.device-sidebar + .main-content {
    transition: all 0.3s ease;
}

/* Fix pour le scroll du body sur mobile */
@media (max-width: 991px) {
    .main-container {
        flex-direction: column;
    }
    
    body.modal-open {
        overflow: hidden;
    }
}
</style>
@endpush

@endsection
