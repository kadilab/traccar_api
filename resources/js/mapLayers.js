/**
 * Système de gestion des couches cartographiques
 * Gère les différents fournisseurs de cartes et les couches
 */

export const mapLayers = {
    // Fournisseurs de cartes disponibles
    providers: {
        open_streets: {
            name: 'OpenStreetMap',
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        },
        here_streets: {
            name: 'Here Streets',
            url: 'https://{s}.base.maps.ls.hereapi.com/maptile/2.1/maptile/{z}/{x}/{y}/png8',
            attribution: '© Here',
            maxZoom: 18
        },
        box_streets: {
            name: 'Stamen TonerLite',
            url: 'https://tiles.stadiamaps.com/tiles/stamen_toner_lite/{z}/{x}/{y}.png',
            attribution: '© Stadia Maps',
            maxZoom: 18
        },
        Bing_streets: {
            name: 'Bing Maps',
            url: 'https://ecn.t{s}.tiles.virtualearth.net/tiles/r{q}?g=13407&mkt={mkt}&lbl=L&stl=m',
            attribution: '© Microsoft',
            maxZoom: 18
        },
        google_streets: {
            name: 'Google Streets',
            url: 'https://mt{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
            attribution: '© Google Maps',
            maxZoom: 20
        },
        google_satellite: {
            name: 'Google Satellite',
            url: 'https://mt{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
            attribution: '© Google Maps',
            maxZoom: 20
        },
        google_terrain: {
            name: 'Google Terrain',
            url: 'https://mt{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',
            attribution: '© Google Maps',
            maxZoom: 20
        },
        google_hybrid: {
            name: 'Google Hybrid',
            url: 'https://mt{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}',
            attribution: '© Google Maps',
            maxZoom: 20
        },
        arcgis_streets: {
            name: 'ArcGIS Streets',
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',
            attribution: '© Esri',
            maxZoom: 18
        },
        arcgis_satellite: {
            name: 'ArcGIS Satellite',
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            attribution: '© Esri',
            maxZoom: 18
        },
        arcgis_hybrid: {
            name: 'ArcGIS Hybrid',
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            attribution: '© Esri',
            maxZoom: 18
        }
    },

    /**
     * Obtenir la couche de tuiles pour le fournisseur spécifié
     */
    getTileLayer(providerKey = 'open_streets', options = {}) {
        const provider = this.providers[providerKey];
        if (!provider) {
            console.warn(`Provider ${providerKey} not found, using OpenStreetMap`);
            return this.getTileLayer('open_streets', options);
        }

        const defaultOptions = {
            attribution: provider.attribution,
            maxZoom: provider.maxZoom,
            ...options
        };

        return L.tileLayer(provider.url, defaultOptions);
    },

    /**
     * Obtenir tous les fournisseurs de cartes pour le contrôle des couches
     */
    getAllProviders() {
        const baseLayers = {};
        Object.keys(this.providers).forEach(key => {
            const provider = this.providers[key];
            baseLayers[provider.name] = this.getTileLayer(key);
        });
        return baseLayers;
    }
};

/**
 * Système de gestion des événements carte
 */
export const mapEvents = {
    /**
     * Initialiser les événements de base de la carte
     */
    initializeBasicEvents(map) {
        // Événement de zoom
        this.onZoomChange(map);
        
        // Événement de double-clic
        this.onDoubleClick(map);
        
        // Événement de clic droit
        this.onContextMenu(map);
        
        // Événement de changement de couche
        this.onLayerChange(map);
        
        // Événement de mouvement
        this.onMapMove(map);
    },

    /**
     * Événement déclenchée lors du changement de zoom
     */
    onZoomChange(map) {
        map.on('zoomend', (event) => {
            const zoomLevel = map.getZoom();
            console.log(`Zoom level: ${zoomLevel}`);
            
            // Déclencher un événement personnalisé
            window.dispatchEvent(new CustomEvent('mapZoomChange', {
                detail: { zoom: zoomLevel }
            }));
            
            // Adapter la taille des marqueurs selon le zoom
            this.adjustMarkersForZoom(zoomLevel);
        });
    },

    /**
     * Événement au double-clic
     */
    onDoubleClick(map) {
        map.on('dblclick', (event) => {
            console.log('Double-click detected', event.latlng);
            
            window.dispatchEvent(new CustomEvent('mapDoubleClick', {
                detail: { 
                    lat: event.latlng.lat,
                    lng: event.latlng.lng
                }
            }));
        });
    },

    /**
     * Événement au clic droit (contextmenu)
     */
    onContextMenu(map) {
        map.on('contextmenu', (event) => {
            console.log('Right-click detected', event.latlng);
            
            // Créer un menu contextuel
            this.showContextMenu(event.latlng, {
                createGeofence: true,
                createPOI: true,
                viewDeviceHistory: true
            });
        });
    },

    /**
     * Événement de changement de couche
     */
    onLayerChange(map) {
        map.on('baselayerchange', (event) => {
            console.log('Map layer changed to:', event.name);
            
            // Sauvegarder la préférence en session/localStorage
            sessionStorage.setItem('selectedMapLayer', event.name);
            localStorage.setItem('userMapPreference', event.name);
            
            window.dispatchEvent(new CustomEvent('mapLayerChange', {
                detail: { layer: event.name }
            }));
        });
    },

    /**
     * Événement de mouvement de la carte
     */
    onMapMove(map) {
        map.on('moveend', (event) => {
            const bounds = map.getBounds();
            
            window.dispatchEvent(new CustomEvent('mapMoveEnd', {
                detail: {
                    center: map.getCenter(),
                    bounds: bounds
                }
            }));
        });
    },

    /**
     * Afficher un menu contextuel personnalisé
     */
    showContextMenu(latlng, options = {}) {
        // Créer le menu contextuel
        const menuHTML = this.createContextMenuHTML(options);
        
        // Créer une popup Leaflet
        const popup = L.popup()
            .setLatLng(latlng)
            .setContent(menuHTML)
            .openOn(window.map || map);
        
        // Attacher les événements aux options du menu
        this.attachContextMenuEvents(latlng, options);
    },

    /**
     * Créer le HTML du menu contextuel
     */
    createContextMenuHTML(options) {
        let html = '<div class="map-context-menu">';
        
        if (options.createGeofence) {
            html += `
                <div class="context-menu-item" data-action="createGeofence">
                    <i class="fas fa-draw-polygon"></i>
                    <span>Créer une Géobarrière</span>
                </div>
            `;
        }
        
        if (options.createPOI) {
            html += `
                <div class="context-menu-item" data-action="createPOI">
                    <i class="fas fa-map-pin"></i>
                    <span>Créer un POI</span>
                </div>
            `;
        }
        
        if (options.viewDeviceHistory) {
            html += `
                <div class="context-menu-item" data-action="viewHistory">
                    <i class="fas fa-history"></i>
                    <span>Voir l'historique</span>
                </div>
            `;
        }
        
        html += '</div>';
        return html;
    },

    /**
     * Attacher les événements aux éléments du menu contextuel
     */
    attachContextMenuEvents(latlng, options) {
        const menuItems = document.querySelectorAll('.map-context-menu .context-menu-item');
        
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const action = e.currentTarget.dataset.action;
                
                switch(action) {
                    case 'createGeofence':
                        window.dispatchEvent(new CustomEvent('createGeofence', {
                            detail: { lat: latlng.lat, lng: latlng.lng }
                        }));
                        break;
                    case 'createPOI':
                        window.dispatchEvent(new CustomEvent('createPOI', {
                            detail: { lat: latlng.lat, lng: latlng.lng }
                        }));
                        break;
                    case 'viewHistory':
                        window.dispatchEvent(new CustomEvent('viewDeviceHistory', {
                            detail: { lat: latlng.lat, lng: latlng.lng }
                        }));
                        break;
                }
            });
        });
    },

    /**
     * Ajuster la taille des marqueurs selon le zoom
     */
    adjustMarkersForZoom(zoomLevel) {
        const markers = document.querySelectorAll('.leaflet-marker-pane img');
        
        markers.forEach(marker => {
            if (zoomLevel < 6) {
                marker.style.width = '20px';
                marker.style.height = '20px';
            } else if (zoomLevel < 10) {
                marker.style.width = '24px';
                marker.style.height = '24px';
            } else {
                marker.style.width = '32px';
                marker.style.height = '32px';
            }
        });
    },

    /**
     * Centrer la carte sur un point
     */
    panToPoint(map, lat, lng, zoom = 12) {
        map.setView([lat, lng], zoom);
    },

    /**
     * Zoomer jusqu'aux limites
     */
    fitBounds(map, bounds) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }
};

/**
 * Styles CSS pour le menu contextuel
 */
const mapContextMenuStyles = `
    .map-context-menu {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        padding: 0;
        min-width: 200px;
        z-index: 1000;
    }

    .context-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
        font-size: 14px;
    }

    .context-menu-item:last-child {
        border-bottom: none;
    }

    .context-menu-item:hover {
        background: #f8f9fa;
        color: #1976d2;
    }

    .context-menu-item i {
        width: 20px;
        text-align: center;
    }
`;

// Injecter les styles
const style = document.createElement('style');
style.textContent = mapContextMenuStyles;
document.head.appendChild(style);
