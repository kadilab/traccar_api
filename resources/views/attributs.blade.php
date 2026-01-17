@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-sliders-h me-2"></i>
                Attributs Personnalisés
            </h1>
            <button class="btn btn-primary" id="btnAddAttribute" onclick="openAddAttributeModal()">
                <i class="fas fa-plus me-1"></i>
                Nouvel Attribut
            </button>
        </div>
        <p class="text-muted">Gérez les attributs personnalisés pour vos devices</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stats-content">
                    <h6>Total</h6>
                    <h3 id="statTotal">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <h6>Actifs</h6>
                    <h3 id="statActive">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon secondary">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stats-content">
                    <h6>Numériques</h6>
                    <h3 id="statNumeric">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-font"></i>
                </div>
                <div class="stats-content">
                    <h6>Texte</h6>
                    <h3 id="statText">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-filter-bar mb-4">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchAttributes" placeholder="Rechercher un attribut..." onkeyup="filterAttributes()">
        </div>
    </div>

    <!-- Attributes Table -->
    <div class="table-container card">
        <table class="data-table" id="attributesTable">
            <thead>
                <tr>
                    <th class="th-checkbox">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Expression</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="attributesTableBody">
                <tr class="empty-state">
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        <i class="fas fa-inbox fa-2x text-muted mb-3" style="display: block; margin-bottom: 10px;"></i>
                        <p class="text-muted">Aucun attribut trouvé</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Attribute Modal -->
<div class="modal fade" id="attributeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attributeModalTitle">Nouvel Attribut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="attributeForm">
                    <div class="mb-3">
                        <label for="attributeName" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="attributeName" required placeholder="Ex: temperatureExterieure">
                    </div>

                    <div class="mb-3">
                        <label for="attributeDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="attributeDescription" rows="2" placeholder="Description de l'attribut"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="attributeType" class="form-label">Type *</label>
                        <select class="form-select" id="attributeType" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="number">Numérique</option>
                            <option value="string">Texte</option>
                            <option value="boolean">Booléen</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="attributeExpression" class="form-label">Expression *</label>
                        <textarea class="form-control" id="attributeExpression" rows="3" required placeholder="Ex: customAttributes.temp"></textarea>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Exemples: customAttributes.temp, deviceAttributes.battery, position.altitude
                        </small>
                    </div>

                    <div id="attributeFormError" class="alert alert-danger d-none" role="alert"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Annuler
                </button>
                <button type="button" class="btn btn-primary" id="btnSaveAttribute" onclick="saveAttribute()">
                    <i class="fas fa-save me-1"></i>
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.page-header {
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 20px;
}

.page-header h1 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 5px;
}

.page-header .text-muted {
    margin: 0;
}

/* Statistics Cards */
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stats-icon.warning {
    background: rgba(255, 193, 7, 0.15);
    color: #ffc107;
}

.stats-icon.primary {
    background: rgba(117, 86, 214, 0.15);
    color: #7556D6;
}

.stats-icon.secondary {
    background: rgba(108, 117, 125, 0.15);
    color: #6c757d;
}

.stats-icon.info {
    background: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
}

.stats-content h6 {
    color: #6c757d;
    font-size: 0.875rem;
    margin: 0 0 5px 0;
    text-transform: uppercase;
    font-weight: 600;
}

.stats-content h3 {
    color: #2d3748;
    font-size: 1.75rem;
    margin: 0;
    font-weight: 700;
}

/* Search Filter Bar */
.search-filter-bar {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-box {
    flex: 1;
    position: relative;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.search-box input {
    width: 100%;
    padding: 10px 12px 10px 38px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 0.9rem;
}

.search-box input:focus {
    outline: none;
    border-color: #7556D6;
    box-shadow: 0 0 0 3px rgba(117, 86, 214, 0.1);
}

/* Table */
.table-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.data-table th {
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.875rem;
    text-transform: uppercase;
}

.data-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
    color: #555;
    font-size: 0.9rem;
}

.data-table tbody tr:hover {
    background: #f8f9fa;
}

.data-table tbody tr.empty-state:hover {
    background: transparent;
}

.th-checkbox {
    width: 50px;
}

.data-table input[type="checkbox"] {
    cursor: pointer;
}

/* Type Badge */
.type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.type-badge.number {
    background: rgba(52, 211, 153, 0.15);
    color: #059669;
}

.type-badge.string {
    background: rgba(96, 165, 250, 0.15);
    color: #0284c7;
}

.type-badge.boolean {
    background: rgba(251, 146, 60, 0.15);
    color: #d97706;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 5px;
}

.action-btn {
    padding: 6px 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.action-btn.edit {
    background: rgba(52, 211, 153, 0.15);
    color: #059669;
}

.action-btn.edit:hover {
    background: rgba(52, 211, 153, 0.25);
}

.action-btn.delete {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.action-btn.delete:hover {
    background: rgba(239, 68, 68, 0.25);
}
</style>
@endpush

@push('scripts')
<script>
let currentAttributeId = null;
let allAttributes = [];

document.addEventListener('DOMContentLoaded', function() {
    console.log('Attributs page loaded');
    loadAttributes();
    
    document.getElementById('selectAll').addEventListener('change', function(e) {
        document.querySelectorAll('.attribute-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
    });
});

// Load attributes from Traccar
function loadAttributes() {
    console.log('Loading attributes...');
    fetch('/api/traccar/attributes')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allAttributes = data.attributes || [];
                console.log('Attributes loaded:', allAttributes);
                renderAttributes();
                updateStats();
            } else {
                showError('Erreur lors du chargement des attributs');
            }
        })
        .catch(error => {
            console.error('Error loading attributes:', error);
            showError('Erreur de connexion');
        });
}

// Render attributes in table
function renderAttributes() {
    const tbody = document.getElementById('attributesTableBody');
    
    if (allAttributes.length === 0) {
        tbody.innerHTML = `
            <tr class="empty-state">
                <td colspan="6" style="text-align: center; padding: 40px;">
                    <i class="fas fa-inbox fa-2x text-muted mb-3" style="display: block; margin-bottom: 10px;"></i>
                    <p class="text-muted">Aucun attribut trouvé</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = allAttributes.map(attr => `
        <tr>
            <td class="th-checkbox">
                <input type="checkbox" class="attribute-checkbox" value="${attr.id}">
            </td>
            <td>
                <strong>${escapeHtml(attr.name)}</strong>
            </td>
            <td>${escapeHtml(attr.description || '-')}</td>
            <td>
                <span class="type-badge ${attr.type || 'string'}">
                    ${getTypeLabel(attr.type)}
                </span>
            </td>
            <td>
                <code style="background: #f0f0f0; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem;">
                    ${escapeHtml(attr.expression || '-')}
                </code>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" onclick="editAttribute(${attr.id})" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteAttribute(${attr.id})" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Open add attribute modal
function openAddAttributeModal() {
    currentAttributeId = null;
    document.getElementById('attributeForm').reset();
    document.getElementById('attributeModalTitle').textContent = 'Nouvel Attribut';
    document.getElementById('attributeFormError').classList.add('d-none');
    const modal = new bootstrap.Modal(document.getElementById('attributeModal'));
    modal.show();
}

// Edit attribute
function editAttribute(id) {
    const attr = allAttributes.find(a => a.id === id);
    if (!attr) return;
    
    currentAttributeId = id;
    document.getElementById('attributeName').value = attr.name;
    document.getElementById('attributeDescription').value = attr.description || '';
    document.getElementById('attributeType').value = attr.type || 'string';
    document.getElementById('attributeExpression').value = attr.expression || '';
    document.getElementById('attributeModalTitle').textContent = 'Modifier Attribut';
    document.getElementById('attributeFormError').classList.add('d-none');
    
    const modal = new bootstrap.Modal(document.getElementById('attributeModal'));
    modal.show();
}

// Save attribute
function saveAttribute() {
    const name = document.getElementById('attributeName').value.trim();
    const description = document.getElementById('attributeDescription').value.trim();
    const type = document.getElementById('attributeType').value;
    const expression = document.getElementById('attributeExpression').value.trim();
    
    if (!name || !type || !expression) {
        showModalError('Veuillez remplir tous les champs obligatoires');
        return;
    }
    
    const payload = {
        name,
        description,
        type,
        expression
    };
    
    const url = currentAttributeId 
        ? `/api/traccar/attributes/computed/${currentAttributeId}`
        : '/api/traccar/attributes/computed';
    
    const method = currentAttributeId ? 'PUT' : 'POST';
    
    document.getElementById('btnSaveAttribute').disabled = true;
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('attributeModal')).hide();
            loadAttributes();
            showSuccess(currentAttributeId ? 'Attribut modifié' : 'Attribut créé');
        } else {
            showModalError(data.message || 'Erreur lors de l\'enregistrement');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModalError('Erreur de connexion');
    })
    .finally(() => {
        document.getElementById('btnSaveAttribute').disabled = false;
    });
}

// Delete attribute
function deleteAttribute(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet attribut ?')) return;
    
    fetch(`/api/traccar/attributes/computed/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadAttributes();
            showSuccess('Attribut supprimé');
        } else {
            showError(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erreur de connexion');
    });
}

// Filter attributes
function filterAttributes() {
    const searchTerm = document.getElementById('searchAttributes').value.toLowerCase();
    const filtered = allAttributes.filter(attr => 
        attr.name.toLowerCase().includes(searchTerm) ||
        (attr.description && attr.description.toLowerCase().includes(searchTerm))
    );
    
    allAttributes = filtered;
    renderAttributes();
}

// Update statistics
function updateStats() {
    const total = allAttributes.length;
    const numeric = allAttributes.filter(a => a.type === 'number').length;
    const text = allAttributes.filter(a => a.type === 'string').length;
    
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statActive').textContent = total;
    document.getElementById('statNumeric').textContent = numeric;
    document.getElementById('statText').textContent = text;
}

// Helper functions
function getTypeLabel(type) {
    const labels = {
        'number': 'Numérique',
        'string': 'Texte',
        'boolean': 'Booléen'
    };
    return labels[type] || type;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.table-container'));
    setTimeout(() => alertDiv.remove(), 5000);
}

function showSuccess(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.table-container'));
    setTimeout(() => alertDiv.remove(), 5000);
}

function showModalError(message) {
    const errorDiv = document.getElementById('attributeFormError');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
}
</script>
@endpush
