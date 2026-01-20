@extends('layouts.app')

@section('title', 'Événements & Notifications - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar events-sidebar">
        <div class="sidebar-header">
            <h4>
                <i class="fas fa-bell"></i>
                Notifications
            </h4>
        </div>
        
        <div class="sidebar-search">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="notificationSearch" class="search-input" placeholder="Rechercher...">
            </div>
        </div>

        <div class="notification-types-list" id="notificationTypesList">
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
                <h3>Gestion des Notifications</h3>
                <div class="header-actions">
                    <button class="btn btn-primary" id="btnAddNotification">
                        <i class="fas fa-plus"></i>
                        Nouvelle notification
                    </button>
                    <button class="btn btn-success" id="btnRefresh">
                        <i class="fas fa-sync-alt"></i>
                        Rafraîchir
                    </button>
                </div>
            </div>

            <!-- Information Section: Événements vs Notifications -->
            <div class="alert alert-info border-0 rounded-lg mb-4" style="background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); border-left: 4px solid #17a2b8;">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="text-primary mb-2">
                            <i class="fas fa-database me-2"></i> L'Événement
                        </h5>
                        <p class="mb-0 small">
                            <strong>Enregistrement du fait</strong> - Stocké dans la base de données et consultable à tout moment dans l'onglet "Événements" ou dans les rapports historiques.
                        </p>
                        <p class="mt-2 mb-0 small text-muted">
                            Exemples: Démarrage du moteur, Sortie de géofence, Batterie faible, Appareil arrêté
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-danger mb-2">
                            <i class="fas fa-bell me-2"></i> La Notification
                        </h5>
                        <p class="mb-0 small">
                            <strong>Alerte immédiate</strong> - Moyen de vous prévenir en temps réel (Web, Email, SMS, Telegram, Push).
                        </p>
                        <p class="mt-2 mb-0 small text-muted">
                            Pour chaque événement, vous choisissez si vous voulez être notifié et par quel canal.
                        </p>
                    </div>
                </div>
                <hr class="my-3">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-2">
                            <i class="fas fa-cogs me-2"></i> Configuration en 3 étapes :
                        </h6>
                        <ol class="mb-0 small">
                            <li><strong>Créer la notification</strong> - Choisissez le type d'événement (ex: Entrée de zone) et le canal (ex: Web ou Telegram)</li>
                            <li><strong>Lier à l'appareil</strong> - Allez dans Appareils, sélectionnez votre appareil, cliquez sur Notifications et cochez celles à activer</li>
                            <li><strong>Lier à l'utilisateur</strong> - Assurez-vous que l'utilisateur possède les permissions pour ces notifications</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalNotifications">0</span>
                        <span class="stat-label">Total</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="activeNotifications">0</span>
                        <span class="stat-label">Actives</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="alarmNotifications">0</span>
                        <span class="stat-label">Alarmes</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="geofenceNotifications">0</span>
                        <span class="stat-label">Geofences</span>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>Type</label>
                        <select id="filterType" class="filter-select">
                            <option value="">Tous les types</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Statut</label>
                        <select id="filterStatus" class="filter-select">
                            <option value="">Tous</option>
                            <option value="active">Actives</option>
                            <option value="inactive">Inactives</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Canal</label>
                        <select id="filterChannel" class="filter-select">
                            <option value="">Tous</option>
                            <option value="web">Web</option>
                            <option value="mail">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notifications Table -->
            <div class="table-container">
                <table class="data-table" id="notificationsTable">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Canaux</th>
                            <th>Calendrier</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="notificationsTableBody">
                        <tr>
                            <td colspan="6">
                                <div class="table-loading">
                                    <div class="spinner-small"></div>
                                    <span>Chargement des notifications...</span>
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
                <div class="pagination" id="pagination"></div>
            </div>
        </div>

        <!-- Événements récents -->
        <div class="content-card mt-4">
            <div class="card-header-custom">
                <h3><i class="fas fa-history me-2"></i>Événements récents</h3>
                <div class="header-actions">
                    <select id="eventDeviceFilter" class="filter-select">
                        <option value="">Tous les devices</option>
                    </select>
                    <input type="date" id="eventDateFrom" class="filter-input">
                    <input type="date" id="eventDateTo" class="filter-input">
                    <button class="btn btn-outline-primary" id="btnLoadEvents">
                        <i class="fas fa-search"></i>
                        Charger
                    </button>
                </div>
            </div>

            <div class="events-list" id="eventsList">
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                    <p>Sélectionnez un device et une période pour voir les événements</p>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajouter/Modifier Notification -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">
                    <i class="fas fa-bell me-2"></i>
                    Nouvelle notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="notificationForm">
                    <input type="hidden" id="notificationId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type d'événement *</label>
                            <select class="form-select" id="notificationType" required>
                                <option value="">Sélectionner un type</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Calendrier</label>
                            <select class="form-select" id="notificationCalendar">
                                <option value="">Toujours actif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Canaux de notification</label>
                        <div class="channels-grid">
                            <div class="channel-checkbox">
                                <input type="checkbox" id="channelWeb" value="web" checked>
                                <label for="channelWeb">
                                    <i class="fas fa-globe"></i>
                                    <span>Web</span>
                                </label>
                            </div>
                            <div class="channel-checkbox">
                                <input type="checkbox" id="channelMail" value="mail">
                                <label for="channelMail">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email</span>
                                </label>
                            </div>
                            <div class="channel-checkbox">
                                <input type="checkbox" id="channelSms" value="sms">
                                <label for="channelSms">
                                    <i class="fas fa-sms"></i>
                                    <span>SMS</span>
                                </label>
                            </div>
                            <div class="channel-checkbox">
                                <input type="checkbox" id="channelFirebase" value="firebase">
                                <label for="channelFirebase">
                                    <i class="fas fa-mobile-alt"></i>
                                    <span>Push</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Appliquer à tous les devices</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notificationAlways" checked>
                                <label class="form-check-label" for="notificationAlways">
                                    Oui, tous les devices
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="notificationFormError" class="alert alert-danger d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-primary" id="btnSaveNotification">
                    <i class="fas fa-save me-1"></i>
                    <span id="btnSaveNotificationText">Enregistrer</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Events management loaded');

    let allNotifications = [];
    let notificationTypes = [];
    let allDevices = [];
    let allCalendars = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let editMode = false;

    // Initialisation
    init();

    async function init() {
        await Promise.all([
            loadNotificationTypes(),
            loadNotifications(),
            loadDevices(),
            loadCalendars()
        ]);
        renderTypesFilter();
        renderDeviceFilter();
        setDefaultDates();
    }

    // Charger les types de notifications
    async function loadNotificationTypes() {
        try {
            const response = await fetch('/api/traccar/notifications/types');
            const data = await response.json();
            console.log('Raw API response:', JSON.stringify(data));
            
            if (data.success && data.types) {
                let types = data.types;
                console.log('Types array:', types, 'is array:', Array.isArray(types));
                
                // Normaliser: extraire la valeur string de chaque type
                notificationTypes = types.map(t => {
                    console.log('Processing type:', t, 'typeof:', typeof t);
                    if (typeof t === 'string') return t;
                    if (typeof t === 'object' && t !== null) {
                        // Essayer différentes propriétés
                        return t.type || t.name || t.id || JSON.stringify(t);
                    }
                    return String(t);
                }).filter(t => t && t !== 'null' && t !== 'undefined');
                
                console.log('Normalized types:', notificationTypes);
            } else {
                // Utiliser des types par défaut si l'API ne répond pas
                notificationTypes = getDefaultTypes();
                console.log('Using default types');
            }
            renderTypesList();
            renderTypeSelect();
        } catch (error) {
            console.error('Erreur chargement types:', error);
            // Utiliser des types par défaut en cas d'erreur
            notificationTypes = getDefaultTypes();
            renderTypesList();
            renderTypeSelect();
        }
    }

    // Types par défaut
    function getDefaultTypes() {
        return [
            'deviceOnline', 'deviceOffline', 'deviceMoving', 'deviceStopped',
            'deviceOverspeed', 'deviceFuelDrop', 'deviceFuelIncrease',
            'geofenceEnter', 'geofenceExit', 'alarm', 'ignitionOn', 'ignitionOff',
            'maintenance', 'textMessage', 'driverChanged', 'commandResult',
            'deviceUnknown', 'deviceInactive', 'queuedCommandSent', 'media'
        ];
    }

    // Charger les notifications
    async function loadNotifications() {
        try {
            const response = await fetch('/api/traccar/notifications');
            const data = await response.json();
            if (data.success) {
                allNotifications = data.notifications || [];
                renderTable();
                updateStats();
            }
        } catch (error) {
            console.error('Erreur chargement notifications:', error);
        }
    }

    // Charger les devices
    async function loadDevices() {
        try {
            const response = await fetch('/api/traccar/devices');
            const data = await response.json();
            if (data.success) {
                allDevices = data.devices || [];
            }
        } catch (error) {
            console.error('Erreur chargement devices:', error);
        }
    }

    // Charger les calendriers
    async function loadCalendars() {
        try {
            const response = await fetch('/api/traccar/calendars');
            const data = await response.json();
            if (data.success) {
                allCalendars = data.calendars || [];
                renderCalendarSelect();
            }
        } catch (error) {
            console.error('Erreur chargement calendriers:', error);
        }
    }

    // Afficher la liste des types dans le sidebar
    function renderTypesList() {
        const container = document.getElementById('notificationTypesList');
        const search = document.getElementById('notificationSearch').value.toLowerCase();
        
        const filteredTypes = notificationTypes.filter(type => 
            !search || getTypeLabel(type).toLowerCase().includes(search)
        );
        
        // Compter les notifications par type
        const typeCounts = {};
        allNotifications.forEach(n => {
            typeCounts[n.type] = (typeCounts[n.type] || 0) + 1;
        });

        container.innerHTML = filteredTypes.map(type => `
            <div class="type-item" data-type="${type}">
                <i class="${getTypeIcon(type)}"></i>
                <span class="type-name">${getTypeLabel(type)}</span>
                <span class="type-count">${typeCounts[type] || 0}</span>
            </div>
        `).join('');

        // Ajouter les listeners
        container.querySelectorAll('.type-item').forEach(item => {
            item.addEventListener('click', () => {
                document.getElementById('filterType').value = item.dataset.type;
                renderTable();
            });
        });
    }

    // Obtenir le libellé d'un type
    function getTypeLabel(type) {
        // S'assurer que type est une string
        const typeStr = typeof type === 'object' ? (type.type || String(type)) : String(type);
        
        const labels = {
            'deviceOnline': 'Device en ligne',
            'deviceOffline': 'Device hors ligne',
            'deviceMoving': 'Device en mouvement',
            'deviceStopped': 'Device arrêté',
            'deviceOverspeed': 'Excès de vitesse',
            'deviceFuelDrop': 'Chute carburant',
            'deviceFuelIncrease': 'Ajout carburant',
            'geofenceEnter': 'Entrée geofence',
            'geofenceExit': 'Sortie geofence',
            'alarm': 'Alarme',
            'ignitionOn': 'Contact ON',
            'ignitionOff': 'Contact OFF',
            'maintenance': 'Maintenance',
            'textMessage': 'Message texte',
            'driverChanged': 'Changement conducteur',
            'commandResult': 'Résultat commande',
            'deviceUnknown': 'Device inconnu',
            'deviceInactive': 'Device inactif',
            'queuedCommandSent': 'Commande envoyée',
            'media': 'Média'
        };
        return labels[typeStr] || typeStr;
    }

    // Obtenir l'icône d'un type
    function getTypeIcon(type) {
        // S'assurer que type est une string
        const typeStr = typeof type === 'object' ? (type.type || String(type)) : String(type);
        
        const icons = {
            'deviceOnline': 'fas fa-wifi text-success',
            'deviceOffline': 'fas fa-wifi-slash text-danger',
            'deviceMoving': 'fas fa-car text-primary',
            'deviceStopped': 'fas fa-parking text-warning',
            'deviceOverspeed': 'fas fa-tachometer-alt text-danger',
            'deviceFuelDrop': 'fas fa-gas-pump text-danger',
            'deviceFuelIncrease': 'fas fa-gas-pump text-success',
            'geofenceEnter': 'fas fa-sign-in-alt text-info',
            'geofenceExit': 'fas fa-sign-out-alt text-warning',
            'alarm': 'fas fa-exclamation-triangle text-danger',
            'ignitionOn': 'fas fa-key text-success',
            'ignitionOff': 'fas fa-key text-secondary',
            'maintenance': 'fas fa-wrench text-warning',
            'textMessage': 'fas fa-comment text-info',
            'driverChanged': 'fas fa-user text-primary',
            'commandResult': 'fas fa-terminal text-info',
            'deviceUnknown': 'fas fa-question-circle text-muted',
            'deviceInactive': 'fas fa-moon text-secondary',
            'queuedCommandSent': 'fas fa-paper-plane text-success',
            'media': 'fas fa-photo-video text-info'
        };
        return icons[typeStr] || 'fas fa-bell text-primary';
    }

    // Afficher le select des types
    function renderTypeSelect() {
        const select = document.getElementById('notificationType');
        select.innerHTML = '<option value="">Sélectionner un type</option>' +
            notificationTypes.map(type => 
                `<option value="${type}">${getTypeLabel(type)}</option>`
            ).join('');
    }

    // Afficher le select des calendriers
    function renderCalendarSelect() {
        const select = document.getElementById('notificationCalendar');
        select.innerHTML = '<option value="">Toujours actif</option>' +
            allCalendars.map(cal => 
                `<option value="${cal.id}">${cal.name}</option>`
            ).join('');
    }

    // Afficher le filtre des types
    function renderTypesFilter() {
        const select = document.getElementById('filterType');
        select.innerHTML = '<option value="">Tous les types</option>' +
            notificationTypes.map(type => 
                `<option value="${type}">${getTypeLabel(type)}</option>`
            ).join('');
    }

    // Afficher le filtre des devices
    function renderDeviceFilter() {
        const select = document.getElementById('eventDeviceFilter');
        select.innerHTML = '<option value="">Tous les devices</option>' +
            allDevices.map(d => 
                `<option value="${d.id}">${d.name}</option>`
            ).join('');
    }

    // Définir les dates par défaut
    function setDefaultDates() {
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        document.getElementById('eventDateFrom').value = yesterday.toISOString().split('T')[0];
        document.getElementById('eventDateTo').value = today.toISOString().split('T')[0];
    }

    // Mettre à jour les stats
    function updateStats() {
        document.getElementById('totalNotifications').textContent = allNotifications.length;
        document.getElementById('activeNotifications').textContent = 
            allNotifications.filter(n => n.always || n.attributes?.enabled !== false).length;
        document.getElementById('alarmNotifications').textContent = 
            allNotifications.filter(n => n.type === 'alarm').length;
        document.getElementById('geofenceNotifications').textContent = 
            allNotifications.filter(n => n.type?.includes('geofence')).length;
    }

    // Afficher la table
    function renderTable() {
        const tbody = document.getElementById('notificationsTableBody');
        const filterType = document.getElementById('filterType').value;
        const filterStatus = document.getElementById('filterStatus').value;
        const filterChannel = document.getElementById('filterChannel').value;
        
        let filtered = allNotifications;
        
        if (filterType) {
            filtered = filtered.filter(n => n.type === filterType);
        }
        if (filterChannel) {
            filtered = filtered.filter(n => n.notificators?.includes(filterChannel));
        }
        
        const start = (currentPage - 1) * itemsPerPage;
        const paginatedItems = filtered.slice(start, start + itemsPerPage);
        
        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune notification configurée</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedItems.map(notification => `
                <tr data-id="${notification.id}">
                    <td>
                        <div class="type-badge">
                            <i class="${getTypeIcon(notification.type)}"></i>
                            <span>${getTypeLabel(notification.type)}</span>
                        </div>
                    </td>
                    <td>
                        ${notification.always ? '<span class="badge bg-info">Tous les devices</span>' : '<span class="badge bg-secondary">Devices spécifiques</span>'}
                    </td>
                    <td>
                        ${renderChannels(notification.notificators)}
                    </td>
                    <td>
                        ${notification.calendarId ? getCalendarName(notification.calendarId) : '<span class="text-muted">Toujours</span>'}
                    </td>
                    <td>
                        <span class="status-badge status-online">Actif</span>
                    </td>
                    <td class="actions-cell">
                        <button class="btn-icon btn-edit" title="Modifier" onclick="editNotification(${notification.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-delete" title="Supprimer" onclick="deleteNotification(${notification.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        document.getElementById('tableInfo').textContent = 
            `Affichage de ${start + 1} à ${Math.min(start + itemsPerPage, filtered.length)} sur ${filtered.length} entrées`;
    }

    // Afficher les canaux
    function renderChannels(notificators) {
        if (!notificators) return '<span class="text-muted">Aucun</span>';
        
        const channels = notificators.split(',').map(c => c.trim());
        const icons = {
            'web': '<i class="fas fa-globe text-primary" title="Web"></i>',
            'mail': '<i class="fas fa-envelope text-danger" title="Email"></i>',
            'sms': '<i class="fas fa-sms text-success" title="SMS"></i>',
            'firebase': '<i class="fas fa-mobile-alt text-warning" title="Push"></i>'
        };
        
        return channels.map(c => icons[c] || c).join(' ');
    }

    // Obtenir le nom du calendrier
    function getCalendarName(calendarId) {
        const calendar = allCalendars.find(c => c.id === calendarId);
        return calendar ? calendar.name : '-';
    }

    // Charger les événements
    document.getElementById('btnLoadEvents').addEventListener('click', async function() {
        const deviceId = document.getElementById('eventDeviceFilter').value;
        const dateFrom = document.getElementById('eventDateFrom').value;
        const dateTo = document.getElementById('eventDateTo').value;
        
        if (!dateFrom || !dateTo) {
            alert('Veuillez sélectionner une période');
            return;
        }

        const container = document.getElementById('eventsList');
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Chargement des événements...</p>
            </div>
        `;

        try {
            let url = `/api/traccar/reports/events?from=${dateFrom}T00:00:00Z&to=${dateTo}T23:59:59Z`;
            if (deviceId) {
                url += `&deviceId=${deviceId}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success && data.events) {
                renderEvents(data.events);
            } else {
                container.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Aucun événement trouvé pour cette période</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Erreur:', error);
            container.innerHTML = `
                <div class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <p>Erreur lors du chargement des événements</p>
                </div>
            `;
        }
    });

    // Afficher les événements
    function renderEvents(events) {
        const container = document.getElementById('eventsList');
        
        if (events.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <p>Aucun événement pour cette période</p>
                </div>
            `;
            return;
        }

        // Trier par date décroissante
        events.sort((a, b) => new Date(b.eventTime) - new Date(a.eventTime));

        container.innerHTML = events.slice(0, 50).map(event => {
            const device = allDevices.find(d => d.id === event.deviceId);
            const eventDate = new Date(event.eventTime);
            
            return `
                <div class="event-item">
                    <div class="event-icon">
                        <i class="${getTypeIcon(event.type)}"></i>
                    </div>
                    <div class="event-content">
                        <div class="event-header">
                            <span class="event-type">${getTypeLabel(event.type)}</span>
                            <span class="event-device">${device?.name || 'Device #' + event.deviceId}</span>
                        </div>
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> ${eventDate.toLocaleString('fr-FR')}</span>
                            ${event.geofenceId ? '<span><i class="fas fa-draw-polygon"></i> Geofence</span>' : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        if (events.length > 50) {
            container.innerHTML += `
                <div class="text-center py-3 text-muted">
                    <small>Affichage des 50 premiers événements sur ${events.length}</small>
                </div>
            `;
        }
    }

    // Ouvrir le modal d'ajout
    document.getElementById('btnAddNotification').addEventListener('click', function() {
        editMode = false;
        document.getElementById('notificationModalLabel').innerHTML = '<i class="fas fa-bell me-2"></i>Nouvelle notification';
        document.getElementById('notificationForm').reset();
        document.getElementById('notificationId').value = '';
        document.getElementById('channelWeb').checked = true;
        document.getElementById('notificationAlways').checked = true;
        new bootstrap.Modal(document.getElementById('notificationModal')).show();
    });

    // Sauvegarder
    document.getElementById('btnSaveNotification').addEventListener('click', async function() {
        const btn = this;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';
        btn.disabled = true;

        try {
            const id = document.getElementById('notificationId').value;
            const type = document.getElementById('notificationType').value;
            const calendarId = document.getElementById('notificationCalendar').value;
            const always = document.getElementById('notificationAlways').checked;
            
            // Récupérer les canaux sélectionnés
            const channels = [];
            if (document.getElementById('channelWeb').checked) channels.push('web');
            if (document.getElementById('channelMail').checked) channels.push('mail');
            if (document.getElementById('channelSms').checked) channels.push('sms');
            if (document.getElementById('channelFirebase').checked) channels.push('firebase');

            if (!type) {
                alert('Veuillez sélectionner un type de notification');
                return;
            }

            const payload = {
                type: type,
                always: always,
                notificators: channels.join(',')
            };
            
            if (calendarId) {
                payload.calendarId = parseInt(calendarId);
            }
            
            if (id) {
                payload.id = parseInt(id);
            }

            const method = id ? 'PUT' : 'POST';
            const url = id ? `/api/traccar/notifications/${id}` : '/api/traccar/notifications';

            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('notificationModal')).hide();
                await loadNotifications();
                renderTypesList();
                alert(id ? 'Notification modifiée !' : 'Notification créée !');
            } else {
                alert('Erreur: ' + (data.message || 'Erreur inconnue'));
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'enregistrement');
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    });

    // Modifier une notification
    window.editNotification = function(id) {
        const notification = allNotifications.find(n => n.id === id);
        if (!notification) return;

        editMode = true;
        document.getElementById('notificationModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Modifier la notification';
        document.getElementById('notificationId').value = notification.id;
        document.getElementById('notificationType').value = notification.type;
        document.getElementById('notificationCalendar').value = notification.calendarId || '';
        document.getElementById('notificationAlways').checked = notification.always;

        // Canaux
        const channels = notification.notificators?.split(',') || [];
        document.getElementById('channelWeb').checked = channels.includes('web');
        document.getElementById('channelMail').checked = channels.includes('mail');
        document.getElementById('channelSms').checked = channels.includes('sms');
        document.getElementById('channelFirebase').checked = channels.includes('firebase');

        new bootstrap.Modal(document.getElementById('notificationModal')).show();
    };

    // Supprimer une notification
    window.deleteNotification = async function(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) return;

        try {
            const response = await fetch(`/api/traccar/notifications/${id}`, { method: 'DELETE' });
            const data = await response.json();

            if (data.success) {
                await loadNotifications();
                renderTypesList();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        }
    };

    // Filtres
    document.getElementById('filterType').addEventListener('change', renderTable);
    document.getElementById('filterStatus').addEventListener('change', renderTable);
    document.getElementById('filterChannel').addEventListener('change', renderTable);
    document.getElementById('notificationSearch').addEventListener('input', renderTypesList);

    // Rafraîchir
    document.getElementById('btnRefresh').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i>';
        await loadNotifications();
        renderTypesList();
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Rafraîchir';
    });
});
</script>
@endpush

@push('styles')
<style>
/* Events Page Styles */
.events-sidebar {
    width: 280px;
    min-width: 280px;
}

.sidebar-header {
    padding: 15px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.sidebar-header h4 {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
}

.notification-types-list {
    padding: 10px;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.type-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 4px;
}

.type-item:hover {
    background: #f0f0f0;
}

.type-item i {
    width: 20px;
    text-align: center;
}

.type-name {
    flex: 1;
    font-size: 0.9rem;
}

.type-count {
    background: #7556D6;
    color: white;
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 12px;
    min-width: 24px;
    text-align: center;
}

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
}

/* Type Badge */
.type-badge {
    display: flex;
    align-items: center;
    gap: 8px;
}

.type-badge i {
    font-size: 1rem;
}

.type-badge span {
    font-weight: 500;
}

/* Channels Grid */
.channels-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.channel-checkbox {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.channel-checkbox:has(input:checked) {
    border-color: #7556D6;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
}

.channel-checkbox input {
    display: none;
}

.channel-checkbox label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    margin: 0;
}

.channel-checkbox i {
    font-size: 1.5rem;
    color: #7556D6;
}

.channel-checkbox span {
    font-size: 0.85rem;
    font-weight: 500;
}

/* Events List */
.events-list {
    max-height: 400px;
    overflow-y: auto;
}

.event-item {
    display: flex;
    gap: 15px;
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
}

.event-item:hover {
    background: #fafafa;
}

.event-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.event-content {
    flex: 1;
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.event-type {
    font-weight: 600;
    color: #333;
}

.event-device {
    font-size: 0.85rem;
    color: #7556D6;
    font-weight: 500;
}

.event-meta {
    display: flex;
    gap: 15px;
    font-size: 0.8rem;
    color: #666;
}

.event-meta i {
    margin-right: 5px;
}

/* Header Actions */
.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.mt-4 {
    margin-top: 1.5rem;
}
</style>
@endpush

@endsection
