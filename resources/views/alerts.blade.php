@extends('layouts.app')

@section('title', 'Alertes - Traccar TF')

@section('content')
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="sidebar-title">Alertes</h2>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-item active">Toutes les alertes</a>
            <a href="#" class="sidebar-item">Non lues</a>
            <a href="#" class="sidebar-item">Critiques</a>
            <a href="#" class="sidebar-item">Paramètres</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-exclamation-triangle"></i> Alertes & Notifications</h1>
            <div class="header-actions">
                <button class="btn btn-outline-primary" id="mark-all-read">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <!-- Statistiques des alertes -->
        <div class="alert-stats row mb-4">
            <div class="col-md-3">
                <div class="stat-card bg-danger text-white">
                    <div class="stat-number" id="critical-count">0</div>
                    <div class="stat-label">Critiques</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-warning text-dark">
                    <div class="stat-number" id="warning-count">0</div>
                    <div class="stat-label">Avertissements</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-info text-white">
                    <div class="stat-number" id="info-count">0</div>
                    <div class="stat-label">Informations</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-success text-white">
                    <div class="stat-number" id="resolved-count">0</div>
                    <div class="stat-label">Résolues</div>
                </div>
            </div>
        </div>

        <!-- Liste des alertes -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Alertes récentes</h3>
                <div class="filter-group">
                    <select class="form-control form-control-sm" id="alert-filter">
                        <option value="all">Toutes</option>
                        <option value="critical">Critiques</option>
                        <option value="warning">Avertissements</option>
                        <option value="info">Informations</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="alert-list" id="alerts-container">
                    <div class="alert-item alert-critical">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title">Exemple d'alerte critique</div>
                            <div class="alert-description">Description de l'alerte</div>
                            <div class="alert-meta">
                                <span><i class="fas fa-car"></i> Appareil: GPS001</span>
                                <span><i class="fas fa-clock"></i> Il y a 5 minutes</span>
                            </div>
                        </div>
                        <div class="alert-actions">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                    <div class="alert-item alert-warning">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title">Exemple d'avertissement</div>
                            <div class="alert-description">Description de l'avertissement</div>
                            <div class="alert-meta">
                                <span><i class="fas fa-car"></i> Appareil: GPS002</span>
                                <span><i class="fas fa-clock"></i> Il y a 15 minutes</span>
                            </div>
                        </div>
                        <div class="alert-actions">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.stat-card {
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}
.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
}
.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}
.alert-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.alert-item {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
    border-left: 4px solid #6c757d;
}
.alert-item.alert-critical {
    border-left-color: #dc3545;
    background: #fff5f5;
}
.alert-item.alert-warning {
    border-left-color: #ffc107;
    background: #fffdf5;
}
.alert-item.alert-info {
    border-left-color: #17a2b8;
    background: #f5fbff;
}
.alert-icon {
    font-size: 1.5rem;
    margin-right: 15px;
    width: 40px;
    text-align: center;
}
.alert-critical .alert-icon { color: #dc3545; }
.alert-warning .alert-icon { color: #ffc107; }
.alert-info .alert-icon { color: #17a2b8; }
.alert-content {
    flex: 1;
}
.alert-title {
    font-weight: 600;
    margin-bottom: 5px;
}
.alert-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 8px;
}
.alert-meta {
    display: flex;
    gap: 20px;
    font-size: 0.8rem;
    color: #6c757d;
}
.alert-actions {
    display: flex;
    gap: 5px;
}
</style>
@endsection
