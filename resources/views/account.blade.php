@extends('layouts.app')

@section('title', 'Utilisateurs - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar device-sidebar" id="accountSidebar">
        <div class="sidebar-search">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="treeSearch" class="search-input" placeholder="Rechercher...">
            </div>
        </div>
        
        <div class="tree-view" id="userTree">
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
                <h3>
                    <i class="fas fa-users me-2"></i>
                    Gestion des Utilisateurs
                </h3>
                <div class="realtime-indicator" id="realtimeIndicator">
                    <span class="realtime-dot"></span>
                    <span class="realtime-text">Temps réel</span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card stat-total">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalUsers">0</span>
                        <span class="stat-label">Total Utilisateurs</span>
                    </div>
                </div>
                <div class="stat-card stat-active">
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="activeUsers">0</span>
                        <span class="stat-label">Actifs</span>
                    </div>
                </div>
                <div class="stat-card stat-disabled">
                    <div class="stat-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="disabledUsers">0</span>
                        <span class="stat-label">Désactivés</span>
                    </div>
                </div>
                <div class="stat-card stat-admin">
                    <div class="stat-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="adminUsers">0</span>
                        <span class="stat-label">Administrateurs</span>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>Recherche</label>
                        <input type="text" id="searchUser" class="filter-input" placeholder="Nom, Email...">
                    </div>
                    <div class="filter-group">
                        <label>Statut</label>
                        <select id="filterStatus" class="filter-select">
                            <option value="">Tous</option>
                            <option value="active">Actif</option>
                            <option value="disabled">Désactivé</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Rôle</label>
                        <select id="filterRole" class="filter-select">
                            <option value="">Tous</option>
                            <option value="admin">Administrateur</option>
                            <option value="user">Utilisateur</option>
                            <option value="readonly">Lecture seule</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" id="btnAddUser" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus"></i>
                    Ajouter
                </button>
                <button class="btn btn-success" id="btnRefresh">
                    <i class="fas fa-sync-alt"></i>
                    Rafraîchir
                </button>
                <button class="btn btn-warning" id="btnExport">
                    <i class="fas fa-download"></i>
                    Exporter
                </button>
                <button class="btn btn-danger" id="btnDeleteSelected">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
            </div>

            <!-- Modal Ajouter Utilisateur -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">
                                <i class="fas fa-user-plus me-2"></i>
                                Ajouter un Utilisateur
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm">
                                <input type="hidden" id="addUserId" name="id" value="0">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="addUserName" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addUserName" name="name" placeholder="Nom complet">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addUserEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="addUserEmail" name="email" placeholder="email@exemple.com">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="addUserPassword" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="addUserPassword" name="password" placeholder="••••••••">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addUserPhone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="addUserPhone" name="phone" placeholder="+33 6 00 00 00 00">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="addUserRole" class="form-label">Rôle</label>
                                        <select class="form-select" id="addUserRole" name="administrator">
                                            <option value="false">Utilisateur</option>
                                            <option value="true">Administrateur</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addUserExpiration" class="form-label">Date d'expiration</label>
                                        <input type="datetime-local" class="form-control" id="addUserExpiration" name="expirationTime">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="addUserDeviceLimit" class="form-label">Limite de devices</label>
                                        <input type="number" class="form-control" id="addUserDeviceLimit" name="deviceLimit" value="-1" min="-1">
                                        <small class="text-muted">-1 = illimité</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addUserUserLimit" class="form-label">Limite d'utilisateurs</label>
                                        <input type="number" class="form-control" id="addUserUserLimit" name="userLimit" value="0" min="-1">
                                        <small class="text-muted">-1 = illimité, 0 = aucun</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="addUserDisabled" name="disabled">
                                            <label class="form-check-label" for="addUserDisabled">Désactivé</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="addUserReadonly" name="readonly">
                                            <label class="form-check-label" for="addUserReadonly">Lecture seule</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="addUserDeviceReadonly" name="deviceReadonly">
                                            <label class="form-check-label" for="addUserDeviceReadonly">Devices en lecture seule</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="addUserFormError" class="alert alert-danger d-none" role="alert"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="btnAddSaveUser">
                                <i class="fas fa-save me-1"></i>
                                <span id="btnAddSaveUserText">Enregistrer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Lier Utilisateur -->
            <div class="modal fade" id="linkUserModal" tabindex="-1" aria-labelledby="linkUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="linkUserModalLabel">
                                <i class="fas fa-link me-2"></i>
                                Lier des Éléments à l'Utilisateur
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="linkUserId" value="0">
                            
                            <!-- Devices -->
                            <div class="link-section">
                                <label for="linkDevices" class="form-label">Devices</label>
                                <select id="linkDevices" class="form-select" onchange="addLinkedItem('device', this)">
                                    <option value="">Sélectionner un device...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedDevices"></div>
                            </div>

                            <!-- Groups -->
                            <div class="link-section mt-3">
                                <label for="linkGroups" class="form-label">Groupes</label>
                                <select id="linkGroups" class="form-select" onchange="addLinkedItem('group', this)">
                                    <option value="">Sélectionner un groupe...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedGroups"></div>
                            </div>

                            <!-- Geofences -->
                            <div class="link-section mt-3">
                                <label for="linkGeofences" class="form-label">Géofences</label>
                                <select id="linkGeofences" class="form-select" onchange="addLinkedItem('geofence', this)">
                                    <option value="">Sélectionner une géofence...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedGeofences"></div>
                            </div>

                            <!-- Notifications -->
                            <div class="link-section mt-3">
                                <label for="linkNotifications" class="form-label">Notifications</label>
                                <select id="linkNotifications" class="form-select" onchange="addLinkedItem('notification', this)">
                                    <option value="">Sélectionner une notification...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedNotifications"></div>
                            </div>

                            <!-- Calendars -->
                            <div class="link-section mt-3">
                                <label for="linkCalendars" class="form-label">Calendriers</label>
                                <select id="linkCalendars" class="form-select" onchange="addLinkedItem('calendar', this)">
                                    <option value="">Sélectionner un calendrier...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedCalendars"></div>
                            </div>

                            <!-- Attributes -->
                            <div class="link-section mt-3">
                                <label for="linkAttributes" class="form-label">Attributs Calculés</label>
                                <select id="linkAttributes" class="form-select" onchange="addLinkedItem('attribute', this)">
                                    <option value="">Sélectionner un attribut...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedAttributes"></div>
                            </div>

                            <!-- Drivers -->
                            <div class="link-section mt-3">
                                <label for="linkDrivers" class="form-label">Chauffeurs</label>
                                <select id="linkDrivers" class="form-select" onchange="addLinkedItem('driver', this)">
                                    <option value="">Sélectionner un chauffeur...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedDrivers"></div>
                            </div>

                            <!-- Managed Users -->
                            <div class="link-section mt-3">
                                <label for="linkManagedUsers" class="form-label">Utilisateurs Gérés</label>
                                <select id="linkManagedUsers" class="form-select" onchange="addLinkedItem('managedUser', this)">
                                    <option value="">Sélectionner un utilisateur...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedManagedUsers"></div>
                            </div>

                            <!-- Commands -->
                            <div class="link-section mt-3">
                                <label for="linkCommands" class="form-label">Commandes Sauvegardées</label>
                                <select id="linkCommands" class="form-select" onchange="addLinkedItem('command', this)">
                                    <option value="">Sélectionner une commande...</option>
                                </select>
                                <div class="linked-items-container mt-2" id="linkedCommands"></div>
                            </div>

                            <div id="linkUserFormError" class="alert alert-danger d-none mt-3" role="alert"></div>
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

            <!-- Modal Modifier Utilisateur -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">
                                <i class="fas fa-user-edit me-2"></i>
                                Modifier Utilisateur
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                <input type="hidden" id="editUserId" name="id" value="0">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editUserName" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="editUserName" name="name" placeholder="Nom complet">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editUserEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="editUserEmail" name="email" placeholder="email@exemple.com">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editUserPassword" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="••••••••">
                                        <small class="text-muted">Laissez vide pour ne pas modifier</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editUserPhone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="editUserPhone" name="phone" placeholder="+33 6 00 00 00 00">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editUserRole" class="form-label">Rôle</label>
                                        <select class="form-select" id="editUserRole" name="administrator">
                                            <option value="false">Utilisateur</option>
                                            <option value="true">Administrateur</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editUserExpiration" class="form-label">Date d'expiration</label>
                                        <input type="datetime-local" class="form-control" id="editUserExpiration" name="expirationTime">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editUserDeviceLimit" class="form-label">Limite de devices</label>
                                        <input type="number" class="form-control" id="editUserDeviceLimit" name="deviceLimit" value="-1" min="-1">
                                        <small class="text-muted">-1 = illimité</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editUserUserLimit" class="form-label">Limite d'utilisateurs</label>
                                        <input type="number" class="form-control" id="editUserUserLimit" name="userLimit" value="0" min="-1">
                                        <small class="text-muted">-1 = illimité, 0 = aucun</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="editUserDisabled" name="disabled">
                                            <label class="form-check-label" for="editUserDisabled">Désactivé</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="editUserReadonly" name="readonly">
                                            <label class="form-check-label" for="editUserReadonly">Lecture seule</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="editUserDeviceReadonly" name="deviceReadonly">
                                            <label class="form-check-label" for="editUserDeviceReadonly">Devices en lecture seule</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="editUserFormError" class="alert alert-danger d-none" role="alert"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="btnEditSaveUser">
                                <i class="fas fa-save me-1"></i>
                                <span id="btnEditSaveUserText">Modifier</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-container">
                <table class="data-table" id="usersTable">
                    <thead>
                        <tr>
                            <th class="th-checkbox">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Téléphone</th>
                            <th>Expiration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="8" class="loading-cell">
                                <div class="table-loading">
                                    <div class="spinner"></div>
                                    <span>Chargement des utilisateurs...</span>
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
    console.log('User management script loaded');
    let allUsers = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let refreshInterval = null;
    const REFRESH_RATE = 10000; // Rafraîchissement toutes les 10 secondes

    // Charger les utilisateurs au démarrage
    loadUsers();
    
    // Démarrer le rafraîchissement automatique
    startRealTimeUpdates();

    // Event listeners
    document.getElementById('btnRefresh').addEventListener('click', loadUsers);
    document.getElementById('searchUser').addEventListener('input', debounce(filterUsers, 300));
    document.getElementById('filterStatus').addEventListener('change', filterUsers);
    document.getElementById('filterRole').addEventListener('change', filterUsers);
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
    document.getElementById('treeSearch').addEventListener('input', debounce(filterTree, 300));

    // Boutons Enregistrer - Modal Ajouter
    const btnAddSaveUser = document.getElementById('btnAddSaveUser');
    if (btnAddSaveUser) {
        btnAddSaveUser.addEventListener('click', function(e) {
            e.preventDefault();
            saveUser('add');
        });
    }

    // Boutons Modifier - Modal Modifier
    const btnEditSaveUser = document.getElementById('btnEditSaveUser');
    if (btnEditSaveUser) {
        btnEditSaveUser.addEventListener('click', function(e) {
            e.preventDefault();
            saveUser('edit');
        });
    }

    // Fonction pour démarrer les mises à jour en temps réel
    function startRealTimeUpdates() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        refreshInterval = setInterval(async () => {
            await loadUsersSilent();
        }, REFRESH_RATE);
        
        console.log('Real-time updates started (every ' + (REFRESH_RATE/1000) + 's)');
    }

    // Charger les utilisateurs silencieusement
    async function loadUsersSilent() {
        try {
            const response = await fetch('/api/traccar/users');
            const data = await response.json();
            
            if (data.success) {
                const newUsers = data.users || [];
                
                if (JSON.stringify(allUsers) !== JSON.stringify(newUsers)) {
                    allUsers = newUsers;
                    filterUsers();
                    buildTreeView();
                    updateStats();
                    updateLastRefreshTime();
                }
            }
        } catch (error) {
            console.error('Erreur rafraîchissement silencieux:', error);
        }
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
            }
        } else {
            loadUsersSilent();
            startRealTimeUpdates();
        }
    });

    // Charger les utilisateurs depuis l'API
    async function loadUsers() {
        try {
            showTableLoading();
            const response = await fetch('/api/traccar/users');
            const data = await response.json();
            console.log('Users response:', data);
            
            if (data.success) {
                allUsers = data.users || [];
                filterUsers();
                buildTreeView();
                updateStats();
            } else {
                showTableError('Erreur lors du chargement des utilisateurs');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showTableError('Erreur de connexion au serveur');
        }
    }

    // Mettre à jour les statistiques
    function updateStats() {
        document.getElementById('totalUsers').textContent = allUsers.length;
        document.getElementById('activeUsers').textContent = allUsers.filter(u => !u.disabled).length;
        document.getElementById('disabledUsers').textContent = allUsers.filter(u => u.disabled).length;
        document.getElementById('adminUsers').textContent = allUsers.filter(u => u.administrator).length;
    }

    // Sauvegarder un utilisateur
    async function saveUser(mode) {
        // Déterminer le formulaire et les éléments à utiliser selon le mode
        const formId = mode === 'edit' ? 'editUserForm' : 'addUserForm';
        const errorDivId = mode === 'edit' ? 'editUserFormError' : 'addUserFormError';
        const btnTextId = mode === 'edit' ? 'btnEditSaveUserText' : 'btnAddSaveUserText';
        const btnId = mode === 'edit' ? 'btnEditSaveUser' : 'btnAddSaveUser';
        const modalId = mode === 'edit' ? 'editUserModal' : 'addUserModal';
        
        const form = document.getElementById(formId);
        const errorDiv = document.getElementById(errorDivId);
        const btnText = document.getElementById(btnTextId);
        const btn = document.getElementById(btnId);
        
        // Validation
        const nameId = mode === 'edit' ? 'editUserName' : 'addUserName';
        const emailId = mode === 'edit' ? 'editUserEmail' : 'addUserEmail';
        const passwordId = mode === 'edit' ? 'editUserPassword' : 'addUserPassword';
        const userIdId = mode === 'edit' ? 'editUserId' : 'addUserId';
        
        const name = document.getElementById(nameId).value.trim();
        const email = document.getElementById(emailId).value.trim();
        const password = document.getElementById(passwordId).value;
        const userId = document.getElementById(userIdId).value;
        
        if (!name || !email) {
            errorDiv.textContent = 'Le nom et l\'email sont obligatoires.';
            errorDiv.classList.remove('d-none');
            return;
        }
        
        if (mode === 'add' && !password) {
            errorDiv.textContent = 'Le mot de passe est obligatoire pour un nouvel utilisateur.';
            errorDiv.classList.remove('d-none');
            return;
        }
        
        errorDiv.classList.add('d-none');
        
        // Déterminer les IDs des champs pour ce mode
        const phoneId = mode === 'edit' ? 'editUserPhone' : 'addUserPhone';
        const roleId = mode === 'edit' ? 'editUserRole' : 'addUserRole';
        const disabledId = mode === 'edit' ? 'editUserDisabled' : 'addUserDisabled';
        const readonlyId = mode === 'edit' ? 'editUserReadonly' : 'addUserReadonly';
        const deviceReadonlyId = mode === 'edit' ? 'editUserDeviceReadonly' : 'addUserDeviceReadonly';
        const deviceLimitId = mode === 'edit' ? 'editUserDeviceLimit' : 'addUserDeviceLimit';
        const userLimitId = mode === 'edit' ? 'editUserUserLimit' : 'addUserUserLimit';
        const expirationId = mode === 'edit' ? 'editUserExpiration' : 'addUserExpiration';
        
        // Préparer les données
        const userData = {
            id: parseInt(userId),
            name: name,
            email: email,
            phone: document.getElementById(phoneId).value.trim() || null,
            administrator: document.getElementById(roleId).value === 'true',
            disabled: document.getElementById(disabledId).checked,
            readonly: document.getElementById(readonlyId).checked,
            deviceReadonly: document.getElementById(deviceReadonlyId).checked,
            deviceLimit: parseInt(document.getElementById(deviceLimitId).value) || -1,
            userLimit: parseInt(document.getElementById(userLimitId).value) || 0,
            expirationTime: document.getElementById(expirationId).value || null
        };
        
        if (password) {
            userData.password = password;
        }
        
        // Afficher loading
        btn.disabled = true;
        btnText.textContent = mode === 'edit' ? 'Modification...' : 'Enregistrement...';
        
        try {
            const isEdit = mode === 'edit';
            const url = isEdit ? `/api/traccar/users/${userId}` : '/api/traccar/users';
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(userData)
            });
            
            const data = await response.json();
            
            if (data.success || response.ok) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                modal.hide();
                
                form.reset();
                document.getElementById(userIdId).value = '0';
                
                await loadUsers();
                console.log(isEdit ? 'Utilisateur modifié avec succès' : 'Utilisateur créé avec succès');
            } else {
                errorDiv.textContent = data.message || data.error || 'Erreur lors de l\'enregistrement';
                errorDiv.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Erreur:', error);
            errorDiv.textContent = 'Erreur de connexion au serveur';
            errorDiv.classList.remove('d-none');
        } finally {
            btn.disabled = false;
            btnText.textContent = mode === 'edit' ? 'Modifier' : 'Enregistrer';
        }
    }

    // Filtrer les utilisateurs
    function filterUsers() {
        const search = document.getElementById('searchUser').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const role = document.getElementById('filterRole').value;

        let filtered = allUsers.filter(user => {
            const matchSearch = !search || 
                user.name?.toLowerCase().includes(search) ||
                user.email?.toLowerCase().includes(search) ||
                user.phone?.toLowerCase().includes(search);
            
            let matchStatus = true;
            if (status === 'active') matchStatus = !user.disabled;
            if (status === 'disabled') matchStatus = user.disabled;
            
            let matchRole = true;
            if (role === 'admin') matchRole = user.administrator;
            if (role === 'user') matchRole = !user.administrator && !user.readonly;
            if (role === 'readonly') matchRole = user.readonly;

            return matchSearch && matchStatus && matchRole;
        });

        currentPage = 1;
        renderTable(filtered);
    }

    // Afficher le tableau
    function renderTable(users) {
        const tbody = document.getElementById('usersTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedUsers = users.slice(start, end);

        if (users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-cell">
                        <div class="empty-state">
                            <i class="fas fa-user fa-3x"></i>
                            <p>Aucun utilisateur trouvé</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedUsers.map(user => `
                <tr data-id="${user.id}">
                    <td><input type="checkbox" class="user-checkbox" value="${user.id}"></td>
                    <td>
                        <div class="user-name-cell">
                            <div class="user-avatar avatar-${user.disabled ? 'offline' : 'online'}">${getInitials(user.name)}</div>
                            <span>${user.name || '-'}</span>
                        </div>
                    </td>
                    <td><a href="mailto:${user.email}">${user.email || '-'}</a></td>
                    <td>
                        <span class="role-badge role-${user.administrator ? 'admin' : (user.readonly ? 'readonly' : 'user')}">
                            ${user.administrator ? '<i class="fas fa-crown"></i> Admin' : (user.readonly ? '<i class="fas fa-eye"></i> Lecture' : '<i class="fas fa-user"></i> User')}
                        </span>
                    </td>
                    <td>${user.phone || '-'}</td>
                    <td>${formatDate(user.expirationTime)}</td>
                    <td class="actions-cell">
                        <button class="btn-icon btn-edit" title="Modifier" onclick="editUser(${user.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-link" title="link" onclick="linkUser(${user.id})">
                            <i class="fas fa-link"></i>
                        </button>
                        <button class="btn-icon btn-locate" title="Voir les devices" onclick="viewUserDevices(${user.id})">
                            <i class="fas fa-mobile-alt"></i>
                        </button>
                        <button class="btn-icon btn-delete" title="Supprimer" onclick="deleteUser(${user.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        updateTableInfo(users.length, start, Math.min(end, users.length));
        renderPagination(users.length);
    }

    // Construire le tree view
    function buildTreeView() {
        const treeContainer = document.getElementById('userTree');
        
        // Grouper par rôle
        const admins = allUsers.filter(u => u.administrator);
        const users = allUsers.filter(u => !u.administrator && !u.readonly);
        const readonly = allUsers.filter(u => u.readonly);

        const groups = [
            { name: 'Administrateurs', icon: 'fa-crown', users: admins },
            { name: 'Utilisateurs', icon: 'fa-user', users: users },
            { name: 'Lecture seule', icon: 'fa-eye', users: readonly }
        ];

        let html = '';
        groups.forEach(group => {
            if (group.users.length > 0) {
                html += `
                    <div class="tree-node expanded">
                        <div class="tree-parent" onclick="toggleTreeNode(this)">
                            <i class="fas fa-chevron-right tree-arrow"></i>
                            <i class="fas ${group.icon} tree-folder-icon"></i>
                            <span class="tree-label">${group.name}</span>
                            <span class="tree-count">${group.users.length}</span>
                        </div>
                        <div class="tree-children">
                            ${group.users.map(u => `
                                <div class="tree-child" data-id="${u.id}" onclick="selectUser(${u.id})">
                                    <span class="tree-status status-${u.disabled ? 'offline' : 'online'}"></span>
                                    <span class="tree-device-name">${u.name}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }
        });

        treeContainer.innerHTML = html || '<div class="tree-empty">Aucun utilisateur</div>';
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

        treeNodes.forEach(node => {
            const hasVisibleChildren = Array.from(node.querySelectorAll('.tree-child')).some(c => c.style.display !== 'none');
            node.style.display = hasVisibleChildren || !search ? 'block' : 'none';
        });
    }

    // Helpers
    function getInitials(name) {
        if (!name) return '?';
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR', { 
            day: '2-digit', month: '2-digit', year: 'numeric'
        });
    }

    function showTableLoading() {
        document.getElementById('usersTableBody').innerHTML = `
            <tr>
                <td colspan="8" class="loading-cell">
                    <div class="table-loading">
                        <div class="spinner"></div>
                        <span>Chargement des utilisateurs...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    function showTableError(message) {
        document.getElementById('usersTableBody').innerHTML = `
            <tr>
                <td colspan="8" class="error-cell">
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                        <p>${message}</p>
                        <button class="btn btn-primary btn-sm" onclick="loadUsers()">Réessayer</button>
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
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = checked);
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
        filterUsers();
    };

    window.toggleTreeNode = function(element) {
        element.parentElement.classList.toggle('expanded');
    };

    window.selectUser = function(id) {
        document.querySelectorAll('.tree-child').forEach(c => c.classList.remove('selected'));
        document.querySelector(`.tree-child[data-id="${id}"]`)?.classList.add('selected');
        
        document.querySelectorAll('#usersTable tbody tr').forEach(r => r.classList.remove('highlighted'));
        document.querySelector(`#usersTable tbody tr[data-id="${id}"]`)?.classList.add('highlighted');
    };

    window.editUser = function(id) {
        const user = allUsers.find(u => u.id === id);
        if (!user) {
            console.error('User not found:', id);
            return;
        }
        
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editUserName').value = user.name || '';
        document.getElementById('editUserEmail').value = user.email || '';
        document.getElementById('editUserPhone').value = user.phone || '';
        document.getElementById('editUserPassword').value = '';
        document.getElementById('editUserRole').value = user.administrator ? 'true' : 'false';
        document.getElementById('editUserDisabled').checked = user.disabled || false;
        document.getElementById('editUserReadonly').checked = user.readonly || false;
        document.getElementById('editUserDeviceReadonly').checked = user.deviceReadonly || false;
        document.getElementById('editUserDeviceLimit').value = user.deviceLimit ?? -1;
        document.getElementById('editUserUserLimit').value = user.userLimit ?? 0;
        
        if (user.expirationTime) {
            const date = new Date(user.expirationTime);
            document.getElementById('editUserExpiration').value = date.toISOString().slice(0, 16);
        } else {
            document.getElementById('editUserExpiration').value = '';
        }
        
        document.getElementById('editUserFormError').classList.add('d-none');
        
        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
    };

    window.viewUserDevices = function(id) {
        console.log('View devices for user:', id);
        window.location.href = `/device?userId=${id}`;
    };

    window.deleteUser = async function(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) return;
        
        try {
            const response = await fetch(`/api/traccar/users/${id}`, { method: 'DELETE' });
            const data = await response.json();
            
            if (data.success) {
                loadUsers();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        }
    };

    window.loadUsers = loadUsers;
});
</script>
<script>
// Variables globales pour la gestion des liaisons
let linkedItems = {
    device: [],
    group: [],
    geofence: [],
    notification: [],
    calendar: [],
    attribute: [],
    driver: [],
    managedUser: [],
    command: []
};

let allItems = {
    devices: [],
    groups: [],
    geofences: [],
    notifications: [],
    calendars: [],
    attributes: [],
    drivers: [],
    managedUsers: [],
    commands: []
};

// Initialiser le modal de liaison
async function initLinkModal() {
    try {
        const userId = parseInt(document.getElementById('linkUserId').value);
        
        // Phase 1: Charger tous les éléments disponibles
        await Promise.all([
            loadItems('devices', '/api/traccar/devices'),
            loadItems('groups', '/api/traccar/groups'),
            loadItems('geofences', '/api/traccar/geofences'),
            loadItems('notifications', '/api/traccar/notifications'),
            loadItems('calendars', '/api/traccar/calendars'),
            loadItems('attributes', '/api/traccar/attributes/computed'),
            loadItems('drivers', '/api/traccar/drivers'),
            loadItems('managedUsers', '/api/traccar/users'),
            loadItems('commands', '/api/traccar/commands')
        ]);
        
        // Phase 2: Charger les permissions existantes de l'utilisateur
        await loadExistingPermissions(userId);
        
        // Phase 3: Remplir les combobox (en excluant les éléments déjà liés)
        populateSelects();
    } catch (error) {
        console.error('Erreur lors du chargement des éléments:', error);
    }
}

// Charger les permissions existantes depuis l'API
async function loadExistingPermissions(userId) {
    try {
        const response = await fetch(`/api/traccar/permissions-test/${userId}`);
        const data = await response.json();
        
        if (data.success && data.permissions) {
            // Réinitialiser les tableaux de liaisons
            linkedItems = {
                device: [],
                group: [],
                geofence: [],
                notification: [],
                calendar: [],
                attribute: [],
                driver: [],
                managedUser: [],
                command: []
            };
            
            // Parcourir les permissions et les afficher en badges
            data.permissions.forEach(permission => {
                let type = null;
                let itemId = null;
                let itemName = '';
                
                if (permission.deviceId) {
                    type = 'device';
                    itemId = permission.deviceId;
                    const device = allItems.devices.find(d => d.id === itemId);
                    itemName = device ? device.name : `Device ${itemId}`;
                } else if (permission.groupId) {
                    type = 'group';
                    itemId = permission.groupId;
                    const group = allItems.groups.find(g => g.id === itemId);
                    itemName = group ? group.name : `Groupe ${itemId}`;
                } else if (permission.geofenceId) {
                    type = 'geofence';
                    itemId = permission.geofenceId;
                    const geofence = allItems.geofences.find(g => g.id === itemId);
                    itemName = geofence ? geofence.name : `Géofence ${itemId}`;
                } else if (permission.notificationId) {
                    type = 'notification';
                    itemId = permission.notificationId;
                    const notification = allItems.notifications.find(n => n.id === itemId);
                    itemName = notification ? (notification.type || notification.name) : `Notification ${itemId}`;
                } else if (permission.calendarId) {
                    type = 'calendar';
                    itemId = permission.calendarId;
                    const calendar = allItems.calendars.find(c => c.id === itemId);
                    itemName = calendar ? calendar.name : `Calendrier ${itemId}`;
                } else if (permission.attributeId) {
                    type = 'attribute';
                    itemId = permission.attributeId;
                    const attribute = allItems.attributes.find(a => a.id === itemId);
                    itemName = attribute ? (attribute.description || attribute.name) : `Attribut ${itemId}`;
                } else if (permission.driverId) {
                    type = 'driver';
                    itemId = permission.driverId;
                    const driver = allItems.drivers.find(d => d.id === itemId);
                    itemName = driver ? driver.name : `Chauffeur ${itemId}`;
                } else if (permission.managedUserId) {
                    type = 'managedUser';
                    itemId = permission.managedUserId;
                    const managedUser = allItems.managedUsers.find(u => u.id === itemId);
                    itemName = managedUser ? managedUser.name : `Utilisateur ${itemId}`;
                } else if (permission.commandId) {
                    type = 'command';
                    itemId = permission.commandId;
                    const command = allItems.commands.find(c => c.id === itemId);
                    itemName = command ? (command.description || command.name) : `Commande ${itemId}`;
                }
                
                if (type && itemId) {
                    linkedItems[type].push(itemId);
                    displayLinkedItem(type, itemId, itemName);
                }
            });
        }
    } catch (error) {
        console.error('Erreur lors du chargement des permissions existantes:', error);
    }
}

// Charger les éléments depuis l'API
async function loadItems(key, endpoint) {
    try {
        const response = await fetch(endpoint);
        const data = await response.json();
        
        if (data.success) {
            allItems[key] = data[key === 'devices' ? 'devices' : 
                              key === 'groups' ? 'groups' :
                              key === 'geofences' ? 'geofences' :
                              key === 'notifications' ? 'notifications' :
                              key === 'calendars' ? 'calendars' :
                              key === 'attributes' ? 'attributes' :
                              key === 'drivers' ? 'drivers' :
                              key === 'managedUsers' ? 'users' :
                              'commands'] || [];
        }
    } catch (error) {
        console.error(`Erreur chargement ${key}:`, error);
    }
}

// Remplir les combobox avec les données (en excluant les éléments liés)
function populateSelects() {
    const userId = parseInt(document.getElementById('linkUserId').value);
    
    // Devices - Exclure les devices déjà liés
    const deviceSelect = document.getElementById('linkDevices');
    deviceSelect.innerHTML = '<option value="">Sélectionner un device...</option>' +
        allItems.devices
            .filter(d => !linkedItems.device.includes(d.id))
            .map(d => `<option value="${d.id}">${d.name}</option>`)
            .join('');

    // Groups - Exclure les groupes déjà liés
    const groupSelect = document.getElementById('linkGroups');
    groupSelect.innerHTML = '<option value="">Sélectionner un groupe...</option>' +
        allItems.groups
            .filter(g => !linkedItems.group.includes(g.id))
            .map(g => `<option value="${g.id}">${g.name}</option>`)
            .join('');

    // Geofences - Exclure les géofences déjà liées
    const geofenceSelect = document.getElementById('linkGeofences');
    geofenceSelect.innerHTML = '<option value="">Sélectionner une géofence...</option>' +
        allItems.geofences
            .filter(g => !linkedItems.geofence.includes(g.id))
            .map(g => `<option value="${g.id}">${g.name}</option>`)
            .join('');

    // Notifications - Exclure les notifications déjà liées
    const notificationSelect = document.getElementById('linkNotifications');
    notificationSelect.innerHTML = '<option value="">Sélectionner une notification...</option>' +
        allItems.notifications
            .filter(n => !linkedItems.notification.includes(n.id))
            .map(n => `<option value="${n.id}">${n.type || n.name}</option>`)
            .join('');

    // Calendars - Exclure les calendriers déjà liés
    const calendarSelect = document.getElementById('linkCalendars');
    calendarSelect.innerHTML = '<option value="">Sélectionner un calendrier...</option>' +
        allItems.calendars
            .filter(c => !linkedItems.calendar.includes(c.id))
            .map(c => `<option value="${c.id}">${c.name}</option>`)
            .join('');

    // Attributes - Exclure les attributs déjà liés
    const attributeSelect = document.getElementById('linkAttributes');
    attributeSelect.innerHTML = '<option value="">Sélectionner un attribut...</option>' +
        allItems.attributes
            .filter(a => !linkedItems.attribute.includes(a.id))
            .map(a => `<option value="${a.id}">${a.description || a.name}</option>`)
            .join('');

    // Drivers - Exclure les chauffeurs déjà liés
    const driverSelect = document.getElementById('linkDrivers');
    driverSelect.innerHTML = '<option value="">Sélectionner un chauffeur...</option>' +
        allItems.drivers
            .filter(d => !linkedItems.driver.includes(d.id))
            .map(d => `<option value="${d.id}">${d.name}</option>`)
            .join('');

    // Managed Users - Exclure l'utilisateur courant et les utilisateurs déjà liés
    const managedUserSelect = document.getElementById('linkManagedUsers');
    managedUserSelect.innerHTML = '<option value="">Sélectionner un utilisateur...</option>' +
        allItems.managedUsers
            .filter(u => u.id !== userId && !linkedItems.managedUser.includes(u.id))
            .map(u => `<option value="${u.id}">${u.name}</option>`)
            .join('');

    // Commands - Exclure les commandes déjà liées
    const commandSelect = document.getElementById('linkCommands');
    commandSelect.innerHTML = '<option value="">Sélectionner une commande...</option>' +
        allItems.commands
            .filter(c => !linkedItems.command.includes(c.id))
            .map(c => `<option value="${c.id}">${c.description || c.name}</option>`)
            .join('');
}

// Ajouter un élément lié
async function addLinkedItem(type, selectElement) {
    const itemId = selectElement.value;
    if (!itemId) return;

    const userId = parseInt(document.getElementById('linkUserId').value);
    
    // Vérifier que l'élément n'est pas déjà lié
    if (linkedItems[type].includes(parseInt(itemId))) {
        alert('Cet élément est déjà lié.');
        selectElement.value = '';
        return;
    }

    try {
        // Préparer les paramètres de la requête
        const requestBody = { userId: userId };
        
        if (type === 'device') requestBody.deviceId = parseInt(itemId);
        else if (type === 'group') requestBody.groupId = parseInt(itemId);
        else if (type === 'geofence') requestBody.geofenceId = parseInt(itemId);
        else if (type === 'notification') requestBody.notificationId = parseInt(itemId);
        else if (type === 'calendar') requestBody.calendarId = parseInt(itemId);
        else if (type === 'attribute') requestBody.attributeId = parseInt(itemId);
        else if (type === 'driver') requestBody.driverId = parseInt(itemId);
        else if (type === 'managedUser') requestBody.managedUserId = parseInt(itemId);
        else if (type === 'command') requestBody.commandId = parseInt(itemId);

        // Faire la requête de liaison
        const response = await fetch('/api/traccar/permissions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(requestBody)
        });

        const data = await response.json();

        if (data.success || response.ok) {
            linkedItems[type].push(parseInt(itemId));
            
            // Obtenir le nom de l'élément
            let itemName = '';
            let itemsArray = [];
            
            if (type === 'device') itemsArray = allItems.devices;
            else if (type === 'group') itemsArray = allItems.groups;
            else if (type === 'geofence') itemsArray = allItems.geofences;
            else if (type === 'notification') itemsArray = allItems.notifications;
            else if (type === 'calendar') itemsArray = allItems.calendars;
            else if (type === 'attribute') itemsArray = allItems.attributes;
            else if (type === 'driver') itemsArray = allItems.drivers;
            else if (type === 'managedUser') itemsArray = allItems.managedUsers;
            else if (type === 'command') itemsArray = allItems.commands;

            const item = itemsArray.find(i => i.id === parseInt(itemId));
            itemName = item ? (item.name || item.description || item.type || 'Élément') : 'Élément';

            // Afficher le badge
            displayLinkedItem(type, itemId, itemName);
            
            // Réinitialiser le select
            selectElement.value = '';
            selectElement.focus();
            
            console.log(`${type} ${itemId} lié avec succès`);
        } else {
            showLinkError(data.message || 'Erreur lors de la liaison');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showLinkError('Erreur de connexion au serveur');
    }
}

// Afficher un élément lié en badge
function displayLinkedItem(type, itemId, itemName) {
    const containerId = type === 'device' ? 'linkedDevices' :
                        type === 'group' ? 'linkedGroups' :
                        type === 'geofence' ? 'linkedGeofences' :
                        type === 'notification' ? 'linkedNotifications' :
                        type === 'calendar' ? 'linkedCalendars' :
                        type === 'attribute' ? 'linkedAttributes' :
                        type === 'driver' ? 'linkedDrivers' :
                        type === 'managedUser' ? 'linkedManagedUsers' :
                        'linkedCommands';

    const container = document.getElementById(containerId);
    const badge = document.createElement('div');
    badge.className = 'linked-item-badge';
    badge.id = `badge-${type}-${itemId}`;
    
    const typeLabel = type === 'device' ? 'Device' :
                      type === 'group' ? 'Groupe' :
                      type === 'geofence' ? 'Géofence' :
                      type === 'notification' ? 'Notification' :
                      type === 'calendar' ? 'Calendrier' :
                      type === 'attribute' ? 'Attribut' :
                      type === 'driver' ? 'Chauffeur' :
                      type === 'managedUser' ? 'Utilisateur' :
                      'Commande';

    badge.innerHTML = `
        <span>${itemName}</span>
        <span class="badge-type">${typeLabel}</span>
        <button type="button" class="remove-link" onclick="removeLinkedItem('${type}', ${itemId})">×</button>
    `;
    
    container.appendChild(badge);
}

// Supprimer un élément lié
async function removeLinkedItem(type, itemId) {
    try {
        // Préparer les paramètres de la requête
        const userId = parseInt(document.getElementById('linkUserId').value);
        const requestBody = { userId: userId };
        
        if (type === 'device') requestBody.deviceId = itemId;
        else if (type === 'group') requestBody.groupId = itemId;
        else if (type === 'geofence') requestBody.geofenceId = itemId;
        else if (type === 'notification') requestBody.notificationId = itemId;
        else if (type === 'calendar') requestBody.calendarId = itemId;
        else if (type === 'attribute') requestBody.attributeId = itemId;
        else if (type === 'driver') requestBody.driverId = itemId;
        else if (type === 'managedUser') requestBody.managedUserId = itemId;
        else if (type === 'command') requestBody.commandId = itemId;

        // Faire la requête de suppression de liaison
        const response = await fetch('/api/traccar/permissions-test', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(requestBody)
        });

        const data = await response.json();

        if (data.success || response.ok) {
            // Supprimer du tableau
            linkedItems[type] = linkedItems[type].filter(id => id !== itemId);
            
            // Supprimer le badge avec animation
            const badge = document.getElementById(`badge-${type}-${itemId}`);
            if (badge) {
                badge.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => badge.remove(), 300);
            }
            
            // Mettre à jour les selects pour rendre l'élément disponible
            populateSelects();
            
            console.log(`${type} ${itemId} délié avec succès`);
        } else {
            showLinkError(data.message || 'Erreur lors de la suppression de la liaison');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showLinkError('Erreur de connexion au serveur');
    }
}

// Afficher une erreur
function showLinkError(message) {
    const errorDiv = document.getElementById('linkUserFormError');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
    setTimeout(() => {
        errorDiv.classList.add('d-none');
    }, 5000);
}

// Ouvrir le modal de liaison
window.linkUser = function(id) {
    document.getElementById('linkUserId').value = id;
    
    // Réinitialiser les conteneurs de badges
    document.getElementById('linkedDevices').innerHTML = '';
    document.getElementById('linkedGroups').innerHTML = '';
    document.getElementById('linkedGeofences').innerHTML = '';
    document.getElementById('linkedNotifications').innerHTML = '';
    document.getElementById('linkedCalendars').innerHTML = '';
    document.getElementById('linkedAttributes').innerHTML = '';
    document.getElementById('linkedDrivers').innerHTML = '';
    document.getElementById('linkedManagedUsers').innerHTML = '';
    document.getElementById('linkedCommands').innerHTML = '';
    
    // Réinitialiser les selects
    document.getElementById('linkDevices').value = '';
    document.getElementById('linkGroups').value = '';
    document.getElementById('linkGeofences').value = '';
    document.getElementById('linkNotifications').value = '';
    document.getElementById('linkCalendars').value = '';
    document.getElementById('linkAttributes').value = '';
    document.getElementById('linkDrivers').value = '';
    document.getElementById('linkManagedUsers').value = '';
    document.getElementById('linkCommands').value = '';
    
    // Réinitialiser les messages d'erreur
    document.getElementById('linkUserFormError').classList.add('d-none');
    
    // Initialiser le modal avec chargement des permissions existantes
    initLinkModal();
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('linkUserModal'));
    modal.show();
};
</script>

@push('styles')
<style>
/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
    padding: 20px
}

.stat-card {
    display: flex;
    align-items: center;
    padding: 16px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 4px solid #6c757d;
}

.stat-card.stat-total { border-left-color: #7556D6; }
.stat-card.stat-active { border-left-color: #28a745; }
.stat-card.stat-disabled { border-left-color: #dc3545; }
.stat-card.stat-admin { border-left-color: #ffc107; }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    background: #f8f9fa;
}

.stat-card.stat-total .stat-icon { background: rgba(117, 86, 214, 0.1); color: #7556D6; }
.stat-card.stat-active .stat-icon { background: rgba(40, 167, 69, 0.1); color: #28a745; }
.stat-card.stat-disabled .stat-icon { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
.stat-card.stat-admin .stat-icon { background: rgba(255, 193, 7, 0.1); color: #ffc107; }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #212529;
}

.stat-label {
    font-size: 13px;
    color: #6c757d;
}

/* User Avatar */
.user-name-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7556D6, #9b7ce8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    flex-shrink: 0;
}

.user-avatar.avatar-online {
    background: linear-gradient(135deg, #28a745, #20c997);
    box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2);
}

.user-avatar.avatar-offline {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
}

/* Role Badge */
.role-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.role-badge.role-admin {
    background: rgba(255, 193, 7, 0.15);
    color: #856404;
}

.role-badge.role-user {
    background: rgba(13, 110, 253, 0.15);
    color: #0d6efd;
}

.role-badge.role-readonly {
    background: rgba(108, 117, 125, 0.15);
    color: #6c757d;
}

/* Tree folder icon */
.tree-folder-icon {
    margin-right: 6px;
    font-size: 14px;
}

/* Link Modal Styles */
.link-section {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
}

.linked-items-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 30px;
}

.linked-item-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #7556D6, #9b7ce8);
    color: white;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    animation: slideIn 0.3s ease;
}

.linked-item-badge .badge-type {
    opacity: 0.8;
    font-size: 11px;
}

.linked-item-badge .remove-link {
    cursor: pointer;
    background: rgba(255, 255, 255, 0.3);
    border: none;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all 0.2s;
    font-size: 12px;
}

.linked-item-badge .remove-link:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: scale(1.1);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================================
   ACCOUNT PAGE - MAIN CONTAINER & SIDEBAR
   ============================================ */

/* Main Container */
.main-container {
    display: flex;
    min-height: calc(100vh - 60px);
    margin-top: 60px;
}

/* Sidebar Fixed */
.device-sidebar {
    position: fixed;
    left: 0;
    top: 50px;
    width: 280px;
    height: calc(100vh - 50px);
    background: white;
    border-right: 1px solid #e3eafc;
    display: flex;
    flex-direction: column;
    z-index: 99;
    overflow-y: auto;
}

/* Main Content adjusted for fixed sidebar */
.main-content {
    margin-left: 280px;
    flex: 1;
    width: calc(100% - 280px);
    padding: 20px;
    background: #f8f9fa;
    overflow-y: auto;
}
</style>
@endpush
@endsection
