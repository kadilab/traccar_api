@extends('layouts.app')

@section('title', 'Monitor - Traccar TF')

@section('content')

<div class="monitor-container">
    <!-- Sidebar -->
    <aside class="monitor-sidebar">
        <div class="sidebar-header">
            <h4>
                <i class="fas fa-map-marker-alt"></i>
                Suivi en direct
            </h4>
            <div class="realtime-indicator active" id="realtimeIndicator">
                <span class="realtime-dot"></span>
                <span class="realtime-text">Live</span>
            </div>
        </div>
        
        <div class="sidebar-search">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="deviceSearch" class="search-input" placeholder="Rechercher un device...">
            </div>
        </div>

        <!-- Filtres rapides -->
        <div class="quick-filters">
            <button class="filter-btn active" data-filter="all">
                Tous <span class="count" id="countAll">0</span>
            </button>
            <button class="filter-btn" data-filter="online">
                <span class="status-dot online"></span> Online <span class="count" id="countOnline">0</span>
            </button>
            <button class="filter-btn" data-filter="offline">
                <span class="status-dot offline"></span> Offline <span class="count" id="countOffline">0</span>
            </button>
        </div>
        
        <div class="device-tree" id="deviceTree">
            <div class="tree-loading">
                <div class="spinner-small"></div>
                <span>Chargement...</span>
            </div>
        </div>
    </aside>

    <!-- Map Container -->
    <main class="map-container">
 <div class="legend-bar">
    <div class="legend-item">
        <i class="fas fa-map-marker-alt moving"></i>
        <span class="legend-text">Moving (0)</span>
    </div>
    <div class="legend-item">
        <i class="fas fa-map-marker-alt stopped"></i>
        <span class="legend-text">Stopped (0)</span>
    </div>
    <div class="legend-item">
        <i class="fas fa-map-marker-alt idling"></i>
        <span class="legend-text">Idling (0)</span>
    </div>
    <div class="legend-item">
        <i class="fas fa-map-marker-alt offline "></i>
        <span class="legend-text">Offline (0)</span>
    </div>
   

    </div>
        <div id="map"></div>
        
        <!-- Device Info Panel -->
        <div class="device-info-panel compact" id="deviceInfoPanel">
            <div class="compact-panel-header">
                <div class="device-identity">
                    <span class="device-name" id="panelDeviceName">Sélectionnez un device</span>
                    <span class="device-imei" id="panelDeviceImei">-</span>
                </div>
                <button class="panel-close-btn" id="closePanel">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="compact-panel-body" id="panelBody">
                <div class="no-device-selected">
                    <i class="fas fa-map-marker-alt fa-2x"></i>
                    <p>Cliquez sur un device</p>
                </div>
            </div>
        </div>

        <!-- Map Controls -->
        <div class="map-controls">
            <button class="map-control-btn" id="btnCenterAll" title="Voir tous les devices">
                <i class="fas fa-expand-arrows-alt"></i>
            </button>
            <button class="map-control-btn" id="btnToggleTraffic" title="Afficher le trafic">
                <i class="fas fa-traffic-light"></i>
            </button>
            <button class="map-control-btn active" id="btnAutoFollow" title="Suivi automatique">
                <i class="fas fa-crosshairs"></i>
            </button>
        </div>

        <!-- Speed & Info Bar -->

    </main>
</div>

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

/* ========== MONITOR PAGE LAYOUT ========== */
.monitor-container {
    display: flex;
    height: calc(100vh - 50px);
    margin-top: 50px;
    overflow: hidden;
    background: #f8faff;
}

/* Sidebar */
.monitor-sidebar {
    width: 280px;
    min-width: 220px;
    background: #fff;
    border-right: 1px solid #e3eafc;
    display: flex;
    flex-direction: column;
    z-index: 10;
    transition: transform 0.3s, left 0.3s;
}

/* Map Container */
.map-container {
    flex: 1;
    position: relative;
    min-width: 0;
    background: #e3f0ff;
    display: flex;
    flex-direction: column;
}

#map {
    flex: 1;
    width: 100%;
    min-height: 300px;
}

/* Legend Bar */
.legend-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding: 8px 15px;
    background: linear-gradient(90deg, #f8faff 0%, #e3f0ff 100%);
    border-bottom: 1px solid #e3eafc;
    align-items: center;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.legend-text {
    font-size: 12px;
    font-family: inherit;
    color: #555;
}

.legend-item i.moving { color: #28a745; }
.legend-item i.stopped { color: #dc3545; }
.legend-item i.idling { color: #ffc107; }
.legend-item i.offline { color: #6c757d; }

/* Device Info Panel */
.device-info-panel.compact {
    position: absolute;
    bottom: 20px;
    left: 20px;
    width: 320px;
    max-width: calc(100vw - 40px);
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.13);
    z-index: 1000;
    overflow: hidden;
}

.compact-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: linear-gradient(135deg, #1e88e5 0%, #1976d2 100%);
    color: white;
}

.device-identity {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.device-identity .device-name {
    font-size: 15px;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.device-identity .device-imei {
    font-size: 11px;
    opacity: 0.85;
    font-family: monospace;
}

.panel-close-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: white;
    transition: background 0.2s;
    font-size: 14px;
    flex-shrink: 0;
}

.panel-close-btn:hover {
    background: rgba(255,255,255,0.35);
}

.compact-panel-body {
    padding: 12px 14px;
    max-height: 320px;
    overflow-y: auto;
}

.compact-panel-body .no-device-selected {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.compact-panel-body .no-device-selected i {
    opacity: 0.3;
    margin-bottom: 10px;
    display: block;
}

.compact-panel-body .no-device-selected p {
    margin: 0;
    font-size: 13px;
}

/* Map Controls */
.map-controls {
    position: absolute;
    right: 16px;
    top: 50px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 900;
}

.map-control-btn {
    width: 40px;
    height: 40px;
    background: #fff;
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #1e88e5;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}

.map-control-btn:hover, .map-control-btn.active {
    background: #1e88e5;
    color: #fff;
    box-shadow: 0 4px 12px rgba(30,136,229,0.18);
}

/* Dynamic Indicators Styles */
.dynamic-indicators {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 8px;
    padding: 10px 0;
    border-top: 1px solid rgba(0,0,0,0.08);
    border-bottom: 1px solid rgba(0,0,0,0.08);
    margin: 10px 0;
}

.dynamic-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    background: rgba(0,0,0,0.04);
    transition: all 0.2s ease;
}

.dynamic-indicator.active {
    background: rgba(117, 86, 214, 0.1);
}

.dynamic-indicator.inactive {
    opacity: 0.5;
}

.indicator-icon {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.indicator-icon.warning { background: rgba(255, 193, 7, 0.15); color: #d39e00; }
.indicator-icon.success { background: rgba(40, 167, 69, 0.15); color: #28a745; }
.indicator-icon.danger { background: rgba(220, 53, 69, 0.15); color: #dc3545; }
.indicator-icon.info { background: rgba(23, 162, 184, 0.15); color: #17a2b8; }
.indicator-icon.primary { background: rgba(117, 86, 214, 0.15); color: #7556D6; }
.indicator-icon.secondary { background: rgba(108, 117, 125, 0.15); color: #6c757d; }

.indicator-info {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
}

.indicator-label {
    font-size: 0.65rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.indicator-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2d3748;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.speed-display {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(117, 86, 214, 0.18);
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    color: #7556D6;
}

.speed-display i {
    color: #a78bfa;
}

/* Scrollable panel body */
.compact-panel-body::-webkit-scrollbar {
    width: 5px;
}

.compact-panel-body::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.04);
    border-radius: 3px;
}

.compact-panel-body::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.13);
    border-radius: 3px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 991px) {
    .monitor-container {
        flex-direction: column;
        height: calc(100vh - 50px);
    }
    .monitor-sidebar {
        width: 100vw;
        min-width: 0;
        max-width: 100vw;
        height: 220px;
        min-height: 120px;
        border-right: none;
        border-bottom: 1px solid #e3eafc;
        flex-direction: column;
        overflow-y: auto;
    }
    .map-container {
        min-height: 250px;
        height: calc(100vh - 270px);
    }
    .device-info-panel.compact {
        left: 10px;
        right: 10px;
        bottom: 12px;
        width: auto;
        max-width: 100vw;
    }
    .legend-bar {
        padding: 6px 8px;
        gap: 10px;
    }
}
@media (max-width: 600px) {
    .monitor-sidebar {
        height: 160px;
        min-height: 80px;
    }
    .map-container {
        height: calc(100vh - 210px);
    }
    .device-info-panel.compact {
        left: 4px;
        right: 4px;
        bottom: 6px;
        border-radius: 10px;
    }
    .compact-panel-body {
        max-height: 180px;
    }
    .legend-bar {
        padding: 4px 6px;
        gap: 8px;
        font-size: 11px;
    }
    .map-controls {
        right: 8px;
        top: 12px;
    }
    .map-control-btn {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
}
</style>
@endpush

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Monitor page loaded');
    
    let map;
    let markers = {};
    let allDevices = [];
    let allGroups = [];
    let positions = {};
    let selectedDeviceId = null;
    let autoFollow = true;
    let refreshInterval = null;
    const REFRESH_RATE = 3000; // 3 secondes pour le temps réel
    
    // Initialiser la carte
    initMap();
    
    // Charger les données
    loadGroups();
    loadDevices();
    loadPositions();
    
    // Démarrer le rafraîchissement temps réel
    startRealTimeUpdates();
    
    // Event listeners
    document.getElementById('deviceSearch').addEventListener('input', debounce(filterDevices, 300));
    document.getElementById('closePanel').addEventListener('click', closePanel);
    document.getElementById('btnCenterAll').addEventListener('click', centerAllDevices);
    document.getElementById('btnAutoFollow').addEventListener('click', toggleAutoFollow);
    
    // Filtres rapides
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterDevices();
        });
    });
    
    // Initialiser la carte Leaflet
    function initMap() {
        map = L.map('map', {
            center: [14.6937, -17.4441], // Dakar par défaut
            zoom: 12,
            zoomControl: false
        });
        
        // Ajouter le layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Ajouter le contrôle de zoom en bas à droite
        L.control.zoom({ position: 'bottomright' }).addTo(map);
    }
    
    // Charger les groupes
    async function loadGroups() {
        try {
            const response = await fetch('/api/traccar/groups');
            const data = await response.json();
            if (data.success) {
                allGroups = data.groups || [];
            }
        } catch (error) {
            console.error('Erreur chargement groupes:', error);
        }
    }
    
    // Charger les devices
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
            console.error('Erreur chargement devices:', error);
        }
    }
    
    // Charger les positions
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
                
                // Mettre à jour le panel si un device est sélectionné
                if (selectedDeviceId) {
                    updateDevicePanel(selectedDeviceId);
                }
            }
        } catch (error) {
            console.error('Erreur chargement positions:', error);
        }
    }
    
    // Démarrer les mises à jour temps réel
    function startRealTimeUpdates() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        refreshInterval = setInterval(() => {
            loadPositions();
            loadDevices();
        }, REFRESH_RATE);
        
        console.log('Real-time updates started (every ' + (REFRESH_RATE/1000) + 's)');
    }
    
    // Mettre à jour l'heure de dernière MAJ
    function updateLastRefreshTime() {
        const now = new Date();
        document.getElementById('lastUpdate').textContent = now.toLocaleTimeString('fr-FR');
    }
    
    // Mettre à jour les compteurs
    function updateCounts() {
        const online = allDevices.filter(d => d.status === 'online').length;
        const offline = allDevices.filter(d => d.status === 'offline').length;
        
        document.getElementById('countAll').textContent = allDevices.length;
        document.getElementById('countOnline').textContent = online;
        document.getElementById('countOffline').textContent = offline;
        document.getElementById('displayedDevices').textContent = allDevices.length;
    }
    
    // Construire l'arbre des devices par groupe
    function buildDeviceTree() {
        const container = document.getElementById('deviceTree');
        const search = document.getElementById('deviceSearch').value.toLowerCase();
        const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'all';
        
        // Filtrer les devices
        let filteredDevices = allDevices.filter(device => {
            const matchSearch = !search || 
                device.name?.toLowerCase().includes(search) ||
                device.uniqueId?.toLowerCase().includes(search);
            
            let matchFilter = true;
            if (activeFilter === 'online') matchFilter = device.status === 'online';
            if (activeFilter === 'offline') matchFilter = device.status === 'offline';
            
            return matchSearch && matchFilter;
        });
        
        // Grouper par groupe
        const grouped = {};
        grouped['Sans groupe'] = filteredDevices.filter(d => !d.groupId);
        
        allGroups.forEach(group => {
            const groupDevices = filteredDevices.filter(d => d.groupId === group.id);
            if (groupDevices.length > 0) {
                grouped[group.name] = groupDevices;
            }
        });
        
        let html = '';
        for (const [groupName, devices] of Object.entries(grouped)) {
            if (devices.length > 0) {
                html += `
                    <div class="group-node expanded">
                        <div class="group-header" onclick="toggleGroup(this)">
                            <i class="fas fa-chevron-right arrow"></i>
                            <i class="fas fa-folder folder-icon"></i>
                            <span class="group-name">${groupName}</span>
                            <span class="group-count">${devices.length}</span>
                        </div>
                        <div class="group-devices">
                            ${devices.map(device => {
                                const pos = positions[device.id];
                                const speed = pos ? Math.round(pos.speed * 1.852) : 0; // knots to km/h
                                return `
                                    <div class="device-item ${selectedDeviceId === device.id ? 'selected' : ''}" 
                                         data-id="${device.id}" 
                                         onclick="selectDevice(${device.id})">
                                        <span class="device-status ${device.status || 'unknown'}"></span>
                                        <div class="device-info">
                                            <div class="device-name">${device.name}</div>
                                            <div class="device-speed">${device.status === 'online' ? speed + ' km/h' : 'Hors ligne'}</div>
                                        </div>
                                        <span class="device-category">${getCategoryIcon(device.category)}</span>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }
        }
        
        container.innerHTML = html || '<div class="tree-empty">Aucun device trouvé</div>';
    }
    
    // Mettre à jour les marqueurs sur la carte
    function updateMarkers() {
        allDevices.forEach(device => {
            const pos = positions[device.id];
            if (!pos || !pos.latitude || !pos.longitude) return;
            
            const latLng = [pos.latitude, pos.longitude];
            
            if (markers[device.id]) {
                // Mettre à jour la position du marqueur existant
                markers[device.id].setLatLng(latLng);
                
                // Mettre à jour l'icône si le statut a changé
                markers[device.id].setIcon(createMarkerIcon(device));
                
                // Mettre à jour le popup
                markers[device.id].setPopupContent(createPopupContent(device, pos));
            } else {
                // Créer un nouveau marqueur
                const marker = L.marker(latLng, {
                    icon: createMarkerIcon(device)
                }).addTo(map);
                
                marker.bindPopup(createPopupContent(device, pos));
                
                marker.on('click', () => {
                    selectDevice(device.id);
                });
                
                markers[device.id] = marker;
            }
        });
        
        // Mettre à jour le tree view avec les nouvelles vitesses
        buildDeviceTree();
        
        // Si auto-follow est activé et un device est sélectionné
        if (autoFollow && selectedDeviceId && positions[selectedDeviceId]) {
            const pos = positions[selectedDeviceId];
            map.panTo([pos.latitude, pos.longitude]);
        }
    }
    
    // Créer l'icône du marqueur
    function createMarkerIcon(device) {
        const color = device.status === 'online' ? '#28a745' : '#dc3545';
        const icon = getCategoryIcon(device.category);
        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="
                    background: ${color};
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    font-size: 18px;
                    ${device.status === 'online' ? 'animation: pulse-marker 2s infinite;' : ''}
                ">
                    ${icon}
                </div>
            `,
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });
    }
    
    // Créer le contenu du popup
    function createPopupContent(device, pos) {
        const speed = pos ? Math.round(pos.speed * 1.852) : 0;
        const course = pos ? Math.round(pos.course) : 0;
        
        return `
            <div style="min-width: 20px;">
                <h6 style="margin: 0 0 8px 0;">${device.name}</h6>
                
            </div>
        `;
    }
    
    // Obtenir l'icône de catégorie
    function getCategoryIcon(category) {
        const icons = {
            'car': '<i class="fas fa-car"></i>',
            'truck': '<i class="fas fa-truck"></i>',
            'motorcycle': '<i class="fas fa-motorcycle"></i>',
            'bus': '<i class="fas fa-bus"></i>',
            'person': '<i class="fas fa-user"></i>',
            'boat': '<i class="fas fa-ship"></i>',
            'bicycle': '<i class="fas fa-bicycle"></i>',
            'animal': '<i class="fas fa-paw"></i>',
            'default': '<i class="fas fa-map-marker-alt"></i>'
        };
        return icons[category] || icons['default'];
    }
    
    // Sélectionner un device
    window.selectDevice = function(deviceId) {
        selectedDeviceId = deviceId;
        
        // Mettre à jour la sélection dans le tree
        document.querySelectorAll('.device-item').forEach(item => {
            item.classList.remove('selected');
        });
        document.querySelector(`.device-item[data-id="${deviceId}"]`)?.classList.add('selected');
        
        // Centrer la carte sur le device
        const pos = positions[deviceId];
        if (pos && pos.latitude && pos.longitude) {
            map.setView([pos.latitude, pos.longitude], 16);
            
            // Ouvrir le popup du marqueur
            if (markers[deviceId]) {
                markers[deviceId].openPopup();
            }
        }
        
        // Mettre à jour le panel d'info
        updateDevicePanel(deviceId);
    };
    
    // Mettre à jour le panel d'information du device
    function updateDevicePanel(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        const pos = positions[deviceId];
        
        if (!device) return;
        
        document.getElementById('panelDeviceName').textContent = device.name;
        document.getElementById('panelDeviceImei').textContent = device.uniqueId || '-';
        
        const speed = pos ? Math.round(pos.speed * 1.852) : 0;
        const isOnline = device.status === 'online';
        const lastUpdate = pos?.fixTime ? new Date(pos.fixTime).toLocaleString('fr-FR') : '-';
        
        // Récupérer les attributs configurés pour ce device
        const monitorAttrs = device.attributes?.monitorAttributes || [];
        const posAttrs = pos?.attributes || {};
        
        // Générer les indicateurs dynamiques basés sur les attributs configurés
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
    
    // Générer les indicateurs dynamiques
    function generateDynamicIndicators(monitorAttrs, posAttrs) {
        if (!monitorAttrs || monitorAttrs.length === 0) return '';
        
        // Configuration des attributs Traccar
        const attrConfig = {
            ignition: { 
                icon: 'fa-key', 
                label: 'Moteur', 
                color: 'warning',
                getValue: (v) => v ? 'ON' : 'OFF',
                isActive: (v) => v === true
            },
            batteryLevel: { 
                icon: 'fa-battery-three-quarters', 
                label: 'Batterie', 
                color: 'success',
                getValue: (v) => v !== undefined ? `${Math.round(v)}%` : '-',
                isActive: (v) => v > 20
            },
            battery: { 
                icon: 'fa-car-battery', 
                label: 'Batterie V', 
                color: 'info',
                getValue: (v) => v !== undefined ? `${Number(v).toFixed(1)}V` : '-',
                isActive: (v) => v > 11
            },
            fuel: { 
                icon: 'fa-gas-pump', 
                label: 'Carburant', 
                color: 'danger',
                getValue: (v) => v !== undefined ? `${Math.round(v)}%` : '-',
                isActive: (v) => v > 15
            },
            alarm: { 
                icon: 'fa-bell', 
                label: 'Alarme', 
                color: 'danger',
                getValue: (v) => v || '-',
                isActive: (v) => !!v
            },
            charge: { 
                icon: 'fa-plug', 
                label: 'Charge', 
                color: 'primary',
                getValue: (v) => v ? 'Oui' : 'Non',
                isActive: (v) => v === true
            },
            blocked: { 
                icon: 'fa-lock', 
                label: 'Bloqué', 
                color: 'secondary',
                getValue: (v) => v ? 'Oui' : 'Non',
                isActive: (v) => v === true
            },
            armed: { 
                icon: 'fa-shield-alt', 
                label: 'Armé', 
                color: 'info',
                getValue: (v) => v ? 'Oui' : 'Non',
                isActive: (v) => v === true
            },
            door: { 
                icon: 'fa-door-open', 
                label: 'Porte', 
                color: 'warning',
                getValue: (v) => v ? 'Ouverte' : 'Fermée',
                isActive: (v) => v === true
            },
            motion: { 
                icon: 'fa-running', 
                label: 'Mouvement', 
                color: 'success',
                getValue: (v) => v ? 'Oui' : 'Non',
                isActive: (v) => v === true
            },
            temperature: { 
                icon: 'fa-thermometer-half', 
                label: 'Temp.', 
                color: 'danger',
                getValue: (v) => v !== undefined ? `${Number(v).toFixed(1)}°C` : '-',
                isActive: () => true
            },
            humidity: { 
                icon: 'fa-tint', 
                label: 'Humidité', 
                color: 'info',
                getValue: (v) => v !== undefined ? `${Math.round(v)}%` : '-',
                isActive: () => true
            },
            rpm: { 
                icon: 'fa-tachometer-alt', 
                label: 'RPM', 
                color: 'primary',
                getValue: (v) => v !== undefined ? v.toLocaleString() : '-',
                isActive: (v) => v > 0
            },
            totalDistance: { 
                icon: 'fa-road', 
                label: 'Distance', 
                color: 'secondary',
                getValue: (v) => v !== undefined ? `${(v/1000).toFixed(1)} km` : '-',
                isActive: () => true
            },
            distance: { 
                icon: 'fa-route', 
                label: 'Trajet', 
                color: 'primary',
                getValue: (v) => v !== undefined ? `${(v/1000).toFixed(2)} km` : '-',
                isActive: () => true
            },
            hours: { 
                icon: 'fa-hourglass-half', 
                label: 'Heures', 
                color: 'warning',
                getValue: (v) => v !== undefined ? `${Math.round(v/3600000)}h` : '-',
                isActive: () => true
            },
            sat: { 
                icon: 'fa-satellite', 
                label: 'Satellites', 
                color: 'info',
                getValue: (v) => v !== undefined ? v : '0',
                isActive: (v) => v > 0
            },
            hdop: { 
                icon: 'fa-crosshairs', 
                label: 'Précision', 
                color: 'success',
                getValue: (v) => v !== undefined ? Number(v).toFixed(1) : '-',
                isActive: (v) => v < 2
            },
            rssi: { 
                icon: 'fa-signal', 
                label: 'Signal', 
                color: 'success',
                getValue: (v) => v !== undefined ? `${v}%` : '-',
                isActive: (v) => v > 30
            },
            power: { 
                icon: 'fa-bolt', 
                label: 'Alim.', 
                color: 'warning',
                getValue: (v) => v !== undefined ? `${Number(v).toFixed(1)}V` : '-',
                isActive: (v) => v > 10
            }
        };
        
        let indicators = '';
        monitorAttrs.forEach(attr => {
            const config = attrConfig[attr];
            if (!config) return;
            
            const value = posAttrs[attr];
            const isActive = config.isActive(value);
            const displayValue = config.getValue(value);
            
            indicators += `
                <div class="dynamic-indicator ${isActive ? 'active' : 'inactive'}" title="${config.label}">
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
    
    // Obtenir l'icône de direction
    function getDirectionIcon(course) {
        if (course >= 337.5 || course < 22.5) return '<i class="fas fa-arrow-up"></i>';
        if (course >= 22.5 && course < 67.5) return '<i class="fas fa-arrow-up" style="transform: rotate(45deg)"></i>';
        if (course >= 67.5 && course < 112.5) return '<i class="fas fa-arrow-right"></i>';
        if (course >= 112.5 && course < 157.5) return '<i class="fas fa-arrow-down" style="transform: rotate(-45deg)"></i>';
        if (course >= 157.5 && course < 202.5) return '<i class="fas fa-arrow-down"></i>';
        if (course >= 202.5 && course < 247.5) return '<i class="fas fa-arrow-down" style="transform: rotate(45deg)"></i>';
        if (course >= 247.5 && course < 292.5) return '<i class="fas fa-arrow-left"></i>';
        if (course >= 292.5 && course < 337.5) return '<i class="fas fa-arrow-up" style="transform: rotate(-45deg)"></i>';
        return '<i class="fas fa-arrow-up"></i>';
    }
    
    // Fermer le panel
    function closePanel() {
        selectedDeviceId = null;
        document.querySelectorAll('.device-item').forEach(item => {
            item.classList.remove('selected');
        });
        document.getElementById('panelDeviceName').textContent = 'Sélectionnez un device';
        document.getElementById('panelDeviceImei').textContent = '-';
        document.getElementById('panelBody').innerHTML = `
            <div class="no-device-selected">
                <i class="fas fa-map-marker-alt fa-2x"></i>
                <p>Cliquez sur un device</p>
            </div>
        `;
    }
    
    // Centrer sur tous les devices
    function centerAllDevices() {
        const bounds = [];
        for (const deviceId in markers) {
            bounds.push(markers[deviceId].getLatLng());
        }
        
        if (bounds.length > 0) {
            map.fitBounds(L.latLngBounds(bounds), { padding: [50, 50] });
        }
    }
    
    // Centrer sur un device
    window.centerOnDevice = function(deviceId) {
        const pos = positions[deviceId];
        if (pos && pos.latitude && pos.longitude) {
            map.setView([pos.latitude, pos.longitude], 17);
        }
    };
    
    // Voir l'historique
    window.viewHistory = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        console.log('View history for device:', deviceId);
        // Rediriger vers la page historique avec l'ID du device
        window.location.href = `/history?id=${deviceId}`;
    };
    
    // Envoyer une commande
    window.sendCommand = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        console.log('Send command to device:', deviceId);
        alert(`Commande vers ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    // Voir les détails du device
    window.viewDeviceDetails = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        console.log('View details for device:', deviceId);
        alert(`Détails de ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    // Voir les géozones
    window.viewGeofences = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        console.log('View geofences for device:', deviceId);
        alert(`Géozones de ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    // Voir les alertes
    window.viewAlerts = function(deviceId) {
        const device = allDevices.find(d => d.id === deviceId);
        console.log('View alerts for device:', deviceId);
        alert(`Alertes de ${device?.name || 'device'} - Fonctionnalité à venir !`);
    };
    
    // Toggle auto-follow
    function toggleAutoFollow() {
        autoFollow = !autoFollow;
        document.getElementById('btnAutoFollow').classList.toggle('active', autoFollow);
    }
    
    // Toggle groupe
    window.toggleGroup = function(header) {
        header.parentElement.classList.toggle('expanded');
    };
    
    // Filtrer les devices
    function filterDevices() {
        buildDeviceTree();
    }
    
    // Debounce
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    
    // Arrêter les mises à jour quand la page n'est pas visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                document.querySelector('.realtime-indicator').classList.remove('active');
            }
        } else {
            loadPositions();
            startRealTimeUpdates();
            document.querySelector('.realtime-indicator').classList.add('active');
        }
    });
});
</script>
@endpush
@endsection
