@extends('layouts.app')

@section('title', 'Gestion de Flotte - Traccar TF')

@section('body-class', 'fleet-page')

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
        
        <div class="tree-view" id="fleetTree">
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
                    <i class="fas fa-truck me-2"></i>
                    Gestion de Flotte
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
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="totalVehicles">0</span>
                        <span class="stat-label">Total Véhicules</span>
                    </div>
                </div>
                <div class="stat-card stat-active">
                    <div class="stat-icon">
                        <i class="fas fa-road"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="activeVehicles">0</span>
                        <span class="stat-label">En Route</span>
                    </div>
                </div>
                <div class="stat-card stat-disabled">
                    <div class="stat-icon">
                        <i class="fas fa-parking"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="parkedVehicles">0</span>
                        <span class="stat-label">Stationnés</span>
                    </div>
                </div>
                <div class="stat-card stat-admin">
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="maintenanceVehicles">0</span>
                        <span class="stat-label">En Maintenance</span>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>Recherche</label>
                        <input type="text" id="searchVehicle" class="filter-input" placeholder="Immatriculation, marque...">
                    </div>
                    <div class="filter-group">
                        <label>Statut</label>
                        <select id="filterStatus" class="filter-select">
                            <option value="">Tous</option>
                            <option value="active">En Route</option>
                            <option value="parked">Stationné</option>
                            <option value="maintenance">En Maintenance</option>
                            <option value="offline">Hors ligne</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Type</label>
                        <select id="filterType" class="filter-select">
                            <option value="">Tous</option>
                            <option value="car">Voiture</option>
                            <option value="truck">Camion</option>
                            <option value="van">Camionnette</option>
                            <option value="motorcycle">Moto</option>
                            <option value="bus">Bus</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Groupe</label>
                        <select id="filterGroup" class="filter-select">
                            <option value="">Tous les groupes</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" id="btnAddVehicle" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                    <i class="fas fa-plus"></i>
                    Ajouter Véhicule
                </button>
                <button class="btn btn-success" id="btnRefresh">
                    <i class="fas fa-sync-alt"></i>
                    Rafraîchir
                </button>
                <button class="btn btn-info" id="btnScheduleMaintenance" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                    <i class="fas fa-wrench"></i>
                    Planifier Maintenance
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

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="fleetTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Immatriculation</th>
                            <th>Marque / Modèle</th>
                            <th>Type</th>
                            <th>Chauffeur</th>
                            <th>Groupe</th>
                            <th>Kilométrage</th>
                            <th>Carburant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="fleetTableBody">
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                                <p class="mt-2 text-muted">Chargement des véhicules...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Affichage de <span id="showingStart">0</span> à <span id="showingEnd">0</span> sur <span id="totalItems">0</span> véhicules
                </div>
                <nav aria-label="Pagination">
                    <ul class="pagination" id="pagination">
                    </ul>
                </nav>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajouter Véhicule -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ajouter un Véhicule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addVehicleForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addVehiclePlate" class="form-label">Immatriculation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addVehiclePlate" name="plate" placeholder="AB-123-CD">
                        </div>
                        <div class="col-md-6">
                            <label for="addVehicleType" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="addVehicleType" name="type">
                                <option value="car">Voiture</option>
                                <option value="truck">Camion</option>
                                <option value="van">Camionnette</option>
                                <option value="motorcycle">Moto</option>
                                <option value="bus">Bus</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addVehicleBrand" class="form-label">Marque</label>
                            <input type="text" class="form-control" id="addVehicleBrand" name="brand" placeholder="Ex: Toyota">
                        </div>
                        <div class="col-md-6">
                            <label for="addVehicleModel" class="form-label">Modèle</label>
                            <input type="text" class="form-control" id="addVehicleModel" name="model" placeholder="Ex: Hilux">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addVehicleYear" class="form-label">Année</label>
                            <input type="number" class="form-control" id="addVehicleYear" name="year" placeholder="2024" min="1990" max="2030">
                        </div>
                        <div class="col-md-6">
                            <label for="addVehicleColor" class="form-label">Couleur</label>
                            <input type="text" class="form-control" id="addVehicleColor" name="color" placeholder="Ex: Blanc">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addVehicleDevice" class="form-label">Device Traceur</label>
                            <select class="form-select" id="addVehicleDevice" name="deviceId">
                                <option value="">-- Sélectionner un device --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="addVehicleGroup" class="form-label">Groupe</label>
                            <select class="form-select" id="addVehicleGroup" name="groupId">
                                <option value="">-- Aucun groupe --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addVehicleDriver" class="form-label">Chauffeur assigné</label>
                            <select class="form-select" id="addVehicleDriver" name="driverId">
                                <option value="">-- Aucun chauffeur --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="addVehicleMileage" class="form-label">Kilométrage actuel</label>
                            <input type="number" class="form-control" id="addVehicleMileage" name="mileage" placeholder="0" min="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="addVehicleFuelType" class="form-label">Type de carburant</label>
                            <select class="form-select" id="addVehicleFuelType" name="fuelType">
                                <option value="diesel">Diesel</option>
                                <option value="petrol">Essence</option>
                                <option value="electric">Électrique</option>
                                <option value="hybrid">Hybride</option>
                                <option value="gas">GPL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="addVehicleTankCapacity" class="form-label">Capacité réservoir (L)</label>
                            <input type="number" class="form-control" id="addVehicleTankCapacity" name="tankCapacity" placeholder="60">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="addVehicleNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="addVehicleNotes" name="notes" rows="3" placeholder="Informations supplémentaires..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btnSaveVehicle">
                    <i class="fas fa-save me-1"></i>
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Véhicule -->
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">
                    <i class="fas fa-edit me-2"></i>
                    Modifier le Véhicule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVehicleForm">
                    <input type="hidden" id="editVehicleId" name="id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehiclePlate" class="form-label">Immatriculation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editVehiclePlate" name="plate" placeholder="AB-123-CD">
                        </div>
                        <div class="col-md-6">
                            <label for="editVehicleType" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="editVehicleType" name="type">
                                <option value="car">Voiture</option>
                                <option value="truck">Camion</option>
                                <option value="van">Camionnette</option>
                                <option value="motorcycle">Moto</option>
                                <option value="bus">Bus</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehicleBrand" class="form-label">Marque</label>
                            <input type="text" class="form-control" id="editVehicleBrand" name="brand">
                        </div>
                        <div class="col-md-6">
                            <label for="editVehicleModel" class="form-label">Modèle</label>
                            <input type="text" class="form-control" id="editVehicleModel" name="model">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehicleYear" class="form-label">Année</label>
                            <input type="number" class="form-control" id="editVehicleYear" name="year" min="1990" max="2030">
                        </div>
                        <div class="col-md-6">
                            <label for="editVehicleColor" class="form-label">Couleur</label>
                            <input type="text" class="form-control" id="editVehicleColor" name="color">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehicleDevice" class="form-label">Device Traceur</label>
                            <select class="form-select" id="editVehicleDevice" name="deviceId">
                                <option value="">-- Sélectionner un device --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editVehicleGroup" class="form-label">Groupe</label>
                            <select class="form-select" id="editVehicleGroup" name="groupId">
                                <option value="">-- Aucun groupe --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehicleDriver" class="form-label">Chauffeur assigné</label>
                            <select class="form-select" id="editVehicleDriver" name="driverId">
                                <option value="">-- Aucun chauffeur --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editVehicleMileage" class="form-label">Kilométrage actuel</label>
                            <input type="number" class="form-control" id="editVehicleMileage" name="mileage" min="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehicleFuelType" class="form-label">Type de carburant</label>
                            <select class="form-select" id="editVehicleFuelType" name="fuelType">
                                <option value="diesel">Diesel</option>
                                <option value="petrol">Essence</option>
                                <option value="electric">Électrique</option>
                                <option value="hybrid">Hybride</option>
                                <option value="gas">GPL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editVehicleTankCapacity" class="form-label">Capacité réservoir (L)</label>
                            <input type="number" class="form-control" id="editVehicleTankCapacity" name="tankCapacity">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editVehicleStatus" class="form-label">Statut</label>
                            <select class="form-select" id="editVehicleStatus" name="status">
                                <option value="active">En service</option>
                                <option value="parked">Stationné</option>
                                <option value="maintenance">En maintenance</option>
                                <option value="offline">Hors service</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editVehicleNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="editVehicleNotes" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btnUpdateVehicle">
                    <i class="fas fa-save me-1"></i>
                    Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Maintenance -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maintenanceModalLabel">
                    <i class="fas fa-wrench me-2"></i>
                    Planifier une Maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="maintenanceForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="maintenanceVehicle" class="form-label">Véhicule <span class="text-danger">*</span></label>
                            <select class="form-select" id="maintenanceVehicle" name="vehicleId">
                                <option value="">-- Sélectionner un véhicule --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="maintenanceType" class="form-label">Type de maintenance <span class="text-danger">*</span></label>
                            <select class="form-select" id="maintenanceType" name="type">
                                <option value="oil_change">Vidange</option>
                                <option value="tire_change">Changement de pneus</option>
                                <option value="brake_check">Vérification freins</option>
                                <option value="full_service">Révision complète</option>
                                <option value="repair">Réparation</option>
                                <option value="inspection">Contrôle technique</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="maintenanceDate" class="form-label">Date prévue <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="maintenanceDate" name="scheduledDate">
                        </div>
                        <div class="col-md-6">
                            <label for="maintenanceMileage" class="form-label">Kilométrage à la maintenance</label>
                            <input type="number" class="form-control" id="maintenanceMileage" name="mileage" placeholder="Optionnel">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="maintenanceCost" class="form-label">Coût estimé (€)</label>
                            <input type="number" class="form-control" id="maintenanceCost" name="estimatedCost" placeholder="0.00" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label for="maintenanceProvider" class="form-label">Prestataire</label>
                            <input type="text" class="form-control" id="maintenanceProvider" name="provider" placeholder="Nom du garage">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="maintenanceDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="maintenanceDescription" name="description" rows="3" placeholder="Détails de la maintenance..."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="maintenanceNotify" name="notify" checked>
                        <label class="form-check-label" for="maintenanceNotify">
                            Envoyer une notification de rappel
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btnSaveMaintenance">
                    <i class="fas fa-calendar-check me-1"></i>
                    Planifier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails Véhicule -->
<div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-labelledby="vehicleDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleDetailsModalLabel">
                    <i class="fas fa-car me-2"></i>
                    Détails du Véhicule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="vehicleDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-panel" type="button" role="tab">
                            <i class="fas fa-info-circle me-1"></i> Informations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-panel" type="button" role="tab">
                            <i class="fas fa-history me-1"></i> Historique
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance-panel" type="button" role="tab">
                            <i class="fas fa-tools me-1"></i> Maintenances
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fuel-tab" data-bs-toggle="tab" data-bs-target="#fuel-panel" type="button" role="tab">
                            <i class="fas fa-gas-pump me-1"></i> Carburant
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-3" id="vehicleDetailsTabContent">
                    <div class="tab-pane fade show active" id="info-panel" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Informations générales</h6>
                                <table class="table table-sm">
                                    <tr><td class="fw-bold">Immatriculation</td><td id="detailPlate">-</td></tr>
                                    <tr><td class="fw-bold">Marque</td><td id="detailBrand">-</td></tr>
                                    <tr><td class="fw-bold">Modèle</td><td id="detailModel">-</td></tr>
                                    <tr><td class="fw-bold">Année</td><td id="detailYear">-</td></tr>
                                    <tr><td class="fw-bold">Couleur</td><td id="detailColor">-</td></tr>
                                    <tr><td class="fw-bold">Type</td><td id="detailType">-</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Utilisation</h6>
                                <table class="table table-sm">
                                    <tr><td class="fw-bold">Kilométrage</td><td id="detailMileage">-</td></tr>
                                    <tr><td class="fw-bold">Chauffeur</td><td id="detailDriver">-</td></tr>
                                    <tr><td class="fw-bold">Groupe</td><td id="detailGroup">-</td></tr>
                                    <tr><td class="fw-bold">Statut</td><td id="detailStatus">-</td></tr>
                                    <tr><td class="fw-bold">Dernière position</td><td id="detailLastPosition">-</td></tr>
                                    <tr><td class="fw-bold">Dernière mise à jour</td><td id="detailLastUpdate">-</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="history-panel" role="tabpanel">
                        <div class="text-center py-4">
                            <i class="fas fa-route fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Historique des trajets à venir...</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="maintenance-panel" role="tabpanel">
                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Historique des maintenances à venir...</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="fuel-panel" role="tabpanel">
                        <div class="text-center py-4">
                            <i class="fas fa-gas-pump fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Consommation carburant à venir...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    console.log('Fleet management script loaded');
    
    let allVehicles = [];
    let allGroups = [];
    let allDevices = [];
    let allDrivers = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let refreshInterval = null;
    const REFRESH_RATE = 10000;

    // Initialisation
    document.addEventListener('DOMContentLoaded', async function() {
        await loadGroups();
        await loadDevices();
        await loadDrivers();
        await loadVehicles();
        startRealTimeUpdates();
    });

    // Event listeners
    document.getElementById('btnRefresh').addEventListener('click', loadVehicles);
    document.getElementById('searchVehicle').addEventListener('input', debounce(filterVehicles, 300));
    document.getElementById('filterStatus').addEventListener('change', filterVehicles);
    document.getElementById('filterType').addEventListener('change', filterVehicles);
    document.getElementById('filterGroup').addEventListener('change', filterVehicles);
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);
    document.getElementById('treeSearch').addEventListener('input', debounce(filterTree, 300));

    // Charger les véhicules (basé sur les devices)
    async function loadVehicles() {
        try {
            showTableLoading();
            const response = await fetch('/api/traccar/devices?all=true');
            const data = await response.json();
            
            if (data.success) {
                // Transformer les devices en véhicules avec infos supplémentaires
                allVehicles = (data.devices || []).map(device => ({
                    id: device.id,
                    plate: device.name,
                    brand: device.attributes?.brand || '-',
                    model: device.attributes?.model || device.model || '-',
                    type: device.category || 'car',
                    driver: device.attributes?.driver || '-',
                    groupId: device.groupId,
                    groupName: getGroupName(device.groupId),
                    mileage: device.attributes?.totalDistance ? Math.round(device.attributes.totalDistance / 1000) : 0,
                    fuelLevel: device.attributes?.fuel || '-',
                    status: getVehicleStatus(device),
                    lastUpdate: device.lastUpdate,
                    position: device.position
                }));
                
                updateStats();
                filterVehicles();
                buildTreeView();
            } else {
                showTableError('Erreur lors du chargement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showTableError('Erreur de connexion');
        }
    }

    // Charger les groupes
    async function loadGroups() {
        try {
            const response = await fetch('/api/traccar/groups?all=true');
            const data = await response.json();
            if (data.success) {
                allGroups = data.groups || [];
                populateGroupSelects();
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
                populateDeviceSelects();
            }
        } catch (error) {
            console.error('Erreur chargement devices:', error);
        }
    }

    // Charger les chauffeurs
    async function loadDrivers() {
        try {
            const response = await fetch('/api/traccar/drivers?all=true');
            const data = await response.json();
            if (data.success) {
                allDrivers = data.drivers || [];
                populateDriverSelects();
            }
        } catch (error) {
            console.error('Erreur chargement chauffeurs:', error);
            allDrivers = [];
        }
    }

    function getGroupName(groupId) {
        if (!groupId) return '-';
        const group = allGroups.find(g => g.id === groupId);
        return group ? group.name : '-';
    }

    function getVehicleStatus(device) {
        if (!device.status || device.status === 'offline') return 'offline';
        if (device.attributes?.motion) return 'active';
        return 'parked';
    }

    function updateStats() {
        document.getElementById('totalVehicles').textContent = allVehicles.length;
        document.getElementById('activeVehicles').textContent = allVehicles.filter(v => v.status === 'active').length;
        document.getElementById('parkedVehicles').textContent = allVehicles.filter(v => v.status === 'parked').length;
        document.getElementById('maintenanceVehicles').textContent = allVehicles.filter(v => v.status === 'maintenance').length;
    }

    function filterVehicles() {
        const searchTerm = document.getElementById('searchVehicle').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        const typeFilter = document.getElementById('filterType').value;
        const groupFilter = document.getElementById('filterGroup').value;

        let filtered = allVehicles.filter(vehicle => {
            const matchSearch = !searchTerm || 
                vehicle.plate.toLowerCase().includes(searchTerm) ||
                vehicle.brand.toLowerCase().includes(searchTerm) ||
                vehicle.model.toLowerCase().includes(searchTerm);
            
            const matchStatus = !statusFilter || vehicle.status === statusFilter;
            const matchType = !typeFilter || vehicle.type === typeFilter;
            const matchGroup = !groupFilter || vehicle.groupId == groupFilter;

            return matchSearch && matchStatus && matchType && matchGroup;
        });

        renderTable(filtered);
    }

    function renderTable(vehicles) {
        const tbody = document.getElementById('fleetTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedVehicles = vehicles.slice(start, end);

        if (paginatedVehicles.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun véhicule trouvé</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = paginatedVehicles.map(vehicle => `
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input vehicle-checkbox" data-id="${vehicle.id}">
                </td>
                <td>
                    <span class="fw-bold">${escapeHtml(vehicle.plate)}</span>
                </td>
                <td>${escapeHtml(vehicle.brand)} ${escapeHtml(vehicle.model)}</td>
                <td>
                    <span class="badge bg-secondary">
                        <i class="${getTypeIcon(vehicle.type)} me-1"></i>
                        ${getTypeName(vehicle.type)}
                    </span>
                </td>
                <td>${escapeHtml(vehicle.driver)}</td>
                <td>${escapeHtml(vehicle.groupName)}</td>
                <td>${vehicle.mileage.toLocaleString()} km</td>
                <td>${vehicle.fuelLevel !== '-' ? vehicle.fuelLevel + '%' : '-'}</td>
                <td>
                    <span class="badge ${getStatusBadgeClass(vehicle.status)}">
                        <i class="${getStatusIcon(vehicle.status)} me-1"></i>
                        ${getStatusName(vehicle.status)}
                    </span>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" onclick="viewVehicle(${vehicle.id})" title="Détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-primary" onclick="editVehicle(${vehicle.id})" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-warning" onclick="scheduleMaintenanceFor(${vehicle.id})" title="Maintenance">
                            <i class="fas fa-wrench"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="deleteVehicle(${vehicle.id})" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        updatePagination(vehicles.length);
    }

    function getTypeIcon(type) {
        const icons = {
            car: 'fas fa-car',
            truck: 'fas fa-truck',
            van: 'fas fa-shuttle-van',
            motorcycle: 'fas fa-motorcycle',
            bus: 'fas fa-bus'
        };
        return icons[type] || 'fas fa-car';
    }

    function getTypeName(type) {
        const names = {
            car: 'Voiture',
            truck: 'Camion',
            van: 'Camionnette',
            motorcycle: 'Moto',
            bus: 'Bus'
        };
        return names[type] || type;
    }

    function getStatusBadgeClass(status) {
        const classes = {
            active: 'bg-success',
            parked: 'bg-warning text-dark',
            maintenance: 'bg-info',
            offline: 'bg-secondary'
        };
        return classes[status] || 'bg-secondary';
    }

    function getStatusIcon(status) {
        const icons = {
            active: 'fas fa-road',
            parked: 'fas fa-parking',
            maintenance: 'fas fa-tools',
            offline: 'fas fa-power-off'
        };
        return icons[status] || 'fas fa-question';
    }

    function getStatusName(status) {
        const names = {
            active: 'En route',
            parked: 'Stationné',
            maintenance: 'Maintenance',
            offline: 'Hors ligne'
        };
        return names[status] || status;
    }

    function showTableLoading() {
        document.getElementById('fleetTableBody').innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement des véhicules...</p>
                </td>
            </tr>
        `;
    }

    function showTableError(message) {
        document.getElementById('fleetTableBody').innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger">${message}</p>
                    <button class="btn btn-primary btn-sm" onclick="loadVehicles()">Réessayer</button>
                </td>
            </tr>
        `;
    }

    function updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, totalItems);

        document.getElementById('showingStart').textContent = totalItems > 0 ? start : 0;
        document.getElementById('showingEnd').textContent = end;
        document.getElementById('totalItems').textContent = totalItems;

        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        if (totalPages <= 1) return;

        // Bouton précédent
        pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage - 1})">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;

        // Pages
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                pagination.innerHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
                    </li>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Bouton suivant
        pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage + 1})">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
    }

    function goToPage(page) {
        currentPage = page;
        filterVehicles();
    }

    function populateGroupSelects() {
        const selects = ['filterGroup', 'addVehicleGroup', 'editVehicleGroup'];
        selects.forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                const firstOption = select.options[0];
                select.innerHTML = '';
                select.appendChild(firstOption);
                allGroups.forEach(group => {
                    const option = document.createElement('option');
                    option.value = group.id;
                    option.textContent = group.name;
                    select.appendChild(option);
                });
            }
        });
    }

    function populateDeviceSelects() {
        const selects = ['addVehicleDevice', 'editVehicleDevice'];
        selects.forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                const firstOption = select.options[0];
                select.innerHTML = '';
                select.appendChild(firstOption);
                allDevices.forEach(device => {
                    const option = document.createElement('option');
                    option.value = device.id;
                    option.textContent = device.name;
                    select.appendChild(option);
                });
            }
        });
    }

    function populateDriverSelects() {
        const selects = ['addVehicleDriver', 'editVehicleDriver'];
        selects.forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                const firstOption = select.options[0];
                select.innerHTML = '';
                select.appendChild(firstOption);
                allDrivers.forEach(driver => {
                    const option = document.createElement('option');
                    option.value = driver.id;
                    option.textContent = driver.name;
                    select.appendChild(option);
                });
            }
        });
    }

    function buildTreeView() {
        const treeContainer = document.getElementById('fleetTree');
        
        const groupedVehicles = {};
        allVehicles.forEach(vehicle => {
            const groupName = vehicle.groupName || 'Sans groupe';
            if (!groupedVehicles[groupName]) {
                groupedVehicles[groupName] = [];
            }
            groupedVehicles[groupName].push(vehicle);
        });

        let html = '<ul class="tree-list">';
        
        Object.keys(groupedVehicles).sort().forEach(groupName => {
            const vehicles = groupedVehicles[groupName];
            html += `
                <li class="tree-item tree-group">
                    <div class="tree-node" onclick="toggleTreeNode(this)">
                        <i class="fas fa-chevron-right tree-toggle"></i>
                        <i class="fas fa-folder tree-icon"></i>
                        <span class="tree-label">${escapeHtml(groupName)}</span>
                        <span class="tree-count">${vehicles.length}</span>
                    </div>
                    <ul class="tree-children">
                        ${vehicles.map(v => `
                            <li class="tree-item tree-device" onclick="selectVehicle(${v.id})">
                                <i class="${getTypeIcon(v.type)} tree-icon"></i>
                                <span class="tree-label">${escapeHtml(v.plate)}</span>
                                <span class="tree-status status-${v.status}"></span>
                            </li>
                        `).join('')}
                    </ul>
                </li>
            `;
        });
        
        html += '</ul>';
        treeContainer.innerHTML = html;
    }

    function toggleTreeNode(node) {
        const parent = node.parentElement;
        parent.classList.toggle('expanded');
    }

    function selectVehicle(id) {
        document.querySelectorAll('.tree-device').forEach(el => el.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        viewVehicle(id);
    }

    function viewVehicle(id) {
        const vehicle = allVehicles.find(v => v.id === id);
        if (!vehicle) return;

        document.getElementById('detailPlate').textContent = vehicle.plate;
        document.getElementById('detailBrand').textContent = vehicle.brand;
        document.getElementById('detailModel').textContent = vehicle.model;
        document.getElementById('detailYear').textContent = '-';
        document.getElementById('detailColor').textContent = '-';
        document.getElementById('detailType').textContent = getTypeName(vehicle.type);
        document.getElementById('detailMileage').textContent = vehicle.mileage.toLocaleString() + ' km';
        document.getElementById('detailDriver').textContent = vehicle.driver;
        document.getElementById('detailGroup').textContent = vehicle.groupName;
        document.getElementById('detailStatus').innerHTML = `<span class="badge ${getStatusBadgeClass(vehicle.status)}">${getStatusName(vehicle.status)}</span>`;
        document.getElementById('detailLastUpdate').textContent = vehicle.lastUpdate ? new Date(vehicle.lastUpdate).toLocaleString() : '-';

        const modal = new bootstrap.Modal(document.getElementById('vehicleDetailsModal'));
        modal.show();
    }

    function editVehicle(id) {
        const vehicle = allVehicles.find(v => v.id === id);
        if (!vehicle) return;

        document.getElementById('editVehicleId').value = vehicle.id;
        document.getElementById('editVehiclePlate').value = vehicle.plate;
        document.getElementById('editVehicleType').value = vehicle.type;
        document.getElementById('editVehicleBrand').value = vehicle.brand !== '-' ? vehicle.brand : '';
        document.getElementById('editVehicleModel').value = vehicle.model !== '-' ? vehicle.model : '';
        document.getElementById('editVehicleMileage').value = vehicle.mileage;
        document.getElementById('editVehicleStatus').value = vehicle.status;

        const modal = new bootstrap.Modal(document.getElementById('editVehicleModal'));
        modal.show();
    }

    function scheduleMaintenanceFor(id) {
        const select = document.getElementById('maintenanceVehicle');
        select.innerHTML = '<option value="">-- Sélectionner un véhicule --</option>';
        allVehicles.forEach(v => {
            const option = document.createElement('option');
            option.value = v.id;
            option.textContent = v.plate;
            if (v.id === id) option.selected = true;
            select.appendChild(option);
        });

        const modal = new bootstrap.Modal(document.getElementById('maintenanceModal'));
        modal.show();
    }

    async function deleteVehicle(id) {
        const confirmed = await showDeleteConfirm('ce véhicule');
        if (confirmed) {
            // API call pour supprimer
            console.log('Delete vehicle:', id);
        }
    }

    function toggleSelectAll(e) {
        const checkboxes = document.querySelectorAll('.vehicle-checkbox');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    }

    function filterTree() {
        const searchTerm = document.getElementById('treeSearch').value.toLowerCase();
        const items = document.querySelectorAll('.tree-device');
        
        items.forEach(item => {
            const label = item.querySelector('.tree-label').textContent.toLowerCase();
            item.style.display = label.includes(searchTerm) ? '' : 'none';
        });
    }

    function startRealTimeUpdates() {
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(loadVehicles, REFRESH_RATE);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush

@push('styles')
<style>
/* ========================================
   FLEET PAGE - MODERN DESIGN
   ======================================== */

/* Main Container */
.fleet-page .main-container {
    display: flex;
    min-height: calc(100vh - 70px);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Sidebar Styles */
.fleet-page .device-sidebar {
    width: 280px;
    background: #ffffff;
    border-right: 1px solid #e2e8f0;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.03);
}

.fleet-page .sidebar-search {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.fleet-page .search-input-wrapper {
    position: relative;
}

.fleet-page .search-input-wrapper .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
}

.fleet-page .search-input {
    width: 100%;
    padding: 12px 16px 12px 42px;
    border: none;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    font-size: 14px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.fleet-page .search-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.fleet-page .search-input:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.fleet-page .tree-view {
    padding: 15px;
    max-height: calc(100vh - 180px);
    overflow-y: auto;
}

/* Tree View Styles */
.tree-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tree-item {
    padding: 2px 0;
}

.tree-node {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
    margin-bottom: 4px;
    background: #f8fafc;
    border: 1px solid transparent;
}

.tree-node:hover {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%);
    border-color: #c7d2fe;
    transform: translateX(4px);
}

.tree-toggle {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 10px;
    color: #667eea;
    background: #eef2ff;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.tree-group.expanded > .tree-node .tree-toggle {
    transform: rotate(90deg);
    background: #667eea;
    color: #fff;
}

.tree-children {
    display: none;
    padding-left: 20px;
    margin-top: 4px;
}

.tree-group.expanded > .tree-children {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tree-icon {
    margin-right: 10px;
    color: #667eea;
    font-size: 16px;
}

.tree-label {
    flex: 1;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.tree-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
}

.tree-device {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    cursor: pointer;
    border-radius: 10px;
    transition: all 0.3s ease;
    margin-bottom: 4px;
    background: #fff;
    border: 1px solid #e5e7eb;
}

.tree-device:hover {
    background: linear-gradient(135deg, #fef3f2 0%, #fef9c3 100%);
    border-color: #fbbf24;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.15);
}

.tree-device.selected {
    background: linear-gradient(135deg, #dbeafe 0%, #c7d2fe 100%);
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.tree-device .tree-icon {
    color: #f59e0b;
}

.tree-status {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-left: 10px;
    box-shadow: 0 0 8px currentColor;
}

.tree-status.status-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #10b981;
}

.tree-status.status-parked {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #f59e0b;
}

.tree-status.status-maintenance {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #3b82f6;
}

.tree-status.status-offline {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: #6b7280;
}

.tree-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #6b7280;
}

.spinner-small {
    width: 40px;
    height: 40px;
    border: 3px solid #e5e7eb;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Main Content */
.fleet-page .main-content {
    flex: 1;
    padding: 24px;
    overflow-y: auto;
}

.fleet-page .content-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    padding: 0;
    overflow: hidden;
}

.fleet-page .card-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.fleet-page .card-header-custom h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.fleet-page .card-header-custom h3 i {
    margin-right: 12px;
    font-size: 1.3rem;
}

.fleet-page .realtime-indicator {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.fleet-page .realtime-dot {
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    margin-right: 8px;
    animation: pulse 2s infinite;
    box-shadow: 0 0 10px #10b981;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.2); }
}

.fleet-page .realtime-text {
    font-size: 13px;
    font-weight: 500;
}

/* Stats Cards */
.fleet-page .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 24px 30px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.fleet-page .stat-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.fleet-page .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.fleet-page .stat-card .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-right: 16px;
}

.fleet-page .stat-card.stat-total .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.fleet-page .stat-card.stat-active .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
}

.fleet-page .stat-card.stat-disabled .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
}

.fleet-page .stat-card.stat-admin .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
}

.fleet-page .stat-card .stat-info {
    display: flex;
    flex-direction: column;
}

.fleet-page .stat-card .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.fleet-page .stat-card .stat-label {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

/* Filters Section */
.fleet-page .filters-section {
    padding: 20px 30px;
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
}

.fleet-page .filters-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.fleet-page .filter-group {
    display: flex;
    flex-direction: column;
}

.fleet-page .filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fleet-page .filter-input,
.fleet-page .filter-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    color: #374151;
    background: #fff;
    transition: all 0.3s ease;
}

.fleet-page .filter-input:focus,
.fleet-page .filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.fleet-page .filter-input::placeholder {
    color: #9ca3af;
}

/* Action Buttons */
.fleet-page .action-buttons {
    display: flex;
    gap: 12px;
    padding: 20px 30px;
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
}

.fleet-page .action-buttons .btn {
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
    transition: all 0.3s ease;
}

.fleet-page .action-buttons .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.fleet-page .action-buttons .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.fleet-page .action-buttons .btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.fleet-page .action-buttons .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.fleet-page .action-buttons .btn-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.fleet-page .action-buttons .btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

.fleet-page .action-buttons .btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.fleet-page .action-buttons .btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.fleet-page .action-buttons .btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.fleet-page .action-buttons .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* Table Styles */
.fleet-page .table-responsive {
    padding: 0 30px 20px;
}

.fleet-page .table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0 8px;
}

.fleet-page .table thead th {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border: none;
    padding: 16px 20px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.fleet-page .table thead th:first-child {
    border-radius: 12px 0 0 12px;
}

.fleet-page .table thead th:last-child {
    border-radius: 0 12px 12px 0;
}

.fleet-page .table tbody tr {
    background: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.fleet-page .table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.fleet-page .table tbody td {
    padding: 16px 20px;
    border: none;
    vertical-align: middle;
    color: #374151;
    font-size: 14px;
}

.fleet-page .table tbody td:first-child {
    border-radius: 12px 0 0 12px;
}

.fleet-page .table tbody td:last-child {
    border-radius: 0 12px 12px 0;
}

.fleet-page .table .badge {
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.fleet-page .table .badge.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.fleet-page .table .badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: #fff !important;
}

.fleet-page .table .badge.bg-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

.fleet-page .table .badge.bg-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
}

/* Button Group Actions */
.fleet-page .btn-group-sm .btn {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px !important;
    margin: 0 3px;
    transition: all 0.3s ease;
}

.fleet-page .btn-group-sm .btn-outline-info {
    color: #3b82f6;
    border-color: #3b82f6;
}

.fleet-page .btn-group-sm .btn-outline-info:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.fleet-page .btn-group-sm .btn-outline-primary {
    color: #667eea;
    border-color: #667eea;
}

.fleet-page .btn-group-sm .btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.fleet-page .btn-group-sm .btn-outline-warning {
    color: #f59e0b;
    border-color: #f59e0b;
}

.fleet-page .btn-group-sm .btn-outline-warning:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.fleet-page .btn-group-sm .btn-outline-danger {
    color: #ef4444;
    border-color: #ef4444;
}

.fleet-page .btn-group-sm .btn-outline-danger:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Pagination */
.fleet-page .pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: #f8fafc;
    border-radius: 0 0 20px 20px;
}

.fleet-page .pagination-info {
    color: #6b7280;
    font-size: 14px;
}

.fleet-page .pagination {
    margin: 0;
    gap: 6px;
}

.fleet-page .pagination .page-item .page-link {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 2px solid #e5e7eb;
    color: #374151;
    font-weight: 600;
    transition: all 0.3s ease;
}

.fleet-page .pagination .page-item .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: #fff;
    transform: translateY(-2px);
}

.fleet-page .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.fleet-page .pagination .page-item.disabled .page-link {
    background: #f3f4f6;
    border-color: #e5e7eb;
    color: #9ca3af;
}

/* Modal Styles */
.fleet-page .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.fleet-page .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 20px 24px;
    border: none;
}

.fleet-page .modal-header .modal-title {
    font-weight: 600;
    display: flex;
    align-items: center;
}

.fleet-page .modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.fleet-page .modal-header .btn-close:hover {
    opacity: 1;
}

.fleet-page .modal-body {
    padding: 24px;
}

.fleet-page .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    background: #f8fafc;
}

.fleet-page .modal-footer .btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
}

.fleet-page .form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.fleet-page .form-control,
.fleet-page .form-select {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.fleet-page .form-control:focus,
.fleet-page .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

/* Nav Tabs in Modal */
.fleet-page .nav-tabs {
    border: none;
    background: #f8fafc;
    border-radius: 12px;
    padding: 6px;
    margin-bottom: 20px;
}

.fleet-page .nav-tabs .nav-link {
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    color: #6b7280;
    font-weight: 600;
    transition: all 0.3s ease;
}

.fleet-page .nav-tabs .nav-link:hover {
    color: #667eea;
    background: #fff;
}

.fleet-page .nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .fleet-page .stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .fleet-page .filters-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .fleet-page .device-sidebar {
        position: fixed;
        left: -280px;
        top: 70px;
        height: calc(100vh - 70px);
        z-index: 1000;
        transition: left 0.3s ease;
    }
    
    .fleet-page .device-sidebar.show {
        left: 0;
    }
    
    .fleet-page .main-content {
        padding: 16px;
    }
}

@media (max-width: 768px) {
    .fleet-page .stats-row {
        grid-template-columns: 1fr;
    }
    
    .fleet-page .filters-row {
        grid-template-columns: 1fr;
    }
    
    .fleet-page .action-buttons {
        flex-direction: column;
    }
    
    .fleet-page .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
    
    .fleet-page .pagination-container {
        flex-direction: column;
        gap: 16px;
    }
}
</style>
@endpush
