@extends('layouts.app')

@section('title', 'Points d\'intérêt - Traccar TF')

@section('content')
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="sidebar-title">POI</h2>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-item active">Tous les POI</a>
            <a href="#" class="sidebar-item">Mes POI</a>
            <a href="#" class="sidebar-item">Catégories</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-star"></i> Points d'intérêt</h1>
            <div class="header-actions">
                <button class="btn btn-primary" id="add-poi-btn">
                    <i class="fas fa-plus"></i> Nouveau POI
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Liste des POI</h3>
                    </div>
                    <div class="card-body poi-list">
                        <div class="poi-item">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <div class="poi-info">
                                <strong>Exemple POI 1</strong>
                                <small>Lat: 48.8566, Lng: 2.3522</small>
                            </div>
                        </div>
                        <div class="poi-item">
                            <i class="fas fa-map-marker-alt text-success"></i>
                            <div class="poi-info">
                                <strong>Exemple POI 2</strong>
                                <small>Lat: 48.8584, Lng: 2.2945</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Carte</h3>
                    </div>
                    <div class="card-body">
                        <div id="poi-map" style="height: 500px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                            <p class="text-muted">Carte des points d'intérêt</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.poi-list {
    max-height: 400px;
    overflow-y: auto;
}
.poi-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.2s;
}
.poi-item:hover {
    background: #f8f9fa;
}
.poi-item i {
    font-size: 1.5em;
    margin-right: 15px;
}
.poi-info {
    display: flex;
    flex-direction: column;
}
.poi-info small {
    color: #6c757d;
}
</style>
@endsection
