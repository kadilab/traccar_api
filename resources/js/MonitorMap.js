/**
 * Exemple d'intégration des systèmes de couches et événements carte
 * À utiliser dans votre vue monitor.blade.php
 */

import { mapLayers, mapEvents } from './mapLayers.js';

export class MonitorMap {
    constructor(mapElementId = 'map') {
        this.mapElementId = mapElementId;
        this.map = null;
        this.selectedLayer = localStorage.getItem('userMapPreference') || 'OpenStreetMap';
    }

    /**
     * Initialiser la carte avec les couches et événements
     */
    initializeMap() {
        // Récupérer la préférence de l'utilisateur ou utiliser la valeur par défaut
        const userMapType = localStorage.getItem('userMapType') || 'open_streets';
        
        // Obtenir la couche de tuiles
        const tileLayer = mapLayers.getTileLayer(userMapType);
        
        // Obtenir tous les fournisseurs pour le contrôle des couches
        const baseLayers = mapLayers.getAllProviders();
        
        // Créer un groupe pour les marqueurs
        const markers = L.layerGroup();
        
        // Créer une couche pour les géofences
        const geofencesLayer = L.layerGroup();
        
        // Créer une couche pour les POI
        const poiLayer = L.layerGroup();
        
        // Initialiser la carte
        this.map = L.map(this.mapElementId, {
            center: [20, 0],
            zoom: 2,
            layers: [tileLayer, markers, geofencesLayer, poiLayer],
            zoomControl: true,
            fullscreenControl: true,
            fullscreenControlOptions: {
                title: 'Vue plein écran',
                titleCancel: 'Quitter le plein écran'
            }
        });

        // Ajouter le contrôle des couches
        const overlays = {
            'Marqueurs': markers,
            'Géobarrières': geofencesLayer,
            'POI': poiLayer
        };

        L.control.layers(baseLayers, overlays, {
            position: 'topright',
            collapsed: true
        }).addTo(this.map);

        // Ajouter le contrôle de zoom
        L.control.zoom({
            position: 'topleft',
            zoomInTitle: 'Agrandir',
            zoomOutTitle: 'Réduire'
        }).addTo(this.map);

        // Ajouter l'échelle
        L.control.scale({
            imperial: false,
            maxWidth: 200
        }).addTo(this.map);

        // Initialiser les événements
        mapEvents.initializeBasicEvents(this.map);

        // Stocker les références des couches
        this.markersLayer = markers;
        this.geofencesLayer = geofencesLayer;
        this.poiLayer = poiLayer;

        console.log('Carte initialisée avec succès');
    }

    /**
     * Ajouter un marqueur de véhicule
     */
    addVehicleMarker(lat, lng, name, id, status = 'online') {
        const iconUrl = this.getStatusIcon(status);
        const customIcon = L.icon({
            iconUrl: iconUrl,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
            className: `marker-${status}`
        });

        const marker = L.marker([lat, lng], { icon: customIcon })
            .bindPopup(`
                <div class="vehicle-popup">
                    <strong>${name}</strong><br>
                    <span class="status-${status}">${status.toUpperCase()}</span><br>
                    <a href="#" onclick="viewVehicleDetails(${id})">Détails</a>
                </div>
            `)
            .addTo(this.markersLayer);

        marker.vehicleId = id;
        marker.vehicleName = name;

        return marker;
    }

    /**
     * Ajouter une géobarrière
     */
    addGeofence(coordinates, name, id, color = '#1976d2') {
        const polygon = L.polygon(coordinates, {
            color: color,
            fillColor: color,
            fillOpacity: 0.2,
            weight: 2
        })
            .bindPopup(`
                <div class="geofence-popup">
                    <strong>${name}</strong><br>
                    <a href="#" onclick="editGeofence(${id})">Modifier</a>
                </div>
            `)
            .addTo(this.geofencesLayer);

        polygon.geofenceId = id;
        return polygon;
    }

    /**
     * Ajouter un POI (Point d'intérêt)
     */
    addPOI(lat, lng, name, id, icon = 'fa-map-pin') {
        const poiIcon = L.divIcon({
            html: `<div class="poi-marker"><i class="fas ${icon}"></i></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
            className: 'poi-icon'
        });

        const marker = L.marker([lat, lng], { icon: poiIcon })
            .bindPopup(`
                <div class="poi-popup">
                    <strong>${name}</strong><br>
                    <a href="#" onclick="editPOI(${id})">Modifier</a>
                </div>
            `)
            .addTo(this.poiLayer);

        marker.poiId = id;
        return marker;
    }

    /**
     * Centrer la carte sur un véhicule
     */
    centerOnVehicle(lat, lng, zoom = 12) {
        mapEvents.panToPoint(this.map, lat, lng, zoom);
    }

    /**
     * Obtenir l'icône selon le statut
     */
    getStatusIcon(status) {
        const icons = {
            online: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            offline: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
            moving: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            stopped: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png'
        };
        return icons[status] || icons.offline;
    }

    /**
     * Masquer/Afficher une couche
     */
    toggleLayer(layerName) {
        const layers = {
            'markers': this.markersLayer,
            'geofences': this.geofencesLayer,
            'poi': this.poiLayer
        };

        const layer = layers[layerName];
        if (layer) {
            if (this.map.hasLayer(layer)) {
                this.map.removeLayer(layer);
            } else {
                this.map.addLayer(layer);
            }
        }
    }

    /**
     * Effacer tous les marqueurs
     */
    clearMarkers() {
        this.markersLayer.clearLayers();
    }

    /**
     * Effacer toutes les géobarrières
     */
    clearGeofences() {
        this.geofencesLayer.clearLayers();
    }

    /**
     * Effacer tous les POI
     */
    clearPOI() {
        this.poiLayer.clearLayers();
    }
}

/**
 * Initialiser la carte au chargement de la page
 */
document.addEventListener('DOMContentLoaded', function() {
    const monitorMap = new MonitorMap('map');
    monitorMap.initializeMap();

    // Écouter les événements personnalisés
    window.addEventListener('createGeofence', (e) => {
        console.log('Créer une géobarrière à', e.detail);
        // Ouvrir un modal pour créer une géobarrière
    });

    window.addEventListener('createPOI', (e) => {
        console.log('Créer un POI à', e.detail);
        // Ouvrir un modal pour créer un POI
    });

    window.addEventListener('mapZoomChange', (e) => {
        console.log('Zoom changé à:', e.detail.zoom);
    });

    window.addEventListener('mapLayerChange', (e) => {
        console.log('Couche changée à:', e.detail.layer);
        localStorage.setItem('userMapType', e.detail.layer);
    });

    // Exposer l'instance globalement pour utilisation dans les templates
    window.monitorMapInstance = monitorMap;
});

// Exemple d'utilisation dans votre Blade template:
// 
// <script>
//     // Après que la carte soit initialisée
//     window.addEventListener('mapReady', () => {
//         const map = window.monitorMapInstance;
//         
//         // Ajouter des véhicules
//         map.addVehicleMarker(48.8566, 2.3522, 'Taxi-001', 1, 'online');
//         map.addVehicleMarker(48.7023, 2.1557, 'Taxi-002', 2, 'offline');
//         
//         // Ajouter des géobarrières
//         const geoCoords = [[48.8566, 2.3522], [48.8566, 2.3600], [48.8500, 2.3600]];
//         map.addGeofence(geoCoords, 'Zone Paris', 1);
//         
//         // Ajouter des POI
//         map.addPOI(48.8566, 2.3522, 'Gare du Nord', 1);
//     });
// </script>
