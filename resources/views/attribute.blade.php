@extends('layouts.app')

@section('title', 'Attributs Calculés - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Main Content -->
    <main class="main-content">
        <div class="content-card">
            <div class="card-header-custom">
                <h3>Gestion des Attributs Calculés</h3>
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
                        <input type="text" id="searchAttribute" class="filter-input" placeholder="Description, Attribute, Expression...">
                    </div>
                    <div class="filter-group">
                        <label>Type</label>
                        <select id="filterType" class="filter-select">
                            <option value="">Tous les types</option>
                            <option value="String">String</option>
                            <option value="Number">Number</option>
                            <option value="Boolean">Boolean</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                @if(Auth::user()->administrator)
                <button class="btn btn-primary" id="btnAddAttribute" data-bs-toggle="modal" data-bs-target="#addAttributeModal">
                    <i class="fas fa-plus"></i>
                    Ajouter
                </button>
                @endif
                <button class="btn btn-success" id="btnRefresh">
                    <i class="fas fa-sync-alt"></i>
                    Rafraîchir
                </button>
                @if(Auth::user()->administrator)
                <button class="btn btn-danger" id="btnDeleteSelected">
                    <i class="fas fa-trash"></i>
                    Supprimer
                </button>
                @endif
            </div>

            <!-- Modal Ajouter Attribut -->
            <div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAttributeModalLabel">Ajouter un Attribut Calculé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addAttributeForm">
                                <div class="mb-3">
                                    <label for="addAttributeDescription" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="addAttributeDescription" placeholder="Description lisible" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addAttributeName" class="form-label">Nom de l'Attribut</label>
                                    <input type="text" class="form-control" id="addAttributeName" placeholder="attribute_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addAttributeType" class="form-label">Type</label>
                                    <select class="form-select" id="addAttributeType" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="String">String</option>
                                        <option value="Number">Number</option>
                                        <option value="Boolean">Boolean</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="addAttributeExpression" class="form-label">Expression</label>
                                    <textarea class="form-control" id="addAttributeExpression" rows="4" placeholder="Expression de calcul" required></textarea>
                                    <small class="form-text text-muted">Ex: speed * 3.6 (convertir knots en km/h)</small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" id="btnSaveAttribute">Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Éditer Attribut -->
            <div class="modal fade" id="editAttributeModal" tabindex="-1" aria-labelledby="editAttributeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAttributeModalLabel">Éditer l'Attribut Calculé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editAttributeForm">
                                <input type="hidden" id="editAttributeId">
                                <div class="mb-3">
                                    <label for="editAttributeDescription" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="editAttributeDescription" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editAttributeName" class="form-label">Nom de l'Attribut</label>
                                    <input type="text" class="form-control" id="editAttributeName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editAttributeType" class="form-label">Type</label>
                                    <select class="form-select" id="editAttributeType" required>
                                        <option value="String">String</option>
                                        <option value="Number">Number</option>
                                        <option value="Boolean">Boolean</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editAttributeExpression" class="form-label">Expression</label>
                                    <textarea class="form-control" id="editAttributeExpression" rows="4" required></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" id="btnUpdateAttribute">Mettre à jour</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des Attributs -->
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="attributesTable">
                    <thead class="table-light">
                        <tr>
                            @if(Auth::user()->administrator)
                            <th style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            @endif
                            <th>Description</th>
                            <th>Attribut</th>
                            <th>Type</th>
                            <th>Expression</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="attributesTableBody">
                        <tr>
                            <td colspan="6" class="loading-cell">
                                <div class="table-loading">
                                    <div class="spinner"></div>
                                    <span>Chargement des attributs...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table Info -->
            <div class="table-footer">
                <span id="tableInfo">Aucun attribut</span>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Attributes management script loaded');
    
    const isAdmin = {{ Auth::user()->administrator ? 'true' : 'false' }};
    
    let allAttributes = [];
    let refreshInterval = null;
    const REFRESH_RATE = 5000; // Rafraîchissement toutes les 5 secondes

    // Charger les attributs
    async function loadAttributes() {
        try {
            showTableLoading();
            const response = await fetch('/api/traccar/attributes/computed?all=' + isAdmin);
            const data = await response.json();
            
            if (data.success) {
                allAttributes = Array.isArray(data.attributes) ? data.attributes : data.attribute || [];
                renderAttributesTable(allAttributes);
                startRealTimeUpdates();
                updateTableInfo(allAttributes.length);
            } else {
                showTableError(data.message || 'Erreur lors du chargement des attributs');
            }
        } catch (error) {
            console.error('Erreur lors du chargement des attributs:', error);
            showTableError('Erreur lors du chargement des attributs');
        }
    }

    // Démarrer les mises à jour en temps réel
    function startRealTimeUpdates() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        refreshInterval = setInterval(async () => {
            await loadAttributesSilent();
        }, REFRESH_RATE);
        
        console.log('Real-time updates started (every ' + (REFRESH_RATE/1000) + 's)');
    }

    // Charger les attributs sans afficher le loader
    async function loadAttributesSilent() {
        try {
            const response = await fetch('/api/traccar/attributes/computed?all=' + isAdmin);
            const data = await response.json();
            
            if (data.success) {
                allAttributes = Array.isArray(data.attributes) ? data.attributes : data.attribute || [];
                renderAttributesTable(allAttributes);
                updateTableInfo(allAttributes.length);
            }
        } catch (error) {
            console.error('Erreur lors du rafraîchissement:', error);
        }
    }

    // Afficher le tableau des attributs
    function renderAttributesTable(attributes) {
        const tbody = document.getElementById('attributesTableBody');
        
        if (attributes.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Aucun attribut trouvé
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = attributes.map(attr => `
            <tr data-id="${attr.id}">
                ${isAdmin ? `<td><input type="checkbox" class="form-check-input attribute-checkbox" value="${attr.id}"></td>` : ''}
                <td><strong>${attr.description || '-'}</strong></td>
                <td><code>${attr.attribute || '-'}</code></td>
                <td><span class="badge bg-info">${attr.type || 'Unknown'}</span></td>
                <td>
                    <code class="small">${truncateExpression(attr.expression || '-', 50)}</code>
                </td>
                <td>
                    <div class="action-group">
                        ${isAdmin ? `
                            <button class="btn btn-sm btn-warning" onclick="editAttribute(${attr.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteAttribute(${attr.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-info" onclick="viewAttributeDetails(${attr.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Tronquer l'expression pour l'affichage
    function truncateExpression(expr, maxLength) {
        return expr.length > maxLength ? expr.substring(0, maxLength) + '...' : expr;
    }

    // Filtrer les attributs
    function filterAttributes() {
        const search = document.getElementById('searchAttribute').value.toLowerCase();
        const typeFilter = document.getElementById('filterType').value;
        
        const filtered = allAttributes.filter(attr => {
            const matchSearch = !search || 
                attr.description?.toLowerCase().includes(search) ||
                attr.attribute?.toLowerCase().includes(search) ||
                attr.expression?.toLowerCase().includes(search);
            
            const matchType = !typeFilter || attr.type === typeFilter;
            
            return matchSearch && matchType;
        });
        
        renderAttributesTable(filtered);
        updateTableInfo(filtered.length);
    }

    // Ajouter un attribut
    async function createAttribute() {
        const description = document.getElementById('addAttributeDescription').value;
        const attribute = document.getElementById('addAttributeName').value;
        const type = document.getElementById('addAttributeType').value;
        const expression = document.getElementById('addAttributeExpression').value;
        
        if (!description || !attribute || !type || !expression) {
            alert('Tous les champs sont obligatoires');
            return;
        }
        
        try {
            const response = await fetch('/api/traccar/attributes/computed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    description,
                    attribute,
                    type,
                    expression
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Attribut ajouté avec succès');
                document.getElementById('addAttributeForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('addAttributeModal')).hide();
                await loadAttributes();
            } else {
                alert('Erreur: ' + (data.message || 'Erreur lors de l\'ajout'));
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'ajout de l\'attribut');
        }
    }

    // Éditer un attribut
    window.editAttribute = function(id) {
        const attr = allAttributes.find(a => a.id === id);
        if (!attr) return;
        
        document.getElementById('editAttributeId').value = attr.id;
        document.getElementById('editAttributeDescription').value = attr.description || '';
        document.getElementById('editAttributeName').value = attr.attribute || '';
        document.getElementById('editAttributeType').value = attr.type || '';
        document.getElementById('editAttributeExpression').value = attr.expression || '';
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById('editAttributeModal')).show();
    };

    // Mettre à jour un attribut
    async function updateAttribute() {
        const id = document.getElementById('editAttributeId').value;
        const description = document.getElementById('editAttributeDescription').value;
        const attribute = document.getElementById('editAttributeName').value;
        const type = document.getElementById('editAttributeType').value;
        const expression = document.getElementById('editAttributeExpression').value;
        
        try {
            const response = await fetch(`/api/traccar/attributes/computed/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id,
                    description,
                    attribute,
                    type,
                    expression
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Attribut mis à jour avec succès');
                bootstrap.Modal.getInstance(document.getElementById('editAttributeModal')).hide();
                await loadAttributes();
            } else {
                alert('Erreur: ' + (data.message || 'Erreur lors de la mise à jour'));
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la mise à jour');
        }
    }

    // Supprimer un attribut
    window.deleteAttribute = function(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet attribut ?')) return;
        
        deleteAttributeConfirmed(id);
    };

    // Confirmer la suppression
    async function deleteAttributeConfirmed(id) {
        try {
            const response = await fetch(`/api/traccar/attributes/computed/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                alert('Attribut supprimé avec succès');
                await loadAttributes();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        }
    }

    // Supprimer les attributs sélectionnés
    async function deleteSelectedAttributes() {
        const selected = Array.from(document.querySelectorAll('.attribute-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            alert('Sélectionnez au moins un attribut');
            return;
        }
        
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selected.length} attribut(s) ?`)) return;
        
        for (const id of selected) {
            await deleteAttributeConfirmed(id);
        }
    }

    // Voir les détails
    window.viewAttributeDetails = function(id) {
        const attr = allAttributes.find(a => a.id === id);
        if (!attr) return;
        
        alert(`
ID: ${attr.id}
Description: ${attr.description}
Attribut: ${attr.attribute}
Type: ${attr.type}
Expression: ${attr.expression}
        `);
    };

    // Basculer la sélection de tous les attributs
    function toggleSelectAll() {
        const isChecked = document.getElementById('selectAll').checked;
        document.querySelectorAll('.attribute-checkbox').forEach(cb => cb.checked = isChecked);
    }

    // Afficher le chargement du tableau
    function showTableLoading() {
        document.getElementById('attributesTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="loading-cell">
                    <div class="table-loading">
                        <div class="spinner"></div>
                        <span>Chargement des attributs...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    // Afficher l'erreur
    function showTableError(message) {
        document.getElementById('attributesTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="error-cell">
                    <div class="error-state">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                        <p>${message}</p>
                        <button class="btn btn-primary btn-sm" onclick="location.reload()">Réessayer</button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Mettre à jour les infos du tableau
    function updateTableInfo(count) {
        document.getElementById('tableInfo').textContent = `Total: ${count} attribut(s)`;
    }

    // Event listeners
    document.getElementById('btnRefresh').addEventListener('click', loadAttributes);
    document.getElementById('searchAttribute').addEventListener('input', debounce(filterAttributes, 300));
    document.getElementById('filterType').addEventListener('change', filterAttributes);
    document.getElementById('btnSaveAttribute').addEventListener('click', createAttribute);
    document.getElementById('btnUpdateAttribute').addEventListener('click', updateAttribute);
    document.getElementById('btnDeleteSelected').addEventListener('click', deleteSelectedAttributes);
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);

    // Debounce
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Charger les attributs au démarrage
    loadAttributes();

    // Arrêter les mises à jour en quittant la page
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) clearInterval(refreshInterval);
    });
});
</script>
@endpush

@endsection
