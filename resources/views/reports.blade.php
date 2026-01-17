@extends('layouts.app')

@section('title', 'Rapports - Traccar TF')

@section('content')
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="sidebar-title">Rapports</h2>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-item active">Rapport de route</a>
            <a href="#" class="sidebar-item">Rapport d'événements</a>
            <a href="#" class="sidebar-item">Rapport de voyage</a>
            <a href="#" class="sidebar-item">Rapport d'arrêts</a>
            <a href="#" class="sidebar-item">Rapport résumé</a>
            <a href="#" class="sidebar-item">Rapport de graphique</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-chart-bar"></i> Rapports</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Générer un rapport</h3>
            </div>
            <div class="card-body">
                <form id="report-form">
                    <div class="form-group">
                        <label for="device">Appareil</label>
                        <select id="device" name="device" class="form-control">
                            <option value="">Sélectionner un appareil</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="from">Date de début</label>
                            <input type="datetime-local" id="from" name="from" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="to">Date de fin</label>
                            <input type="datetime-local" id="to" name="to" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-alt"></i> Générer le rapport
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3>Résultats</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Sélectionnez un appareil et une période pour générer un rapport.</p>
            </div>
        </div>
    </main>
</div>
@endsection
