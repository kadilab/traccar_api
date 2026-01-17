@extends('layouts.app')

@section('title', 'Commandes - Traccar TF')

@section('content')
<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2 class="sidebar-title">Commandes</h2>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-item active">Toutes les commandes</a>
            <a href="#" class="sidebar-item">Commandes envoyées</a>
            <a href="#" class="sidebar-item">Modèles de commandes</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-terminal"></i> Commandes</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Envoyer une commande</h3>
            </div>
            <div class="card-body">
                <form id="command-form">
                    <div class="form-group">
                        <label for="device">Appareil</label>
                        <select id="device" name="device" class="form-control">
                            <option value="">Sélectionner un appareil</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="command-type">Type de commande</label>
                        <select id="command-type" name="type" class="form-control">
                            <option value="custom">Commande personnalisée</option>
                            <option value="positionPeriodic">Position périodique</option>
                            <option value="positionStop">Arrêter le positionnement</option>
                            <option value="engineStop">Arrêter le moteur</option>
                            <option value="engineResume">Redémarrer le moteur</option>
                            <option value="alarmArm">Activer l'alarme</option>
                            <option value="alarmDisarm">Désactiver l'alarme</option>
                        </select>
                    </div>
                    <div class="form-group" id="custom-command-group">
                        <label for="command-data">Commande</label>
                        <textarea id="command-data" name="data" class="form-control" rows="3" placeholder="Entrez votre commande..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer la commande
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3>Historique des commandes</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Appareil</th>
                            <th>Commande</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody id="commands-history">
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucune commande envoyée</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
