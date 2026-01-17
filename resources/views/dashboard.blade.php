@extends('layouts.app')

@section('title', 'Dashboard - Traccar TF')

@section('content')

<div class="main-container">
     <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-user">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'U' }}&background=667eea&color=fff&size=40&bold=true" alt="Avatar" class="user-avatar">
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                <span class="user-role">{{ auth()->user()->administrator ? __('messages.roles.admin') : __('messages.roles.user') }}</span>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item active">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('profile') }}" class="menu-item">
                <i class="fas fa-user"></i>
                <span>Mon Profil</span>
            </a>
            @if(auth()->user()->administrator)
            <a href="{{ route('account') }}" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="{{ route('reports') }}" class="menu-item">
                <i class="fas fa-chart-pie"></i>
                <span>Rapports</span>
            </a>
            @endif
            <a href="{{ route('monitor') }}" class="menu-item">
                <i class="fas fa-desktop"></i>
                <span>Moniteur</span>
            </a>
            <a href="{{ route('device') }}" class="menu-item">
                <i class="fas fa-microchip"></i>
                <span>Appareils</span>
            </a>
            <a href="{{ route('geofence') }}" class="menu-item">
                <i class="fas fa-draw-polygon"></i>
                <span>Géobarrières</span>
            </a>
        </nav>
        
        <div class="sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-power-off"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside> 

    <!-- Main Content -->
    <main class="main-content">
        @if(isset($error))
        <div class="error-banner">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ $error }}</span>
        </div>
        @endif

       

    <!-- Tab Navigation -->
        <div class="tab-nav d-flex align-items-center justify-content-between">
            <div class="col-auto d-flex gap-2">
                <button class="tab-item active" data-tab="overview">Overview</button>
                <button class="tab-item" data-tab="rapid-salle">Rapid Salle</button>
                <button class="tab-item" data-tab="mi-coins">Mi Coins</button>  
            </div>
            <div class="col-auto ">
                <button id="refresh-stats-btn" class="refresh-btn" title="Rafraîchir les statistiques">
                    <i class="fas fa-sync-alt"></i>
                </button>
                
            </div>
        </div>

        <!-- Tab Content: Overview -->
        <div class="tab-content active" id="tab-overview">
      <!-- Dashboard Grid -->
      <div class="dashboard-grid">
        <!-- Left Section -->
        <div class="grid-left">
            <!-- Row with 2 columns -->
            <div class="grid-row-2">
                <!-- Column 1 -->
                <div class="grid-col">
                    <a href="#devices" class="grid-card card-purple stat-link">
                        <div class="card-content">
                            <div class="card-number">{{ $stockCount ?? 41 }}</div>
                            <div class="card-label">
                                <i class="fas fa-box"></i>
                                <span>Stock</span>
                            </div>
                        </div>
                    </a>
                    <div class="grid-row-2">
                        <a href="#online-devices" class="grid-card card-blue stat-link">
                            <div class="card-content">
                            <div class="card-number">{{ $onlineCount ?? 41 }}</div>
                            <div class="card-label">
                                <i class="fas fa-wifi"></i>
                                <span>Online</span>
                            </div>
                        </div>
                        </a>
                        <a href="#offline-devices" class="grid-card card-danger stat-link">
                            <div class="card-content">
                            <div class="card-number">{{ $offlineCount ?? 41 }}</div>
                            <div class="card-label">
                                <i class="fas fa-wifi" style="opacity: 0.5;"></i>
                                <span>Offline</span>
                            </div>
                        </div></a>
                    </div>
                    <div class="grid-row-2">
                        <a href="#active-devices" class="grid-card card-success stat-link">
                            <div class="card-content">
                            <div class="card-number">{{ $activeCount ?? 41 }}</div>
                            <div class="card-label">
                                <i class="fas fa-check-circle"></i>
                                <span>Active</span>
                            </div>
                        </div></a>
                        <a href="#inactive-devices" class="grid-card card-teal stat-link">
                            <div class="card-content">
                            <div class="card-number">{{ $inactiveCount ?? 41 }}</div>
                            <div class="card-label">
                                <i class="fas fa-pause-circle"></i>
                                <span>Inactive</span>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <!-- Column 2 -->
                <div class="grid-col">
                    <div class="grid-row-2">
                        <a href="#all-devices" class="grid-card card-magenta stat-link">
                            <div class="card-content">
                                <div class="card-number" id="stat-total">{{ $totalDevices ?? 0 }}</div>
                                <div class="card-label">
                                    <i class="fas fa-microchip"></i>
                                    <span>Devices</span>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('groupe') }}" class="grid-card card-indigo stat-link">
                            <div class="card-content">
                                <div class="card-number" id="stat-groups">{{ $groupCount ?? 0 }}</div>
                                <div class="card-label">
                                    <i class="fas fa-layer-group"></i>
                                    <span>Groupes</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="grid-row-2">
                        <a href="#expiring-devices" class="grid-card card-success stat-link">
                            <div class="card-content">
                            <div class="card-number">{{ $expiringCount ?? 0 }}</div>
                            <div class="card-label">
                                <i class="fas fa-clock"></i>
                                <span>Expiring</span>
                            </div>
                        </div></a>
                        <a href="#expired-devices" class="grid-card card-ex stat-link">
                            <div class="card-content">
                            <div class="card-number">{{ $expiredCount ?? 0 }}</div>
                            <div class="card-label">
                                <i class="fas fa-calendar-times"></i>
                                <span>Expired</span>
                            </div>
                        </div></a>
                    </div>
                    <div class="grid-row-2">
                        <a href="{{ route('account') }}" class="grid-card card-warning stat-link">
                            <div class="card-content">
                            <div class="card-number" id="stat-users">{{ $userCount ?? 0 }}</div>
                            <div class="card-label">
                                <i class="fas fa-users"></i>
                                <span>Utilisateurs</span>
                            </div>
                        </div></a>
                        <a href="{{ route('geofence') }}" class="grid-card card-pink stat-link">
                            <div class="card-content">
                            <div class="card-number" id="stat-geofences">{{ $geofenceCount ?? 0 }}</div>
                            <div class="card-label">
                                <i class="fas fa-draw-polygon"></i>
                                <span>Geofences</span>
                            </div>
                        </div></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="grid-right">
            <div class="grid-row-right">
                <!-- Left narrow column -->
                <div class="grid-col-narrow">
                    <a href="{{ route('monitor') ?? '#' }}" class="grid-card card-blue widget-link">
                        <div class="card-content">
                            <div class="card-number">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <div class="card-label">
                                <span>Monitor</span>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('geofence') ?? '#' }}" class="grid-card card-ex widget-link">
                        <div class="card-content">
                            <div class="card-number">
                                <i class="fas fa-draw-polygon"></i>
                            </div>
                            <div class="card-label">
                                <span>Geo Fence</span>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('commandes') ?? '#' }}" class="grid-card card-primary widget-link">
                        <div class="card-content">
                            <div class="card-number">
                                <i class="fas fa-terminal"></i>
                            </div>
                            <div class="card-label">
                                <span>Commande</span>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Right wide column -->
                <div class="grid-col-wide">
                    <a href="{{ route('reports') ?? '#' }}" class="grid-card card-ex widget-link">
                      <div class="card-content">
                            <div class="">
                                <i class="fas fa-chart-bar fa-2x"></i>
                            </div>
                            <div class="card-label">
                                <span>Raport</span>
                            </div>
                        </div>
                    </a>
                    <div class="grid-row-2">
                        <a href="{{ route('account.index') ?? '#' }}" class="grid-card card-magenta widget-link">
                            <div class="card-content">
                            <div class="">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <div class="card-label">
                                <span>Account</span>
                            </div>
                        </div>
                        </a>
                        <a href="{{ route('devices.index') ?? '#' }}" class="grid-card card-cyan widget-link">
                            <div class="card-content">
                            <div class="">
                                <i class="fas fa-mobile-alt fa-2x"></i>
                            </div>
                            <div class="card-label">
                                <span>Device M</span>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="grid-row-2">
                        <a href="{{ route('poi.index') ?? '#' }}" class="grid-card card-success widget-link">
                            <div class="card-content">
                            <div class="">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                            <div class="card-label">
                                <span>POI</span>
                            </div>
                        </div>
                        </a>
                        <a href="{{ route('alerts.index') ?? '#' }}" class="grid-card card-orange widget-link">
                            <div class="card-content">
                            <div class="">
                               <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div class="card-label">
                                <span>Alerts</span>
                            </div>
                        </div>
                        </a>
                        
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <!-- <div class="grid-bottom">
            <div class="grid-card card-light grid-bottom-1">
                a
            </div>
            <div class="grid-card card-light grid-bottom-2">d</div>
            <div class="grid-card card-light grid-bottom-3">c</div>
        </div> -->
      </div>
        </div>

        <!-- Tab Content: Rapid Salle -->
        <div class="tab-content" id="tab-rapid-salle">
            <div class="row">
                <div class="col-md-8">
                    
                </div>
                <div class="col-md-4">
                       
               
                </div>
            </div>
           
        </div>
      
        <!-- Tab Content: Mi Coins -->
        <div class="tab-content" id="tab-mi-coins">
            <div class="tab-panel">
                <div class="panel-header">
                    <h3>Mi Coins</h3>
                    <span class="panel-badge">1200</span>
                </div>
                <div class="panel-body">
                    <p>Contenu de Mi Coins à personnaliser...</p>
                </div>
            </div>
        </div>

     
    </main>
  </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabItems = document.querySelectorAll('.tab-item');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Retirer la classe active de tous les onglets
            tabItems.forEach(tab => tab.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Ajouter la classe active à l'onglet cliqué
            this.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });
    
    // Ajouter des effets de survol sur les widgets et les cartes de statistiques
    const statLinks = document.querySelectorAll('.stat-link, .widget-link');
    statLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.2)';
            this.style.cursor = 'pointer';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });

    // Rafraîchissement automatique des statistiques toutes les 30 secondes
    function refreshStats() {
        fetch('{{ route("dashboard.stats") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.stats) {
                updateStatsUI(data.stats);
            }
        })
        .catch(error => console.log('Erreur rafraîchissement stats:', error));
    }

    function updateStatsUI(stats) {
        // Mise à jour des valeurs dans l'interface
        const statMappings = {
            'stock': stats.stockCount,
            'online': stats.onlineCount,
            'offline': stats.offlineCount,
            'active': stats.activeCount,
            'inactive': stats.inactiveCount,
            'devices': stats.totalDevices,
            'groupes': stats.groupCount,
            'expiring': stats.expiringCount,
            'expired': stats.expiredCount,
            'utilisateurs': stats.userCount,
            'geofences': stats.geofenceCount
        };

        document.querySelectorAll('.stat-link').forEach(link => {
            const label = link.querySelector('.card-label span');
            if (label) {
                const key = label.textContent.toLowerCase();
                if (statMappings[key] !== undefined) {
                    const numberEl = link.querySelector('.card-number');
                    if (numberEl) {
                        // Animation de mise à jour
                        numberEl.style.opacity = '0.5';
                        setTimeout(() => {
                            numberEl.textContent = statMappings[key];
                            numberEl.style.opacity = '1';
                        }, 150);
                    }
                }
            }
        });
    }

    // Rafraîchir toutes les 5 secondes
    setInterval(refreshStats, 5000);

    // Bouton de rafraîchissement manuel si présent
    const refreshBtn = document.getElementById('refresh-stats-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.classList.add('spinning');
            refreshStats();
            setTimeout(() => this.classList.remove('spinning'), 1000);
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.stat-link, .widget-link {
    text-decoration: none;
    color: inherit;
    display: block;
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stat-link:hover, .widget-link:hover {
    text-decoration: none;
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.grid-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stat-link .grid-card, .widget-link .grid-card {
    cursor: pointer;
}

.card-number {
    transition: opacity 0.3s ease;
}

/* Animation pour le bouton de rafraîchissement */
.spinning {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Effet pulse pour les statistiques importantes */
.stat-link.pulse .card-number {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Indicateur en ligne/hors ligne */
.status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
}

.status-online {
    background-color: #28a745;
    box-shadow: 0 0 8px rgba(40, 167, 69, 0.6);
}

.status-offline {
    background-color: #dc3545;
    box-shadow: 0 0 8px rgba(220, 53, 69, 0.6);
}

/* Message d'erreur */
.error-banner {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.error-banner i {
    font-size: 1.2em;
}
</style>
@endpush
@endsection
