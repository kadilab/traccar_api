@extends('layouts.app')

@section('title', 'Groupes - Traccar TF')

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
        
        <div class="tree-view" id="groupTree">
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
                    <i class="fas fa-layer-group me-2"></i>
                    Gestion des Groupes
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
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalGroups">0</span>
                        <span class="stat-label">Total Groupes</span>
                    </div>
                </div>
                <div class="stat-card stat-active">
                    <div class="stat-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalDevices">0</span>
                        <span class="stat-label">Devices Assignés</span>
                    </div>
                </div>
                <div class="stat-card stat-disabled">
                    <div class="stat-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="parentGroups">0</span>
                        <span class="stat-label">Groupes Parents</span>
                    </div>
                </div>
                <div class="stat-card stat-admin">
                    <div class="stat-icon">
                        <i class="fas fa-folder-tree"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="childGroups">0</span>
                        <span class="stat-label">Sous-Groupes</span>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>Recherche</label>
                        <input type="text" id="searchGroup" class="filter-input" placeholder="Nom du groupe...">
                    </div>
                    <div class="filter-group">
                        <label>Groupe Parent</label>
                        <select id="filterParent" class="filter-select">
                            <option value="">Tous</option>
                            <option value="root">Groupes racines</option>
                            <option value="child">Sous-groupes</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" id="btnAddGroup" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                    <i class="fas fa-plus-circle"></i>
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

            <!-- Modal Ajouter Groupe -->
            <div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="addGroupModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addGroupModalLabel">
                                <i class="fas fa-plus-circle me-2"></i>
                                Ajouter un Groupe
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addGroupForm">
                                <input type="hidden" id="addGroupId" name="id" value="0">
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="addGroupName" class="form-label">Nom du Groupe <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addGroupName" name="name" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="addGroupParent" class="form-label">Groupe Parent</label>
                                        <select class="form-select" id="addGroupParent" name="groupId">
                                            <option value="">-- Aucun (Groupe racine) --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Attributs personnalisés</label>
                                        <div class="attributes-container" id="addAttributesContainer">
                                            <div class="attribute-row">
                                                <input type="text" class="form-control" placeholder="Clé" name="attrKey[]">
                                                <input type="text" class="form-control" placeholder="Valeur" name="attrValue[]">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addAttributeRow('addAttributesContainer')">
                                            <i class="fas fa-plus me-1"></i> Ajouter un attribut
                                        </button>
                                    </div>
                                </div>

                                <div id="addGroupFormError" class="alert alert-danger d-none" role="alert"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="btnAddSaveGroup">
                                <i class="fas fa-save me-1"></i>
                                <span id="btnAddSaveGroupText">Enregistrer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Modifier Groupe -->
            <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="editGroupModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editGroupModalLabel">
                                <i class="fas fa-edit me-2"></i>
                                Modifier le Groupe
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editGroupForm">
                                <input type="hidden" id="editGroupId" name="id" value="0">
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="editGroupName" class="form-label">Nom du Groupe <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="editGroupName" name="name" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="editGroupParent" class="form-label">Groupe Parent</label>
                                        <select class="form-select" id="editGroupParent" name="groupId">
                                            <option value="">-- Aucun (Groupe racine) --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Attributs personnalisés</label>
                                        <div class="attributes-container" id="editAttributesContainer">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addAttributeRow('editAttributesContainer')">
                                            <i class="fas fa-plus me-1"></i> Ajouter un attribut
                                        </button>
                                    </div>
                                </div>

                                <div id="editGroupFormError" class="alert alert-danger d-none" role="alert"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                            <button type="button" class="btn btn-primary" id="btnEditSaveGroup">
                                <i class="fas fa-save me-1"></i>
                                <span id="btnEditSaveGroupText">Modifier</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Voir Devices du Groupe -->
            <div class="modal fade" id="viewDevicesModal" tabindex="-1" aria-labelledby="viewDevicesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewDevicesModalLabel">
                                <i class="fas fa-car me-2"></i>
                                Devices du Groupe
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="groupDevicesList" class="devices-list">
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                </div>
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

            <!-- Groups Table -->
            <div class="table-container">
                <table class="data-table" id="groupsTable">
                    <thead>
                        <tr>
                            <th class="th-checkbox">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Groupe Parent</th>
                            <th>Devices</th>
                            <th>Attributs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="groupsTableBody">
                        <tr>
                            <td colspan="7" class="loading-cell">
                                <div class="table-loading">
                                    <div class="spinner"></div>
                                    <span>Chargement des groupes...</span>
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
    console.log('Group management script loaded');
    let allGroups = [];
    let allDevices = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let refreshInterval = null;
    const REFRESH_RATE = 10000;

    // Charger les données au démarrage
    loadGroups();
    loadDevices();
    
    // Démarrer le rafraîchissement automatique
    startRealTimeUpdates();

    // Event listeners
    document.getElementById('btnRefresh').addEventListener('click', loadGroups);
    document.getElementById('searchGroup').addEventListener('input', debounce(filterGroups, 300));
    document.getElementById('filterParent').addEventListener('change', filterGroups);
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
    document.getElementById('treeSearch').addEventListener('input', debounce(filterTree, 300));

    // Bouton Enregistrer - Modal Ajouter
    const btnAddSaveGroup = document.getElementById('btnAddSaveGroup');
    if (btnAddSaveGroup) {
        btnAddSaveGroup.addEventListener('click', function(e) {
            e.preventDefault();
            saveGroup('add');
        });
    }

    // Bouton Modifier - Modal Modifier
    const btnEditSaveGroup = document.getElementById('btnEditSaveGroup');
    if (btnEditSaveGroup) {
        btnEditSaveGroup.addEventListener('click', function(e) {
            e.preventDefault();
            saveGroup('edit');
        });
    }

    // Bouton Supprimer sélectionnés
    document.getElementById('btnDeleteSelected').addEventListener('click', deleteSelectedGroups);

    // Bouton Exporter
    document.getElementById('btnExport').addEventListener('click', exportGroups);

    // Fonction pour démarrer les mises à jour en temps réel
    function startRealTimeUpdates() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        refreshInterval = setInterval(async () => {
            await loadGroupsSilent();
        }, REFRESH_RATE);
        
        console.log('Real-time updates started (every ' + (REFRESH_RATE/1000) + 's)');
    }

    // Charger les groupes silencieusement
    async function loadGroupsSilent() {
        try {
            const response = await fetch('/api/traccar/groups');
            const data = await response.json();
            
            if (data.success) {
                const newGroups = data.groups || [];
                
                if (JSON.stringify(allGroups) !== JSON.stringify(newGroups)) {
                    allGroups = newGroups;
                    filterGroups();
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
            loadGroupsSilent();
            startRealTimeUpdates();
        }
    });

    // Charger les groupes depuis l'API
    async function loadGroups() {
        try {
            showTableLoading();
            const response = await fetch('/api/traccar/groups');
            const data = await response.json();
            console.log('Groups response:', data);
            
            if (data.success) {
                allGroups = data.groups || [];
                filterGroups();
                buildTreeView();
                updateStats();
                populateParentSelects();
            } else {
                showTableError('Erreur lors du chargement des groupes');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showTableError('Erreur de connexion au serveur');
        }
    }

    // Charger les devices pour compter par groupe
    async function loadDevices() {
        try {
            const response = await fetch('/api/traccar/devices');
            const data = await response.json();
            
            if (data.success) {
                allDevices = data.devices || [];
                updateStats();
            }
        } catch (error) {
            console.error('Erreur chargement devices:', error);
        }
    }

    // Mettre à jour les statistiques
    function updateStats() {
        const totalGroups = allGroups.length;
        const parentGroups = allGroups.filter(g => !g.groupId).length;
        const childGroups = allGroups.filter(g => g.groupId).length;
        const totalDevicesInGroups = allDevices.filter(d => d.groupId).length;

        document.getElementById('totalGroups').textContent = totalGroups;
        document.getElementById('totalDevices').textContent = totalDevicesInGroups;
        document.getElementById('parentGroups').textContent = parentGroups;
        document.getElementById('childGroups').textContent = childGroups;
    }

    // Remplir les selects de groupe parent
    function populateParentSelects() {
        const addSelect = document.getElementById('addGroupParent');
        const editSelect = document.getElementById('editGroupParent');
        
        const options = '<option value="">-- Aucun (Groupe racine) --</option>' +
            allGroups.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
        
        addSelect.innerHTML = options;
        editSelect.innerHTML = options;
    }

    // Sauvegarder un groupe
    async function saveGroup(mode) {
        const formId = mode === 'edit' ? 'editGroupForm' : 'addGroupForm';
        const errorDivId = mode === 'edit' ? 'editGroupFormError' : 'addGroupFormError';
        const btnTextId = mode === 'edit' ? 'btnEditSaveGroupText' : 'btnAddSaveGroupText';
        const btnId = mode === 'edit' ? 'btnEditSaveGroup' : 'btnAddSaveGroup';
        const modalId = mode === 'edit' ? 'editGroupModal' : 'addGroupModal';
        
        const form = document.getElementById(formId);
        const errorDiv = document.getElementById(errorDivId);
        const btnText = document.getElementById(btnTextId);
        const btn = document.getElementById(btnId);
        
        const nameId = mode === 'edit' ? 'editGroupName' : 'addGroupName';
        const parentId = mode === 'edit' ? 'editGroupParent' : 'addGroupParent';
        const groupIdId = mode === 'edit' ? 'editGroupId' : 'addGroupId';
        const attrContainerId = mode === 'edit' ? 'editAttributesContainer' : 'addAttributesContainer';
        
        const name = document.getElementById(nameId).value.trim();
        const parentGroupId = document.getElementById(parentId).value;
        const groupId = document.getElementById(groupIdId).value;
        
        if (!name) {
            errorDiv.textContent = 'Le nom du groupe est obligatoire.';
            errorDiv.classList.remove('d-none');
            return;
        }
        
        errorDiv.classList.add('d-none');
        
        // Collecter les attributs (seulement si remplis)
        const attributes = {};
        const attrRows = document.querySelectorAll(`#${attrContainerId} .attribute-row`);
        attrRows.forEach(row => {
            const keyInput = row.querySelector('input[name="attrKey[]"]');
            const valueInput = row.querySelector('input[name="attrValue[]"]');
            if (keyInput && valueInput) {
                const key = keyInput.value.trim();
                const value = valueInput.value.trim();
                if (key && value) {
                    attributes[key] = value;
                }
            }
        });
        
        // Préparer les données
        const groupData = {
            name: name
        };
        
        // Ajouter les attributs seulement s'il y en a
        if (Object.keys(attributes).length > 0) {
            groupData.attributes = attributes;
        }
        
        if (parentGroupId) {
            groupData.groupId = parseInt(parentGroupId);
        }
        
        if (mode === 'edit') {
            groupData.id = parseInt(groupId);
        }
        
        // Afficher loading
        btn.disabled = true;
        btnText.textContent = mode === 'edit' ? 'Modification...' : 'Enregistrement...';
        
        try {
            const isEdit = mode === 'edit';
            const url = isEdit ? `/api/traccar/groups/${groupId}` : '/api/traccar/groups';
            const method = isEdit ? 'PUT' : 'POST';
            console.log('Saving group:', groupData, 'to', url);
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(groupData)
            });
            
            const data = await response.json();
            
            if (data.success || response.ok) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                modal.hide();
                
                form.reset();
                document.getElementById(groupIdId).value = '0';
                
                // Réinitialiser les attributs
                const attrContainer = document.getElementById(attrContainerId);
                attrContainer.innerHTML = `
                    <div class="attribute-row">
                        <input type="text" class="form-control" placeholder="Clé" name="attrKey[]">
                        <input type="text" class="form-control" placeholder="Valeur" name="attrValue[]">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                await loadGroups();
                console.log(isEdit ? 'Groupe modifié avec succès' : 'Groupe créé avec succès');
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

    // Filtrer les groupes
    function filterGroups() {
        const search = document.getElementById('searchGroup').value.toLowerCase();
        const parentFilter = document.getElementById('filterParent').value;

        let filtered = allGroups.filter(group => {
            const matchSearch = !search || group.name?.toLowerCase().includes(search);
            
            let matchParent = true;
            if (parentFilter === 'root') matchParent = !group.groupId;
            if (parentFilter === 'child') matchParent = !!group.groupId;

            return matchSearch && matchParent;
        });

        currentPage = 1;
        renderTable(filtered);
    }

    // Afficher le tableau
    function renderTable(groups) {
        const tbody = document.getElementById('groupsTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedGroups = groups.slice(start, end);

        if (groups.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-cell">
                        <div class="empty-state">
                            <i class="fas fa-layer-group fa-3x"></i>
                            <p>Aucun groupe trouvé</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedGroups.map(group => {
                const parentGroup = allGroups.find(g => g.id === group.groupId);
                const deviceCount = allDevices.filter(d => d.groupId === group.id).length;
                const attrCount = group.attributes ? Object.keys(group.attributes).length : 0;
                
                return `
                    <tr data-id="${group.id}">
                        <td><input type="checkbox" class="group-checkbox" value="${group.id}"></td>
                        <td><span class="badge bg-secondary">#${group.id}</span></td>
                        <td>
                            <div class="group-name-cell">
                                <i class="fas fa-folder text-warning me-2"></i>
                                <span>${group.name || '-'}</span>
                            </div>
                        </td>
                        <td>
                            ${parentGroup ? `<span class="badge bg-info"><i class="fas fa-level-up-alt me-1"></i>${parentGroup.name}</span>` : '<span class="text-muted">- Racine -</span>'}
                        </td>
                        <td>
                            <span class="badge bg-primary">${deviceCount} device${deviceCount > 1 ? 's' : ''}</span>
                        </td>
                        <td>
                            ${attrCount > 0 ? `<span class="badge bg-secondary">${attrCount} attribut${attrCount > 1 ? 's' : ''}</span>` : '<span class="text-muted">-</span>'}
                        </td>
                        <td class="actions-cell">
                            <button class="btn-icon btn-edit" title="Modifier" onclick="editGroup(${group.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-locate" title="Voir les devices" onclick="viewGroupDevices(${group.id})">
                                <i class="fas fa-car"></i>
                            </button>
                            <button class="btn-icon btn-delete" title="Supprimer" onclick="deleteGroup(${group.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        updateTableInfo(groups.length, start, Math.min(end, groups.length));
        renderPagination(groups.length);
    }

    // Construire le tree view
    function buildTreeView() {
        const treeContainer = document.getElementById('groupTree');
        
        // Grouper les groupes par parent
        const rootGroups = allGroups.filter(g => !g.groupId);
        const childGroups = allGroups.filter(g => g.groupId);

        function buildGroupNode(group) {
            const children = childGroups.filter(g => g.groupId === group.id);
            const deviceCount = allDevices.filter(d => d.groupId === group.id).length;
            
            let childrenHtml = '';
            if (children.length > 0) {
                childrenHtml = `
                    <div class="tree-children">
                        ${children.map(child => buildGroupNode(child)).join('')}
                    </div>
                `;
            }
            
            return `
                <div class="tree-node ${children.length > 0 ? 'expanded' : ''}">
                    <div class="tree-parent" onclick="${children.length > 0 ? 'toggleTreeNode(this)' : `selectGroup(${group.id})`}">
                        ${children.length > 0 ? '<i class="fas fa-chevron-right tree-arrow"></i>' : '<i class="fas fa-circle tree-arrow" style="font-size: 6px; opacity: 0.3;"></i>'}
                        <i class="fas fa-folder tree-folder-icon"></i>
                        <span class="tree-label">${group.name}</span>
                        <span class="tree-count">${deviceCount}</span>
                    </div>
                    ${childrenHtml}
                </div>
            `;
        }

        let html = rootGroups.map(group => buildGroupNode(group)).join('');
        treeContainer.innerHTML = html || '<div class="tree-empty">Aucun groupe</div>';
    }

    // Filtrer le tree view
    function filterTree() {
        const search = document.getElementById('treeSearch').value.toLowerCase();
        const treeNodes = document.querySelectorAll('.tree-node');

        treeNodes.forEach(node => {
            const label = node.querySelector('.tree-label')?.textContent.toLowerCase();
            const matches = label?.includes(search);
            node.style.display = matches || !search ? 'block' : 'none';
        });
    }

    // Helpers
    function showTableLoading() {
        document.getElementById('groupsTableBody').innerHTML = `
            <tr>
                <td colspan="7" class="loading-cell">
                    <div class="table-loading">
                        <div class="spinner"></div>
                        <span>Chargement des groupes...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    function showTableError(message) {
        document.getElementById('groupsTableBody').innerHTML = `
            <tr>
                <td colspan="7" class="error-cell">
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                        <p>${message}</p>
                        <button class="btn btn-primary btn-sm" onclick="loadGroups()">Réessayer</button>
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
        document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = checked);
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Supprimer les groupes sélectionnés
    async function deleteSelectedGroups() {
        const selected = Array.from(document.querySelectorAll('.group-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            alert('Veuillez sélectionner au moins un groupe.');
            return;
        }
        
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selected.length} groupe(s) ?`)) return;
        
        for (const id of selected) {
            try {
                await fetch(`/api/traccar/groups/${id}`, { method: 'DELETE' });
            } catch (error) {
                console.error('Erreur suppression groupe:', id, error);
            }
        }
        
        loadGroups();
    }

    // Exporter les groupes
    function exportGroups() {
        const csv = [
            ['ID', 'Nom', 'Groupe Parent', 'Devices', 'Attributs'].join(','),
            ...allGroups.map(g => {
                const parent = allGroups.find(p => p.id === g.groupId);
                const deviceCount = allDevices.filter(d => d.groupId === g.id).length;
                const attrs = g.attributes ? JSON.stringify(g.attributes) : '';
                return [g.id, g.name, parent?.name || '', deviceCount, attrs].join(',');
            })
        ].join('\n');
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'groupes.csv';
        a.click();
    }

    // Fonctions globales
    window.goToPage = function(page) {
        currentPage = page;
        filterGroups();
    };

    window.toggleTreeNode = function(element) {
        element.parentElement.classList.toggle('expanded');
    };

    window.selectGroup = function(id) {
        document.querySelectorAll('.tree-node').forEach(n => n.classList.remove('selected'));
        document.querySelectorAll(`#groupsTable tbody tr`).forEach(r => r.classList.remove('highlighted'));
        document.querySelector(`#groupsTable tbody tr[data-id="${id}"]`)?.classList.add('highlighted');
    };

    window.editGroup = function(id) {
        const group = allGroups.find(g => g.id === id);
        if (!group) {
            console.error('Group not found:', id);
            return;
        }
        
        document.getElementById('editGroupId').value = group.id;
        document.getElementById('editGroupName').value = group.name || '';
        document.getElementById('editGroupParent').value = group.groupId || '';
        
        // Charger les attributs
        const attrContainer = document.getElementById('editAttributesContainer');
        attrContainer.innerHTML = '';
        
        if (group.attributes && Object.keys(group.attributes).length > 0) {
            Object.entries(group.attributes).forEach(([key, value]) => {
                attrContainer.innerHTML += `
                    <div class="attribute-row">
                        <input type="text" class="form-control" placeholder="Clé" name="attrKey[]" value="${key}">
                        <input type="text" class="form-control" placeholder="Valeur" name="attrValue[]" value="${value}">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            });
        } else {
            attrContainer.innerHTML = `
                <div class="attribute-row">
                    <input type="text" class="form-control" placeholder="Clé" name="attrKey[]">
                    <input type="text" class="form-control" placeholder="Valeur" name="attrValue[]">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }
        
        // Ne pas permettre de s'assigner comme propre parent
        const parentSelect = document.getElementById('editGroupParent');
        Array.from(parentSelect.options).forEach(opt => {
            opt.disabled = parseInt(opt.value) === id;
        });
        
        document.getElementById('editGroupFormError').classList.add('d-none');
        
        const modal = new bootstrap.Modal(document.getElementById('editGroupModal'));
        modal.show();
    };

    window.viewGroupDevices = function(id) {
        const group = allGroups.find(g => g.id === id);
        const devices = allDevices.filter(d => d.groupId === id);
        
        const container = document.getElementById('groupDevicesList');
        document.getElementById('viewDevicesModalLabel').innerHTML = `
            <i class="fas fa-car me-2"></i>
            Devices du Groupe "${group?.name || id}"
        `;
        
        if (devices.length === 0) {
            container.innerHTML = `
                <div class="empty-state text-center py-4">
                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun device dans ce groupe</p>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="list-group">
                    ${devices.map(d => `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-car me-2 text-primary"></i>
                                <strong>${d.name}</strong>
                                <small class="text-muted ms-2">${d.uniqueId || ''}</small>
                            </div>
                            <span class="badge ${d.status === 'online' ? 'bg-success' : 'bg-secondary'}">
                                ${d.status || 'unknown'}
                            </span>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        const modal = new bootstrap.Modal(document.getElementById('viewDevicesModal'));
        modal.show();
    };

    window.deleteGroup = async function(id) {
        const group = allGroups.find(g => g.id === id);
        if (!confirm(`Êtes-vous sûr de vouloir supprimer le groupe "${group?.name}" ?`)) return;
        
        try {
            const response = await fetch(`/api/traccar/groups/${id}`, { method: 'DELETE' });
            const data = await response.json();
            
            if (data.success || response.ok) {
                loadGroups();
            } else {
                alert('Erreur lors de la suppression: ' + (data.message || 'Erreur inconnue'));
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        }
    };

    window.loadGroups = loadGroups;
});

// Fonctions globales pour les attributs
function addAttributeRow(containerId) {
    const container = document.getElementById(containerId);
    const row = document.createElement('div');
    row.className = 'attribute-row';
    row.innerHTML = `
        <input type="text" class="form-control" placeholder="Clé" name="attrKey[]">
        <input type="text" class="form-control" placeholder="Valeur" name="attrValue[]">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(row);
}

function removeAttributeRow(button) {
    const row = button.closest('.attribute-row');
    const container = row.parentElement;
    
    if (container.querySelectorAll('.attribute-row').length > 1) {
        row.remove();
    } else {
        // Réinitialiser les champs si c'est la dernière ligne
        row.querySelectorAll('input').forEach(input => input.value = '');
    }
}
</script>
@endpush

@push('styles')
<style>
/* Attributes Container */
.attributes-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.attribute-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.attribute-row:last-child {
    margin-bottom: 0;
}

.attribute-row input {
    flex: 1;
}

.attribute-row button {
    flex-shrink: 0;
}

/* Group Name Cell */
.group-name-cell {
    display: flex;
    align-items: center;
}

/* Tree View Custom for Groups */
.tree-folder-icon {
    color: #f0ad4e;
    margin-right: 8px;
}

/* Devices List */
.devices-list .list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.devices-list .list-group-item:hover {
    border-left-color: #7556D6;
    background: #f8f9fa;
}

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left: 4px solid #7556D6;
}

.stat-card.stat-total { border-left-color: #7556D6; }
.stat-card.stat-active { border-left-color: #28a745; }
.stat-card.stat-disabled { border-left-color: #ffc107; }
.stat-card.stat-admin { border-left-color: #17a2b8; }

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-total .stat-icon { background: rgba(117, 86, 214, 0.1); color: #7556D6; }
.stat-active .stat-icon { background: rgba(40, 167, 69, 0.1); color: #28a745; }
.stat-disabled .stat-icon { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
.stat-admin .stat-icon { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    line-height: 1;
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
    margin-top: 4px;
}
</style>
@endpush

@endsection
