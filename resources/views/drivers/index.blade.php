@extends('layouts.app')

@section('title', 'Conducteurs - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar device-sidebar" id="driverSidebar">
        <div class="sidebar-search">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="treeSearch" class="search-input" placeholder="Rechercher...">
            </div>
        </div>
        
        <div class="tree-view" id="driverTree">
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
                <div>
                    <h3 class="mb-1">
                        <i class="fas fa-id-card me-2" style="color: #7556D6;"></i>
                        Gestion des Conducteurs
                    </h3>
                    <p class="text-muted small mb-0">Gérez les conducteurs et leurs assignations aux véhicules</p>
                </div>
                <div class="realtime-indicator" id="realtimeIndicator">
                    <span class="realtime-dot"></span>
                    <span class="realtime-text">Temps réel</span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card stat-total">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalDrivers">0</span>
                        <span class="stat-label">Total Conducteurs</span>
                    </div>
                </div>
                <div class="stat-card stat-active">
                    <div class="stat-icon">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="assignedDrivers">0</span>
                        <span class="stat-label">Assignés</span>
                    </div>
                </div>
                <div class="stat-card stat-disabled">
                    <div class="stat-icon">
                        <i class="fas fa-unlink"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="unassignedDrivers">0</span>
                        <span class="stat-label">Non assignés</span>
                    </div>
                </div>
                <div class="stat-card stat-admin">
                    <div class="stat-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalDevices">0</span>
                        <span class="stat-label">Appareils Disponibles</span>
                    </div>
                </div>
            </div>

            <!-- Filters & Actions Section -->
            <div class="filters-and-actions-section">
                <div class="filters-section">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label><i class="fas fa-search me-1" style="color: #7556D6;"></i>Recherche</label>
                            <input type="text" id="searchDriver" class="filter-input" placeholder="Nom, Identifiant...">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-filter me-1" style="color: #7556D6;"></i>Statut</label>
                            <select id="filterStatus" class="filter-select">
                                <option value="">Tous les conducteurs</option>
                                <option value="assigned">Assignés uniquement</option>
                                <option value="unassigned">Non assignés</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-primary" id="btnAddDriver" data-bs-toggle="modal" data-bs-target="#addDriverModal">
                        <i class="fas fa-plus"></i>
                        <span>Nouveau Conducteur</span>
                    </button>
                    <button class="btn btn-info" id="btnRefresh">
                        <i class="fas fa-sync-alt"></i>
                        <span>Rafraîchir</span>
                    </button>
                    <button class="btn btn-success" id="btnExport">
                        <i class="fas fa-download"></i>
                        <span>Exporter</span>
                    </button>
                </div>
            </div>

            <!-- Modal Ajouter Conducteur -->
            <div class="modal fade" id="addDriverModal" tabindex="-1" aria-labelledby="addDriverModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addDriverModalLabel">
                                <i class="fas fa-id-card me-2"></i>
                                Ajouter un Conducteur
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addDriverForm">
                                <input type="hidden" id="addDriverId" name="id" value="0">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="addDriverName" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addDriverName" name="name" placeholder="Nom complet du conducteur">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addDriverUniqueId" class="form-label">Identifiant Unique <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addDriverUniqueId" name="uniqueId" placeholder="Ex: DRV-001, Badge RFID...">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="addDriverPhone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="addDriverPhone" name="phone" placeholder="+33 6 00 00 00 00">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addDriverLicense" class="form-label">Numéro de Permis</label>
                                        <input type="text" class="form-control" id="addDriverLicense" name="license" placeholder="Numéro de permis">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="addDriverNotes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="addDriverNotes" name="notes" rows="2" placeholder="Notes additionnelles..."></textarea>
                                </div>

                                <div class="form-hint mb-3">
                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                    L'identifiant unique sera utilisé pour l'identification automatique (badge RFID, iButton, etc.)
                                </div>

                                <div id="addDriverFormError" class="alert alert-danger d-none" role="alert"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="btnAddSaveDriver">
                                <i class="fas fa-save me-1"></i>
                                <span id="btnAddSaveDriverText">Enregistrer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Modifier Conducteur -->
            <div class="modal fade" id="editDriverModal" tabindex="-1" aria-labelledby="editDriverModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDriverModalLabel">
                                <i class="fas fa-edit me-2"></i>
                                Modifier le Conducteur
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editDriverForm">
                                <input type="hidden" id="editDriverId" name="id" value="0">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editDriverName" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="editDriverName" name="name" placeholder="Nom complet du conducteur">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editDriverUniqueId" class="form-label">Identifiant Unique <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="editDriverUniqueId" name="uniqueId" placeholder="Ex: DRV-001, Badge RFID...">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="editDriverPhone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="editDriverPhone" name="phone" placeholder="+33 6 00 00 00 00">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editDriverLicense" class="form-label">Numéro de Permis</label>
                                        <input type="text" class="form-control" id="editDriverLicense" name="license" placeholder="Numéro de permis">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="editDriverNotes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="editDriverNotes" name="notes" rows="2" placeholder="Notes additionnelles..."></textarea>
                                </div>

                                <div id="editDriverFormError" class="alert alert-danger d-none" role="alert"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="btnEditSaveDriver">
                                <i class="fas fa-save me-1"></i>
                                <span id="btnEditSaveDriverText">Modifier</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Assigner Appareils -->
            <div class="modal fade" id="assignDeviceModal" tabindex="-1" aria-labelledby="assignDeviceModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignDeviceModalLabel">
                                <i class="fas fa-link me-2"></i>
                                Assigner des Appareils
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="assignDriverId" value="0">
                            
                            <!-- Driver Info -->
                            <div class="driver-info-card mb-4">
                                <div class="driver-avatar" id="assignDriverAvatar">JD</div>
                                <div class="driver-details">
                                    <h5 id="assignDriverName">Nom du conducteur</h5>
                                    <span id="assignDriverUniqueId">ID: DRV-001</span>
                                </div>
                            </div>
                            
                            <!-- Assigned Devices -->
                            <div class="link-section mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-car me-1" style="color: #28a745;"></i>
                                    Appareils Assignés
                                </label>
                                <div class="linked-items-container" id="assignedDevicesList">
                                    <span class="text-muted">Aucun appareil assigné</span>
                                </div>
                            </div>

                            <!-- Available Devices -->
                            <div class="link-section">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-plus-circle me-1" style="color: #7556D6;"></i>
                                    Appareils Disponibles
                                </label>
                                <select id="availableDevicesSelect" class="form-select mb-2" onchange="assignDevice(this)">
                                    <option value="">Sélectionner un appareil à assigner...</option>
                                </select>
                                <div class="linked-items-container" id="availableDevicesList">
                                </div>
                            </div>

                            <div id="assignFormError" class="alert alert-danger d-none mt-3" role="alert"></div>
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

            <!-- Drivers Table -->
            <div class="table-container">
                <table class="data-table" id="driversTable">
                    <thead>
                        <tr>
                            <th class="th-checkbox">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Conducteur</th>
                            <th>Identifiant Unique</th>
                            <th>Téléphone</th>
                            <th>Appareils Assignés</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="driversTableBody">
                        <tr>
                            <td colspan="6" class="loading-cell">
                                <div class="table-loading">
                                    <div class="spinner"></div>
                                    <span>Chargement des conducteurs...</span>
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
    console.log('Drivers management script loaded');
    let allDrivers = [];
    let allDevices = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let refreshInterval = null;
    const REFRESH_RATE = 10000;

    // Charger les conducteurs au démarrage
    loadDrivers();
    
    // Démarrer le rafraîchissement automatique
    startRealTimeUpdates();

    // Event listeners
    document.getElementById('btnRefresh').addEventListener('click', loadDrivers);
    document.getElementById('searchDriver').addEventListener('input', debounce(filterDrivers, 300));
    document.getElementById('filterStatus').addEventListener('change', filterDrivers);
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
    document.getElementById('treeSearch').addEventListener('input', debounce(filterTree, 300));

    // Boutons Enregistrer - Modal Ajouter
    document.getElementById('btnAddSaveDriver').addEventListener('click', function(e) {
        e.preventDefault();
        saveDriver('add');
    });

    // Boutons Modifier - Modal Modifier
    document.getElementById('btnEditSaveDriver').addEventListener('click', function(e) {
        e.preventDefault();
        saveDriver('edit');
    });

    // Export
    document.getElementById('btnExport').addEventListener('click', exportDrivers);

    // Fonction pour démarrer les mises à jour en temps réel
    function startRealTimeUpdates() {
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(loadDriversSilent, REFRESH_RATE);
        console.log('Real-time updates started');
    }

    // Charger silencieusement
    async function loadDriversSilent() {
        try {
            const response = await fetch('{{ route("drivers.api.list") }}');
            const data = await response.json();
            
            if (data.success) {
                const newDrivers = data.drivers || [];
                if (JSON.stringify(allDrivers) !== JSON.stringify(newDrivers)) {
                    allDrivers = newDrivers;
                    allDevices = data.devices || [];
                    filterDrivers();
                    buildTreeView();
                    updateStats();
                }
            }
        } catch (error) {
            console.error('Erreur rafraîchissement:', error);
        }
    }

    // Pause when page not visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
            }
        } else {
            loadDriversSilent();
            startRealTimeUpdates();
        }
    });

    // Charger les conducteurs depuis l'API
    async function loadDrivers() {
        try {
            showTableLoading();
            const response = await fetch('{{ route("drivers.api.list") }}');
            const data = await response.json();
            console.log('Drivers response:', data);
            
            if (data.success) {
                allDrivers = data.drivers || [];
                allDevices = data.devices || [];
                filterDrivers();
                buildTreeView();
                updateStats();
            } else {
                showTableError('Erreur lors du chargement des conducteurs');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showTableError('Erreur de connexion au serveur');
        }
    }

    // Mettre à jour les statistiques
    function updateStats() {
        document.getElementById('totalDrivers').textContent = allDrivers.length;
        document.getElementById('totalDevices').textContent = allDevices.length;
        
        // Compter les assignés (à améliorer avec les données réelles)
        const assigned = allDrivers.filter(d => d.attributes && Object.keys(d.attributes).length > 0).length;
        document.getElementById('assignedDrivers').textContent = assigned;
        document.getElementById('unassignedDrivers').textContent = allDrivers.length - assigned;
    }

    // Sauvegarder un conducteur
    async function saveDriver(mode) {
        const formId = mode === 'edit' ? 'editDriverForm' : 'addDriverForm';
        const errorDivId = mode === 'edit' ? 'editDriverFormError' : 'addDriverFormError';
        const btnTextId = mode === 'edit' ? 'btnEditSaveDriverText' : 'btnAddSaveDriverText';
        const btnId = mode === 'edit' ? 'btnEditSaveDriver' : 'btnAddSaveDriver';
        const modalId = mode === 'edit' ? 'editDriverModal' : 'addDriverModal';
        
        const nameId = mode === 'edit' ? 'editDriverName' : 'addDriverName';
        const uniqueIdId = mode === 'edit' ? 'editDriverUniqueId' : 'addDriverUniqueId';
        const phoneId = mode === 'edit' ? 'editDriverPhone' : 'addDriverPhone';
        const licenseId = mode === 'edit' ? 'editDriverLicense' : 'addDriverLicense';
        const notesId = mode === 'edit' ? 'editDriverNotes' : 'addDriverNotes';
        const driverIdId = mode === 'edit' ? 'editDriverId' : 'addDriverId';
        
        const errorDiv = document.getElementById(errorDivId);
        const btnText = document.getElementById(btnTextId);
        const btn = document.getElementById(btnId);
        
        const name = document.getElementById(nameId).value.trim();
        const uniqueId = document.getElementById(uniqueIdId).value.trim();
        const phone = document.getElementById(phoneId).value.trim();
        const license = document.getElementById(licenseId).value.trim();
        const notes = document.getElementById(notesId).value.trim();
        const driverId = document.getElementById(driverIdId).value;
        
        if (!name || !uniqueId) {
            errorDiv.textContent = 'Le nom et l\'identifiant unique sont obligatoires.';
            errorDiv.classList.remove('d-none');
            return;
        }
        
        errorDiv.classList.add('d-none');
        
        const driverData = {
            name: name,
            uniqueId: uniqueId,
            attributes: {}
        };
        
        if (phone) driverData.attributes.phone = phone;
        if (license) driverData.attributes.license = license;
        if (notes) driverData.attributes.notes = notes;
        
        btn.disabled = true;
        btnText.textContent = mode === 'edit' ? 'Modification...' : 'Enregistrement...';
        
        try {
            const isEdit = mode === 'edit';
            const url = isEdit ? `{{ url('/drivers') }}/${driverId}` : '{{ route("drivers.api.store") }}';
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(driverData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                modal.hide();
                
                document.getElementById(formId).reset();
                document.getElementById(driverIdId).value = '0';
                
                await loadDrivers();
                console.log(isEdit ? 'Conducteur modifié' : 'Conducteur créé');
            } else {
                errorDiv.textContent = data.message || 'Erreur lors de l\'enregistrement';
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

    // Filtrer les conducteurs
    function filterDrivers() {
        const search = document.getElementById('searchDriver').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;

        let filtered = allDrivers.filter(driver => {
            const matchSearch = !search || 
                driver.name?.toLowerCase().includes(search) ||
                driver.uniqueId?.toLowerCase().includes(search);
            
            let matchStatus = true;
            if (status === 'assigned') matchStatus = driver.attributes && Object.keys(driver.attributes).length > 0;
            if (status === 'unassigned') matchStatus = !driver.attributes || Object.keys(driver.attributes).length === 0;

            return matchSearch && matchStatus;
        });

        currentPage = 1;
        renderTable(filtered);
    }

    // Afficher le tableau
    function renderTable(drivers) {
        const tbody = document.getElementById('driversTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedDrivers = drivers.slice(start, end);

        if (drivers.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-cell">
                        <div class="empty-state">
                            <i class="fas fa-id-card fa-3x"></i>
                            <p>Aucun conducteur trouvé</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDriverModal">
                                <i class="fas fa-plus me-1"></i>Ajouter un conducteur
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedDrivers.map(driver => {
                const phone = driver.attributes?.phone || '-';
                const hasDevices = driver.devices && driver.devices.length > 0;
                const deviceBadges = hasDevices ? driver.devices.map(d => 
                    `<span class="device-badge"><i class="fas fa-car"></i> ${d.name}</span>`
                ).join('') : '<span class="text-muted fst-italic">Aucun appareil</span>';
                
                return `
                    <tr data-id="${driver.id}">
                        <td><input type="checkbox" class="driver-checkbox" value="${driver.id}"></td>
                        <td>
                            <div class="user-name-cell">
                                <div class="user-avatar avatar-${hasDevices ? 'online' : 'offline'}">${getInitials(driver.name)}</div>
                                <span>${driver.name || '-'}</span>
                            </div>
                        </td>
                        <td><span class="unique-id-badge">${driver.uniqueId || '-'}</span></td>
                        <td>${phone}</td>
                        <td class="devices-cell">${deviceBadges}</td>
                        <td class="actions-cell">
                            <button class="btn-icon btn-edit" title="Modifier" onclick="editDriver(${driver.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-link" title="Assigner des appareils" onclick="openAssignModal(${driver.id})">
                                <i class="fas fa-link"></i>
                            </button>
                            <button class="btn-icon btn-delete" title="Supprimer" onclick="deleteDriver(${driver.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        updateTableInfo(drivers.length, start, Math.min(end, drivers.length));
        renderPagination(drivers.length);
    }

    // Construire le tree view
    function buildTreeView() {
        const treeContainer = document.getElementById('driverTree');
        
        // Grouper par statut d'assignation
        const assigned = allDrivers.filter(d => d.attributes && Object.keys(d.attributes).length > 0);
        const unassigned = allDrivers.filter(d => !d.attributes || Object.keys(d.attributes).length === 0);

        const groups = [
            { name: 'Assignés', icon: 'fa-link', drivers: assigned, status: 'online' },
            { name: 'Non Assignés', icon: 'fa-unlink', drivers: unassigned, status: 'offline' }
        ];

        let html = '';
        groups.forEach(group => {
            html += `
                <div class="tree-node expanded">
                    <div class="tree-parent" onclick="toggleTreeNode(this)">
                        <i class="fas fa-chevron-right tree-arrow"></i>
                        <i class="fas ${group.icon} tree-folder-icon"></i>
                        <span class="tree-label">${group.name}</span>
                        <span class="tree-count">${group.drivers.length}</span>
                    </div>
                    <div class="tree-children">
                        ${group.drivers.length > 0 ? group.drivers.map(d => `
                            <div class="tree-child" data-id="${d.id}" onclick="selectDriver(${d.id})">
                                <span class="tree-status status-${group.status}"></span>
                                <span class="tree-device-name">${d.name}</span>
                            </div>
                        `).join('') : '<div class="tree-empty-child">Aucun conducteur</div>'}
                    </div>
                </div>
            `;
        });

        treeContainer.innerHTML = html || '<div class="tree-empty">Aucun conducteur</div>';
    }

    // Filtrer le tree view
    function filterTree() {
        const search = document.getElementById('treeSearch').value.toLowerCase();
        const treeChildren = document.querySelectorAll('.tree-child');
        const treeNodes = document.querySelectorAll('.tree-node');

        treeChildren.forEach(child => {
            const name = child.querySelector('.tree-device-name')?.textContent.toLowerCase() || '';
            child.style.display = name.includes(search) ? 'flex' : 'none';
        });

        treeNodes.forEach(node => {
            const hasVisibleChildren = Array.from(node.querySelectorAll('.tree-child')).some(c => c.style.display !== 'none');
            node.style.display = hasVisibleChildren || !search ? 'block' : 'none';
        });
    }

    // Export CSV
    function exportDrivers() {
        let csv = 'Nom,Identifiant Unique,Téléphone,Permis,Notes\n';
        allDrivers.forEach(d => {
            csv += `"${d.name || ''}","${d.uniqueId || ''}","${d.attributes?.phone || ''}","${d.attributes?.license || ''}","${d.attributes?.notes || ''}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'conducteurs_' + new Date().toISOString().split('T')[0] + '.csv';
        link.click();
    }

    // Helpers
    function getInitials(name) {
        if (!name) return '?';
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    function showTableLoading() {
        document.getElementById('driversTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="loading-cell">
                    <div class="table-loading">
                        <div class="spinner"></div>
                        <span>Chargement des conducteurs...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    function showTableError(message) {
        document.getElementById('driversTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="error-cell">
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                        <p>${message}</p>
                        <button class="btn btn-primary btn-sm" onclick="loadDrivers()">Réessayer</button>
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
        document.querySelectorAll('.driver-checkbox').forEach(cb => cb.checked = checked);
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
        filterDrivers();
    };

    window.toggleTreeNode = function(element) {
        element.parentElement.classList.toggle('expanded');
    };

    window.selectDriver = function(id) {
        document.querySelectorAll('.tree-child').forEach(c => c.classList.remove('selected'));
        document.querySelector(`.tree-child[data-id="${id}"]`)?.classList.add('selected');
        
        document.querySelectorAll('#driversTable tbody tr').forEach(r => r.classList.remove('highlighted'));
        document.querySelector(`#driversTable tbody tr[data-id="${id}"]`)?.classList.add('highlighted');
    };

    window.editDriver = function(id) {
        const driver = allDrivers.find(d => d.id === id);
        if (!driver) return;
        
        document.getElementById('editDriverId').value = driver.id;
        document.getElementById('editDriverName').value = driver.name || '';
        document.getElementById('editDriverUniqueId').value = driver.uniqueId || '';
        document.getElementById('editDriverPhone').value = driver.attributes?.phone || '';
        document.getElementById('editDriverLicense').value = driver.attributes?.license || '';
        document.getElementById('editDriverNotes').value = driver.attributes?.notes || '';
        
        document.getElementById('editDriverFormError').classList.add('d-none');
        
        const modal = new bootstrap.Modal(document.getElementById('editDriverModal'));
        modal.show();
    };

    window.openAssignModal = async function(id) {
        const driver = allDrivers.find(d => d.id === id);
        if (!driver) return;
        
        document.getElementById('assignDriverId').value = id;
        document.getElementById('assignDriverName').textContent = driver.name;
        document.getElementById('assignDriverUniqueId').textContent = 'ID: ' + driver.uniqueId;
        document.getElementById('assignDriverAvatar').textContent = getInitials(driver.name);
        
        // Load assigned devices
        try {
            const response = await fetch(`{{ url('/drivers') }}/${id}/devices`);
            const data = await response.json();
            
            const assignedDevices = data.success ? (data.devices || []) : [];
            const assignedIds = assignedDevices.map(d => d.id);
            
            // Render assigned
            const assignedList = document.getElementById('assignedDevicesList');
            if (assignedDevices.length === 0) {
                assignedList.innerHTML = '<span class="text-muted">Aucun appareil assigné</span>';
            } else {
                assignedList.innerHTML = assignedDevices.map(device => `
                    <span class="linked-item-badge" id="assigned-${device.id}">
                        <i class="fas fa-car"></i>
                        ${device.name}
                        <button class="remove-link" onclick="unassignDevice(${id}, ${device.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                `).join('');
            }
            
            // Render available select
            const availableDevices = allDevices.filter(d => !assignedIds.includes(d.id));
            const selectEl = document.getElementById('availableDevicesSelect');
            selectEl.innerHTML = '<option value="">Sélectionner un appareil à assigner...</option>';
            availableDevices.forEach(d => {
                selectEl.innerHTML += `<option value="${d.id}">${d.name} (${d.uniqueId})</option>`;
            });
            
        } catch (error) {
            console.error('Error:', error);
        }
        
        const modal = new bootstrap.Modal(document.getElementById('assignDeviceModal'));
        modal.show();
    };

    window.assignDevice = async function(selectEl) {
        const deviceId = parseInt(selectEl.value);
        if (!deviceId) return;
        
        const driverId = parseInt(document.getElementById('assignDriverId').value);
        
        try {
            const response = await fetch('{{ route("drivers.api.assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ driverId, deviceId })
            });
            
            const result = await response.json();
            
            if (result.success) {
                await loadDrivers();
                window.openAssignModal(driverId);
            } else {
                document.getElementById('assignFormError').textContent = result.message || 'Erreur';
                document.getElementById('assignFormError').classList.remove('d-none');
            }
        } catch (error) {
            console.error('Error:', error);
        }
        
        selectEl.value = '';
    };

    window.unassignDevice = async function(driverId, deviceId) {
        try {
            const response = await fetch('{{ route("drivers.api.unassign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ driverId, deviceId })
            });
            
            const result = await response.json();
            
            if (result.success) {
                await loadDrivers();
                window.openAssignModal(driverId);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };

    window.deleteDriver = async function(id) {
        const driver = allDrivers.find(d => d.id === id);
        const result = await showDeleteConfirm(driver?.name || 'ce conducteur');
        if (!result.isConfirmed) return;
        
        try {
            const response = await fetch(`{{ url('/drivers') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            const data = await response.json();
            
            if (data.success) {
                showToast('Conducteur supprimé avec succès', 'success');
                loadDrivers();
            } else {
                showError('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showError('Erreur de connexion au serveur');
        }
    };

    window.loadDrivers = loadDrivers;
});
</script>
@endpush

@push('styles')
<style>
/* ===================== DRIVERS PAGE - SAME STYLE AS ACCOUNT ===================== */

/* Card Header Enhanced */
.card-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.card-header-custom h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.card-header-custom .text-muted {
    color: #999 !important;
}

/* Filters and Actions Section */
.filters-and-actions-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin: 25px 0;
    flex-wrap: wrap;
}

.filters-section {
    flex: 1;
    min-width: 280px;
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 15px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
}

.filter-input,
.filter-select {
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
    transition: all 0.3s ease;
}

.filter-input:focus,
.filter-select:focus {
    border-color: #7556D6;
    box-shadow: 0 0 0 3px rgba(117, 86, 214, 0.1);
    outline: none;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-buttons .btn {
    padding: 10px 16px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    border: 1px solid transparent;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
    padding: 20px;
    background: rgba(117, 86, 214, 0.02);
    border-radius: 10px;
}

.stat-card {
    display: flex;
    align-items: center;
    padding: 18px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    border-left: 4px solid #7556D6;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.stat-card.stat-total { border-left-color: #7556D6; }
.stat-card.stat-active { border-left-color: #28a745; }
.stat-card.stat-disabled { border-left-color: #dc3545; }
.stat-card.stat-admin { border-left-color: #ffc107; }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    margin-right: 15px;
}

.stat-total .stat-icon { background: rgba(117, 86, 214, 0.12); color: #7556D6; }
.stat-active .stat-icon { background: rgba(40, 167, 69, 0.12); color: #28a745; }
.stat-disabled .stat-icon { background: rgba(220, 53, 69, 0.12); color: #dc3545; }
.stat-admin .stat-icon { background: rgba(255, 193, 7, 0.12); color: #ffc107; }

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: #333;
    line-height: 1;
}

.stat-label {
    font-size: 0.8rem;
    color: #999;
    font-weight: 500;
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
    background: linear-gradient(135deg, #6c757d, #adb5bd);
    box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.2);
}

/* Unique ID Badge */
.unique-id-badge {
    font-family: 'Courier New', monospace;
    background: #f0f0f0;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.85rem;
    color: #333;
}

/* Device Badge */
.device-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    margin: 2px;
}

.devices-cell {
    max-width: 250px;
}

/* Actions */
.actions-cell {
    display: flex;
    gap: 6px;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    cursor: pointer;
}

.btn-icon:hover {
    transform: scale(1.1);
}

.btn-edit {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.btn-edit:hover {
    background: #6c757d;
    color: white;
}

.btn-link {
    background: rgba(117, 86, 214, 0.1);
    color: #7556D6;
}

.btn-link:hover {
    background: #7556D6;
    color: white;
}

.btn-delete {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
}

/* Driver Info Card in Modal */
.driver-info-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #efefef 100%);
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}

.driver-info-card .driver-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7556D6, #9b7ce8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 700;
}

.driver-info-card .driver-details h5 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.driver-info-card .driver-details span {
    font-size: 0.85rem;
    color: #999;
}

/* Link Section */
.link-section {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
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
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
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
    font-size: 10px;
}

.linked-item-badge .remove-link:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Form hint */
.form-hint {
    font-size: 0.85rem;
    color: #666;
    padding: 10px;
    background: rgba(117, 86, 214, 0.05);
    border-radius: 6px;
    border-left: 3px solid #7556D6;
}

/* Tree folder icon */
.tree-folder-icon {
    margin-right: 6px;
    font-size: 14px;
    color: #7556D6;
}

.tree-empty-child {
    padding: 8px 16px;
    color: #999;
    font-style: italic;
    font-size: 0.85rem;
}

/* Modal Enhancements */
.modal-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #efefef 100%);
    border-bottom: 2px solid #e0e0e0;
}

.modal-title {
    font-weight: 600;
    color: #333;
}

/* Table row highlight */
#driversTable tbody tr.highlighted {
    background-color: #fffbea;
    border-left: 3px solid #ffc107;
}

/* Responsive */
@media (max-width: 768px) {
    .filters-and-actions-section {
        flex-direction: column;
    }
    
    .filters-section {
        min-width: 100%;
    }
    
    .action-buttons {
        width: 100%;
    }
    
    .action-buttons .btn {
        flex: 1;
        justify-content: center;
    }
}
</style>
@endpush
@endsection
