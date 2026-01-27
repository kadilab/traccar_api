@extends('layouts.app')

@section('title', 'Géolocalisation Professionnelle')

@section('body-class', '')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #e3f0ff 0%, #f8faff 100%);
    }
    .navbar-custom {
        background: #f8faff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 0.7rem 0;
    }
    .navbar-logo {
        font-weight: bold;
        font-size: 1.5rem;
        color: #007bff;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
    }
    .navbar-logo i {
        font-size: 2rem;
        margin-right: 0.5rem;
    }
    .navbar-login-btn {
        min-width: 120px;
    }
    .hero-section {
        background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
        color: #fff;
        padding: 80px 0 60px 0;
        text-align: center;
        box-shadow: 0 4px 24px rgba(0,123,255,0.08);
    }
    .section-alt {
        background: linear-gradient(90deg, #f0f6ff 0%, #e3f0ff 100%);
    }
    .section-colored {
        background: linear-gradient(90deg, #e3f0ff 0%, #f8faff 100%);
    }
    .feature-icon {
        font-size: 2rem;
        color: #007bff;
    }
    .card {
        background: #f8faff;
        border: none;
    }
    .card-title {
        color: #007bff;
    }
    .border {
        border: 1px solid #e3eafc !important;
    }
    .bg-white {
        background: #f8faff !important;
    }
    .footer-partners {
        background: linear-gradient(90deg, #e3f0ff 0%, #f8faff 100%);
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@section('content')
    <!-- Navigation Bar (visible pour visiteurs) -->
    @guest
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-logo" href="#">
                <i class="bi bi-geo-alt-fill"></i> GeoTrack Pro
            </a>
            <div class="d-flex align-items-center ms-auto">
                <a href="{{ route('login') }}" class="btn btn-primary navbar-login-btn">Connexion</a>
            </div>
        </div>
    </nav>
    <div style="height: 70px;"></div> <!-- Décalage pour la navbar fixed -->
    @endguest

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Expert en Géolocalisation & Gestion de Flotte</h1>
            <p class="lead mb-4">Solutions professionnelles pour le suivi, la sécurité et l’optimisation de vos véhicules, équipements et équipes. Plateforme intuitive, alertes intelligentes, et compatibilité multi-GPS.</p>
            <a href="#cta" class="btn btn-light btn-lg shadow">Demander un essai gratuit</a>
        </div>
    </section>

    <!-- Avantages Section -->
    <section class="container py-5 section-alt rounded-4 my-4">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="p-4 h-100 border rounded bg-white">
                    <i class="bi bi-shield-lock feature-icon mb-3"></i>
                    <h5 class="fw-bold mb-2">Sécurité & Confidentialité</h5>
                    <p class="mb-0">Analyse du comportement, alertes vol, protection des données et récupération rapide.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 h-100 border rounded bg-white">
                    <i class="bi bi-graph-up-arrow feature-icon mb-3"></i>
                    <h5 class="fw-bold mb-2">Productivité & Économie</h5>
                    <p class="mb-0">Optimisation des trajets, réduction des coûts, gestion du carburant et maintenance prédictive.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 h-100 border rounded bg-white">
                    <i class="bi bi-cpu feature-icon mb-3"></i>
                    <h5 class="fw-bold mb-2">Compatibilité Totale</h5>
                    <p class="mb-0">Support de nombreux modèles GPS (Teltonika, Coban, Queclink, Concox, etc.) et caméras embarquées.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Produits phares Section -->
    <section class="container py-5 section-colored rounded-4 my-4">
        <h2 class="fw-bold text-center mb-5">Nos équipements phares</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="https://www.seeworldgps.com/wp-content/uploads/2023/11/S5L-4G-GPS-Tracker.jpg" class="card-img-top" alt="Traceur GPS 4G">
                    <div class="card-body">
                        <h5 class="card-title">Traceur GPS 4G S5L</h5>
                        <p class="card-text">Suivi carburant & température, gestion flotte, alertes intelligentes.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="https://www.seeworldgps.com/wp-content/uploads/2023/11/R16-2G-GPS-Tracker.jpg" class="card-img-top" alt="Traceur GPS 2G">
                    <div class="card-body">
                        <h5 class="card-title">Traceur GPS 2G R16</h5>
                        <p class="card-text">Idéal pour voitures, motos, gestion de flotte et sécurité antivol.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="https://www.seeworldgps.com/wp-content/uploads/2023/11/V7Pro-4G-Dash-Cam.jpg" class="card-img-top" alt="Caméra 4G IA">
                    <div class="card-body">
                        <h5 class="card-title">Caméra 4G V7 Pro</h5>
                        <p class="card-text">Double objectif, IA embarquée, sécurité et analyse vidéo en temps réel.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions métiers Section -->
    <section class="container py-5 section-alt rounded-4 my-4">
        <h2 class="fw-bold text-center mb-5">Solutions adaptées à chaque secteur</h2>
        <div class="row text-center g-4">
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white h-100">
                    <i class="bi bi-truck feature-icon mb-2"></i>
                    <div>Transport & Logistique</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white h-100">
                    <i class="bi bi-building feature-icon mb-2"></i>
                    <div>Construction & BTP</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white h-100">
                    <i class="bi bi-bicycle feature-icon mb-2"></i>
                    <div>Mobilité douce</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white h-100">
                    <i class="bi bi-bus-front feature-icon mb-2"></i>
                    <div>Transport scolaire</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white h-100">
                    <i class="bi bi-car-front feature-icon mb-2"></i>
                    <div>Location de véhicules</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white h-100">
                    <i class="bi bi-shield-check feature-icon mb-2"></i>
                    <div>Assurance & Sécurité</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fonctionnalités Section -->
    <section class="container py-5 section-colored rounded-4 my-4">
        <h2 class="fw-bold text-center mb-5">Fonctionnalités avancées</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 border rounded bg-white h-100">
                    <h5 class="fw-bold mb-2">Suivi en temps réel</h5>
                    <p>Visualisez la position, l’itinéraire et l’historique de chaque véhicule ou équipement.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded bg-white h-100">
                    <h5 class="fw-bold mb-2">Alertes & Rapports</h5>
                    <p>Recevez des notifications instantanées (SMS/email), rapports personnalisés, alertes géorepérage, conduite, maintenance…</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded bg-white h-100">
                    <h5 class="fw-bold mb-2">Plateforme intuitive</h5>
                    <p>Accès web/mobile, interface simple, gestion multi-utilisateurs, API ouverte pour intégration.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Appel à l'action Section -->
    <section id="cta" class="container py-5 text-center section-alt rounded-4 my-4">
        <div class="bg-primary text-white rounded p-5 shadow">
            <h2 class="fw-bold mb-3">Demandez votre essai gratuit ou un devis personnalisé</h2>
            <p class="mb-4">Notre équipe vous accompagne pour trouver la solution la plus adaptée à vos besoins professionnels.</p>
            @if(!\App\Models\User::where('administrator', true)->exists())
            <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">Créer un compte</a>
            @endif
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Connexion</a>
        </div>
    </section>

    <!-- Footer partenaires Section -->
    <footer class="text-center py-4 mt-5 footer-partners">
        <div class="container">
            <div class="mb-2">Ils nous font confiance :</div>
            <img src="https://www.seeworldgps.com/wp-content/uploads/2025/05/telcel_logo-150x150.webp" alt="telcel" class="mx-2" style="height:40px;">
            <img src="https://www.seeworldgps.com/wp-content/uploads/2025/05/shopee_logo-150x150.webp" alt="shopee" class="mx-2" style="height:40px;">
            <div class="mt-3">
                <small>&copy; {{ date('Y') }} GeoTrack Pro. Tous droits réservés.</small>
            </div>
        </div>
    </footer>
@endsection