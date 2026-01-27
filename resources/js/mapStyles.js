/**
 * Configuration des styles du menu contextuel et des éléments carte
 */

const mapStyles = `
    /* Menu contextuel de la carte */
    .map-context-menu {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        padding: 4px 0;
        min-width: 220px;
        z-index: 1000;
        animation: slideIn 0.2s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        font-weight: 500;
    }

    .context-menu-item:last-child {
        border-bottom: none;
    }

    .context-menu-item:hover {
        background: #f0f7ff;
        color: #1976d2;
        padding-left: 20px;
    }

    .context-menu-item i {
        width: 20px;
        text-align: center;
        color: #1976d2;
    }

    /* Marqueurs de véhicule */
    .vehicle-popup {
        padding: 10px;
        font-size: 13px;
    }

    .vehicle-popup strong {
        display: block;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .vehicle-popup .status-online {
        background: #dcfce7;
        color: #166534;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 8px;
    }

    .vehicle-popup .status-offline {
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 8px;
    }

    .vehicle-popup a {
        color: #1976d2;
        text-decoration: none;
        font-weight: 600;
        margin-top: 8px;
        display: block;
    }

    .vehicle-popup a:hover {
        text-decoration: underline;
    }

    /* Géobarrières */
    .geofence-popup {
        padding: 10px;
        font-size: 13px;
    }

    .geofence-popup strong {
        display: block;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .geofence-popup a {
        color: #1976d2;
        text-decoration: none;
        font-weight: 600;
        display: block;
        margin-top: 8px;
    }

    /* POI Markers */
    .poi-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 2px solid #1976d2;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .poi-icon i {
        color: #1976d2;
        font-size: 16px;
    }

    .poi-popup {
        padding: 10px;
        font-size: 13px;
    }

    .poi-popup strong {
        display: block;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .poi-popup a {
        color: #1976d2;
        text-decoration: none;
        font-weight: 600;
        display: block;
        margin-top: 8px;
    }

    /* Contrôles de la carte */
    .leaflet-control {
        background: white !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .leaflet-control-zoom {
        border: none !important;
    }

    .leaflet-control-zoom a {
        background-color: white !important;
        border: none !important;
        color: #1976d2 !important;
        font-weight: bold;
        width: 36px !important;
        height: 36px !important;
        line-height: 36px !important;
        border-radius: 4px !important;
        margin-bottom: 4px !important;
    }

    .leaflet-control-zoom a:hover {
        background-color: #f0f7ff !important;
    }

    .leaflet-control-zoom a.leaflet-disabled {
        color: #ccc !important;
    }

    .leaflet-control-layers {
        background-color: white !important;
    }

    .leaflet-control-layers label {
        margin-bottom: 8px;
    }

    .leaflet-control-layers-toggle {
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="%231976d2" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>') !important;
        background-position: 2px 2px !important;
    }

    /* Popup personnalisées */
    .leaflet-popup-content-wrapper {
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .leaflet-popup-tip {
        background-color: white !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .map-context-menu {
            min-width: 180px;
        }

        .context-menu-item {
            padding: 10px 12px;
            font-size: 13px;
        }

        .leaflet-control-zoom a {
            width: 32px !important;
            height: 32px !important;
            line-height: 32px !important;
        }
    }
`;

// Injecter les styles au chargement du DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectStyles);
} else {
    injectStyles();
}

function injectStyles() {
    const style = document.createElement('style');
    style.textContent = mapStyles;
    document.head.appendChild(style);
}

export { mapStyles };
