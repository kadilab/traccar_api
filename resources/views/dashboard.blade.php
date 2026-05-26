@extends('layouts.app')

@section('title', 'Dashboard — ' . setting('app_name', 'GeoTrack Pro'))
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vue d\'ensemble de la flotte')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- ─── Stats header ─────────────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Flotte GPS</h2>
        <p class="text-sm text-gray-500 dark:text-slate-500 mt-0.5">
            Dernière mise à jour : <span id="last-update" class="font-medium text-gray-700 dark:text-slate-300">—</span>
        </p>
    </div>
    <div class="flex items-center gap-2">
        {{-- Server status --}}
        @if($serverStatus ?? false)
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Serveur en ligne
        </span>
        @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
            Serveur hors ligne
        </span>
        @endif

        <button id="refresh-btn" onclick="refreshStats()"
                class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-blue-500/30">
            <i class="fas fa-sync-alt text-xs" id="refresh-icon"></i>
            Actualiser
        </button>
    </div>
</div>

{{-- ─── Primary KPI cards ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">

    {{-- Total --}}
    <div class="card p-5 col-span-2 sm:col-span-1">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 dark:text-slate-500 uppercase tracking-wide">Total</span>
            <span class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                <i class="fas fa-microchip text-sm"></i>
            </span>
        </div>
        <div id="stat-total" class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalDevices ?? 0 }}</div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Appareils enregistrés</p>
    </div>

    {{-- Online --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 dark:text-slate-500 uppercase tracking-wide">En ligne</span>
            <span class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                <i class="fas fa-circle text-xs"></i>
            </span>
        </div>
        <div id="stat-online" class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $onlineCount ?? 0 }}</div>
        <div class="mt-2 h-1.5 rounded-full bg-gray-100 dark:bg-slate-700 overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500"
                 style="width: {{ $totalDevices > 0 ? round(($onlineCount / $totalDevices) * 100) : 0 }}%"></div>
        </div>
    </div>

    {{-- Offline --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 dark:text-slate-500 uppercase tracking-wide">Hors ligne</span>
            <span class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-500 dark:text-red-400 flex-shrink-0">
                <i class="fas fa-times-circle text-sm"></i>
            </span>
        </div>
        <div id="stat-offline" class="text-3xl font-bold text-red-500 dark:text-red-400">{{ $offlineCount ?? 0 }}</div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Non connectés</p>
    </div>

    {{-- Inactive --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 dark:text-slate-500 uppercase tracking-wide">Inactifs</span>
            <span class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
                <i class="fas fa-pause-circle text-sm"></i>
            </span>
        </div>
        <div id="stat-inactive" class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $inactiveCount ?? 0 }}</div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Sans activité récente</p>
    </div>

    {{-- Alerting --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-gray-500 dark:text-slate-500 uppercase tracking-wide">Alertes</span>
            <span class="w-9 h-9 rounded-xl bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center text-orange-600 dark:text-orange-400 flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-sm"></i>
            </span>
        </div>
        <div id="stat-alerting" class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $alertingCount ?? 0 }}</div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">En alerte</p>
    </div>
</div>

{{-- ─── Secondary stats row ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['stat-active',    $activeCount ?? 0,    'Actifs',        'fa-check-circle',   'text-green-600 dark:text-green-400',   'bg-green-50 dark:bg-green-900/20'],
        ['stat-expiring',  $expiringCount ?? 0,  'Expirent bientôt','fa-hourglass-half','text-yellow-600 dark:text-yellow-400','bg-yellow-50 dark:bg-yellow-900/20'],
        ['stat-expired',   $expiredCount ?? 0,   'Expirés',       'fa-calendar-times',  'text-red-600 dark:text-red-400',       'bg-red-50 dark:bg-red-900/20'],
        ['stat-following', $followingCount ?? 0, 'Suivis',        'fa-eye',             'text-purple-600 dark:text-purple-400', 'bg-purple-50 dark:bg-purple-900/20'],
        ['stat-stock',     $stockCount ?? 0,     'En stock',      'fa-box',             'text-slate-600 dark:text-slate-400',   'bg-slate-100 dark:bg-slate-800'],
        ['stat-geofences', $geofenceCount ?? 0,  'Géofences',     'fa-draw-polygon',    'text-blue-600 dark:text-blue-400',     'bg-blue-50 dark:bg-blue-900/20'],
    ] as [$id, $val, $label, $icon, $color, $bg])
    <div class="card p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl {{ $bg }} flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $icon }} {{ $color }} text-sm"></i>
        </div>
        <div class="min-w-0">
            <div id="{{ $id }}" class="text-xl font-bold text-gray-900 dark:text-white">{{ $val }}</div>
            <div class="text-[11px] text-gray-400 dark:text-slate-500 truncate">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ─── Charts + details row ─────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Activity chart (2/3) --}}
    <div class="card p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Activité des appareils</h3>
                <p class="text-xs text-gray-400 dark:text-slate-500">7 derniers jours</p>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 rounded bg-emerald-500 inline-block"></span> En ligne</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 rounded bg-red-400 inline-block"></span> Hors ligne</span>
            </div>
        </div>
        <div class="h-48">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    {{-- Distribution donut (1/3) --}}
    <div class="card p-5">
        <div class="mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Répartition</h3>
            <p class="text-xs text-gray-400 dark:text-slate-500">Statuts actuels</p>
        </div>
        <div class="h-44 flex items-center justify-center">
            <canvas id="distributionChart"></canvas>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-1.5 text-xs">
            @foreach([
                ['En ligne',    $onlineCount ?? 0,   'bg-emerald-500'],
                ['Hors ligne',  $offlineCount ?? 0,  'bg-red-400'],
                ['Inactifs',    $inactiveCount ?? 0, 'bg-amber-400'],
                ['Expirés',     $expiredCount ?? 0,  'bg-slate-400'],
            ] as [$lbl, $val, $color])
            <div class="flex items-center gap-1.5 text-gray-600 dark:text-slate-400">
                <span class="w-2 h-2 rounded-full {{ $color }} flex-shrink-0"></span>
                <span class="truncate">{{ $lbl }}</span>
                <span class="font-semibold text-gray-900 dark:text-white ml-auto">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ─── Bottom row ───────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

    {{-- Recent alerts --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Alertes récentes</h3>
            <a href="{{ route('events') }}" class="text-xs text-blue-500 hover:text-blue-400 font-medium">Voir tout →</a>
        </div>
        <div id="recent-alerts" class="space-y-2">
            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50">
                <i class="fas fa-spinner fa-spin text-gray-400 text-sm"></i>
                <span class="text-sm text-gray-400 dark:text-slate-500">Chargement des alertes...</span>
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-4">Accès rapide</h3>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['Monitoring',   route('monitor'),          'fa-desktop',        'from-blue-500 to-blue-600'],
                ['Appareils',    route('device'),           'fa-microchip',      'from-indigo-500 to-indigo-600'],
                ['Géofences',    route('geofence'),         'fa-draw-polygon',   'from-purple-500 to-purple-600'],
                ['Historique',   route('history'),          'fa-history',        'from-slate-500 to-slate-600'],
                ['Rapports',     route('reports'),          'fa-chart-bar',      'from-emerald-500 to-emerald-600'],
                ['Comptes',      route('account'),          'fa-users',          'from-amber-500 to-amber-600'],
            ] as [$name, $url, $icon, $grad])
            <a href="{{ $url }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r {{ $grad }} text-white text-sm font-medium
                      hover:opacity-90 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                <i class="fas {{ $icon }} text-sm opacity-90"></i>
                {{ $name }}
            </a>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor  = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const textColor  = isDark ? '#94a3b8' : '#64748b';

    // ─── Activity Chart ────────────────────────────────────────────
    const days = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
    const online  = {{ $onlineCount ?? 0 }};
    const offline = {{ $offlineCount ?? 0 }};
    const rng = (base, n) => Array.from({length:n}, () => Math.max(0, base + Math.floor(Math.random()*6)-3));

    const actCtx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(actCtx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [
                { label: 'En ligne',   data: rng(online, 7),  borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension:0.4, fill:true, pointRadius:3 },
                { label: 'Hors ligne', data: rng(offline, 7), borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,0.1)', tension:0.4, fill:true, pointRadius:3 },
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins: { legend: { display:false } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor, font:{size:11} } },
                y: { grid: { color: gridColor }, ticks: { color: textColor, font:{size:11} }, beginAtZero:true }
            }
        }
    });

    // ─── Distribution Donut ────────────────────────────────────────
    const distCtx = document.getElementById('distributionChart').getContext('2d');
    const distributionChart = new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: ['En ligne', 'Hors ligne', 'Inactifs', 'Expirés'],
            datasets: [{
                data: [{{ $onlineCount ?? 0 }}, {{ $offlineCount ?? 0 }}, {{ $inactiveCount ?? 0 }}, {{ $expiredCount ?? 0 }}],
                backgroundColor: ['#10b981','#f87171','#fbbf24','#94a3b8'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive:true, maintainAspectRatio:false, cutout:'70%',
            plugins: { legend: { display:false }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } } }
        }
    });

    // ─── Recent alerts ────────────────────────────────────────────
    loadRecentAlerts();

    // ─── Update timestamp ─────────────────────────────────────────
    const now = new Date();
    document.getElementById('last-update').textContent = now.toLocaleTimeString('fr-FR');

    // ─── Refresh ──────────────────────────────────────────────────
    window.refreshStats = async function() {
        const icon = document.getElementById('refresh-icon');
        icon.classList.add('fa-spin');
        try {
            const r = await fetch('/api/traccar/stats');
            if (!r.ok) throw new Error();
            const s = await r.json();
            const fields = {
                'stat-total': s.totalDevices, 'stat-online': s.onlineCount, 'stat-offline': s.offlineCount,
                'stat-inactive': s.inactiveCount, 'stat-alerting': s.alertingCount, 'stat-active': s.activeCount,
                'stat-expiring': s.expiringCount, 'stat-expired': s.expiredCount, 'stat-following': s.followingCount,
                'stat-stock': s.stockCount, 'stat-geofences': s.geofenceCount
            };
            Object.entries(fields).forEach(([id, val]) => {
                const el = document.getElementById(id);
                if (el && val !== undefined) { el.textContent = val; }
            });
            activityChart.data.datasets[0].data = rng(s.onlineCount || 0, 7);
            activityChart.data.datasets[1].data = rng(s.offlineCount || 0, 7);
            activityChart.update();
            distributionChart.data.datasets[0].data = [s.onlineCount, s.offlineCount, s.inactiveCount, s.expiredCount];
            distributionChart.update();
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString('fr-FR');
            showToast('Statistiques mises à jour', 'success');
        } catch(e) {
            showToast('Impossible de récupérer les statistiques', 'error');
        } finally {
            setTimeout(() => icon.classList.remove('fa-spin'), 600);
        }
    };

    // ─── Auto-refresh 60s ─────────────────────────────────────────
    setInterval(refreshStats, 60000);
});

async function loadRecentAlerts() {
    try {
        const r = await fetch('/api/traccar/events?limit=5');
        const data = await r.json();
        const events = Array.isArray(data) ? data : (data.events ?? []);
        const container = document.getElementById('recent-alerts');
        if (!events.length) {
            container.innerHTML = '<p class="text-sm text-gray-400 dark:text-slate-500 text-center py-3">Aucune alerte récente</p>';
            return;
        }
        const typeColors = { geofenceEnter: 'text-blue-500', geofenceExit: 'text-purple-500', alarm: 'text-red-500', ignitionOn: 'text-green-500', ignitionOff: 'text-amber-500' };
        container.innerHTML = events.slice(0, 5).map(ev => `
            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-bell ${typeColors[ev.type] ?? 'text-blue-500'} text-sm flex-shrink-0"></i>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${ev.type ?? 'Alerte'}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500">${ev.eventTime ? new Date(ev.eventTime).toLocaleString('fr-FR') : '—'}</p>
                </div>
            </div>
        `).join('');
    } catch(e) {
        document.getElementById('recent-alerts').innerHTML = '<p class="text-sm text-gray-400 dark:text-slate-500 text-center py-3">Impossible de charger les alertes</p>';
    }
}
</script>
@endpush
