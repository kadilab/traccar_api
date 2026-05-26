<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      x-data="appLayout()"
      x-init="init()"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('app_name', 'GeoTrack Pro'))</title>

    @php $appLogo = setting('app_logo'); $favicon = setting('app_favicon'); @endphp
    @if($favicon)
        <link rel="icon" href="{{ Storage::url($favicon) }}">
    @endif

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap 5 CSS (loaded before Tailwind so Tailwind utilities override) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-950 font-sans antialiased @yield('body-class')">

@auth
{{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SIDEBAR OVERLAY (mobile) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div x-show="sidebarOpen && isMobile"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 lg:hidden">
</div>

{{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SIDEBAR â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<aside :class="{
           'translate-x-0': sidebarOpen || !isMobile,
           '-translate-x-full': !sidebarOpen && isMobile,
           'w-64 shadow-2xl shadow-black/40': navExpanded || isMobile,
           'w-16': !navExpanded && !isMobile,
           'is-expanded': navExpanded || isMobile
       }"
       @mouseenter="if(!isMobile) navExpanded = true"
       @mouseleave="if(!isMobile) navExpanded = false"
       class="sidebar-nav fixed top-0 left-0 h-full bg-slate-900 dark:bg-gray-950 border-r border-slate-800 z-50 flex flex-col transition-all duration-200 ease-in-out select-none overflow-hidden">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-3 py-4 border-b border-slate-800 flex-shrink-0" style="min-height:60px">
        @if($appLogo)
            <img src="{{ Storage::url($appLogo) }}" alt="logo" class="h-8 w-auto flex-shrink-0">
        @else
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30">
                <i class="fas fa-map-marker-alt text-white text-xs"></i>
            </div>
        @endif
        <div class="nav-label min-w-0 flex-1">
            <span class="text-white font-bold text-sm leading-none block truncate whitespace-nowrap">{{ setting('app_name', 'GeoTrack') }}</span>
            <span class="text-blue-400 text-[10px] font-medium tracking-wide whitespace-nowrap">GPS Tracking</span>
        </div>
        {{-- Close btn mobile --}}
        <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-500 hover:text-white p-1 rounded flex-shrink-0">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden px-2 py-3 space-y-0.5">

        {{-- Main nav --}}
        <p class="nav-section-label text-slate-500 text-[10px] font-bold uppercase tracking-widest px-3 py-2">Navigation</p>

        <a href="{{ route('monitor') }}"
           title="{{ __('messages.nav.monitor') }}"
           class="nav-link {{ request()->routeIs('monitor') ? 'active' : '' }}">
            <i class="fas fa-desktop flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.monitor') }}</span>
        </a>

        <a href="{{ route('device') }}"
           title="{{ __('messages.nav.device') }}"
           class="nav-link {{ request()->routeIs('device', 'devices.*') ? 'active' : '' }}">
            <i class="fas fa-microchip flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.device') }}</span>
        </a>

        <a href="{{ route('geofence') }}"
           title="{{ __('messages.nav.geofence') }}"
           class="nav-link {{ request()->routeIs('geofence') ? 'active' : '' }}">
            <i class="fas fa-draw-polygon flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.geofence') }}</span>
        </a>

        <a href="{{ route('history') }}"
           title="{{ __('messages.nav.history') }}"
           class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}">
            <i class="fas fa-history flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.history') }}</span>
        </a>

        <a href="{{ route('tracking') }}"
           title="Tracking"
           class="nav-link {{ request()->routeIs('tracking') ? 'active' : '' }}">
            <i class="fas fa-location-arrow flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">Tracking</span>
        </a>

        <a href="{{ route('events') }}"
           title="{{ __('messages.nav.events') }}"
           class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}">
            <i class="fas fa-bell flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.events') }}</span>
            <span id="nav-badge" class="nav-badge-count ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
        </a>

        <a href="{{ route('commandes') }}"
           title="Commandes"
           class="nav-link {{ request()->routeIs('commandes') ? 'active' : '' }}">
            <i class="fas fa-terminal flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">Commandes</span>
        </a>

        {{-- Admin section --}}
        @if(Auth::user()->administrator ?? false)
        <div class="pt-3 pb-1">
            <p class="nav-section-label text-slate-500 text-[10px] font-bold uppercase tracking-widest px-3 py-1">Administration</p>
        </div>

        <a href="{{ route('dashboard') }}"
           title="{{ __('messages.nav.dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <i class="fas fa-th-large flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.dashboard') }}</span>
        </a>

        <a href="{{ route('account') }}"
           title="{{ __('messages.nav.account') }}"
           class="nav-link {{ request()->routeIs('account*') ? 'active' : '' }}">
            <i class="fas fa-users flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.account') }}</span>
        </a>

        <a href="{{ route('groupe') }}"
           title="{{ __('messages.nav.groupe') }}"
           class="nav-link {{ request()->routeIs('groupe') ? 'active' : '' }}">
            <i class="fas fa-layer-group flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.groupe') }}</span>
        </a>

        <a href="{{ route('drivers.index') }}"
           title="Conducteurs"
           class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
            <i class="fas fa-id-card flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">Conducteurs</span>
        </a>

        <a href="{{ route('attribute') }}"
           title="{{ __('messages.nav.attributs') }}"
           class="nav-link {{ request()->routeIs('attribute*', 'attributes*') ? 'active' : '' }}">
            <i class="fas fa-sliders-h flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.attributs') }}</span>
        </a>

        <a href="{{ route('reports') }}"
           title="Rapports"
           class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
            <i class="fas fa-chart-bar flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">Rapports</span>
        </a>

        <a href="{{ route('settings.index') }}"
           title="Paramètres"
           class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
            <i class="fas fa-cog flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">Paramètres</span>
        </a>

        @elseif(Auth::user()->isManager())
        <div class="pt-3 pb-1">
            <p class="nav-section-label text-slate-500 text-[10px] font-bold uppercase tracking-widest px-3 py-1">Gestion</p>
        </div>
        <a href="{{ route('account') }}"
           title="{{ __('messages.nav.account') }}"
           class="nav-link {{ request()->routeIs('account*') ? 'active' : '' }}">
            <i class="fas fa-users flex-shrink-0 w-4 text-center text-sm"></i>
            <span class="nav-label">{{ __('messages.nav.account') }}</span>
        </a>
        @endif

    </nav>

    {{-- User profile at bottom --}}
    <div class="border-t border-slate-800 p-3 flex-shrink-0">
        <a href="{{ route('profile') }}"
           title="{{ Auth::user()->name ?? 'Profil' }}"
           class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-800 transition-colors group">
            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'U').'&background=3b82f6&color=fff&size=80' }}"
                 alt="Avatar" class="w-8 h-8 rounded-full flex-shrink-0 ring-2 ring-slate-700 group-hover:ring-blue-500 transition-all">
            <div class="nav-label min-w-0 flex-1">
                <p class="text-white text-sm font-medium truncate leading-none whitespace-nowrap">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                <p class="text-slate-500 text-xs truncate mt-0.5">
                    @if(Auth::user()->administrator ?? false)
                        <span class="text-blue-400 whitespace-nowrap">Administrateur</span>
                    @else
                        <span class="whitespace-nowrap">Utilisateur</span>
                    @endif
                </p>
            </div>
            <i class="fas fa-chevron-right nav-label text-slate-600 text-xs flex-shrink-0"></i>
        </a>
    </div>
</aside>

{{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ MAIN CONTENT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div class="lg:pl-16 flex flex-col min-h-screen transition-all duration-200">

    {{-- TOP NAVBAR --}}
    <header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-slate-800">
        <div class="flex items-center h-14 px-4 sm:px-6 gap-3">

            {{-- Hamburger (mobile) --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors lg:hidden">
                <i class="fas fa-bars text-sm"></i>
            </button>

            {{-- Page title --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-gray-900 dark:text-white font-semibold text-sm sm:text-base leading-none truncate">
                    @yield('page-title', '')
                </h1>
                @hasSection('page-subtitle')
                <p class="text-gray-400 dark:text-slate-500 text-xs mt-0.5 truncate">@yield('page-subtitle')</p>
                @endif
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-1">

                {{-- Dark mode --}}
                <button @click="toggleDark()"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                        :title="dark ? 'Mode clair' : 'Mode sombre'">
                    <i x-show="!dark" class="fas fa-moon text-sm"></i>
                    <i x-show="dark" class="fas fa-sun text-sm text-amber-400"></i>
                </button>

                {{-- Language --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors text-xs font-bold">
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute right-0 mt-1 w-36 card py-1 z-50">
                        @foreach(['fr' => ['ðŸ‡«ðŸ‡·','FranÃ§ais'], 'en' => ['ðŸ‡¬ðŸ‡§','English'], 'ar' => ['ðŸ‡¸ðŸ‡¦','Ø§Ù„Ø¹Ø±Ø¨ÙŠØ©']] as $code => $lang)
                        <a href="{{ route('lang.switch', $code) }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ app()->getLocale()===$code ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-slate-300' }} hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                            <span>{{ $lang[0] }}</span>{{ $lang[1] }}
                            @if(app()->getLocale()===$code)<i class="fas fa-check ml-auto text-xs"></i>@endif
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Notifications --}}
                <a href="{{ route('events') }}"
                   class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                    <span id="notification-count"
                          class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full items-center justify-center hidden">0</span>
                </a>

                {{-- User dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-2 px-2 h-9 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'U').'&background=3b82f6&color=fff&size=80' }}"
                             class="w-6 h-6 rounded-full" alt="avatar">
                        <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-slate-300 max-w-[120px] truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                        <i class="fas fa-chevron-down text-gray-400 dark:text-slate-500 text-[10px]"></i>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute right-0 mt-1 w-52 card py-1.5 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-100 dark:border-slate-700">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 mt-1 transition-colors">
                            <i class="fas fa-user w-4 text-gray-400 text-xs"></i> Profil
                        </a>

                        @if(Auth::user()->administrator ?? false)
                        <a href="{{ route('settings.index') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                            <i class="fas fa-cog w-4 text-gray-400 text-xs"></i> ParamÃ¨tres
                        </a>
                        @endif

                        <div class="border-t border-gray-100 dark:border-slate-700 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fas fa-sign-out-alt w-4 text-xs"></i> DÃ©connexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="flex-1 p-4 sm:p-6">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="py-3 px-6 border-t border-gray-200 dark:border-slate-800 mt-auto">
        <p class="text-center text-xs text-gray-400 dark:text-slate-600">
            &copy; {{ date('Y') }} {{ setting('app_name', 'GeoTrack Pro') }}
            @if(setting('company_name')) &middot; {{ setting('company_name') }} @endif
            @if(setting('footer_text')) &middot; {{ setting('footer_text') }} @endif
        </p>
    </footer>
</div>

@else
{{-- Guest pages (login, register) --}}
<div class="min-h-screen">@yield('content')</div>
@endauth

{{-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ GLOBAL TOAST CONTAINER â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
<div id="toast-container" class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-2 max-w-xs w-full pointer-events-none"></div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// â”€â”€â”€ Alpine.js layout component â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function appLayout() {
    return {
        sidebarOpen: window.innerWidth >= 1024,
        isMobile: window.innerWidth < 1024,
        navExpanded: false,
        dark: localStorage.getItem('darkMode') === 'true',

        init() {
            this.$watch('dark', v => localStorage.setItem('darkMode', v));
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 1024;
                if (!this.isMobile) this.sidebarOpen = true;
                if (this.isMobile) this.navExpanded = false;
            });
            this.loadNotifications();
            setInterval(() => this.loadNotifications(), 30000);
        },

        toggleDark() { this.dark = !this.dark; },

        async loadNotifications() {
            try {
                const r = await fetch('/api/traccar/events/recent');
                const d = await r.json();
                const n = d.count || 0;
                const el = document.getElementById('notification-count');
                const nb = document.getElementById('nav-badge');
                if (el) { el.textContent = n > 99 ? '99+' : n; el.classList.toggle('hidden', n === 0); el.classList.toggle('flex', n > 0); }
                if (nb) { nb.textContent = n > 99 ? '99+' : n; nb.classList.toggle('hidden', n === 0); }
            } catch(e) {}
        }
    };
}

// â”€â”€â”€ Toast helper â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
window.showToast = function(msg, type = 'success') {
    const cfg = {
        success: { bg: 'bg-emerald-500', icon: 'fa-check-circle' },
        error:   { bg: 'bg-red-500',     icon: 'fa-times-circle' },
        warning: { bg: 'bg-amber-500',   icon: 'fa-exclamation-triangle' },
        info:    { bg: 'bg-blue-500',     icon: 'fa-info-circle' },
    };
    const c = cfg[type] || cfg.info;
    const el = document.createElement('div');
    el.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl text-white shadow-xl text-sm font-medium ${c.bg} transform transition-all duration-300 translate-y-3 opacity-0`;
    el.innerHTML = `<i class="fas ${c.icon} flex-shrink-0"></i><span>${msg}</span>`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => el.classList.remove('translate-y-3','opacity-0'), 10);
    setTimeout(() => { el.classList.add('translate-y-3','opacity-0'); setTimeout(() => el.remove(), 300); }, 3500);
};

// â”€â”€â”€ SweetAlert2 helpers (backward compatible) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
window.showSuccess     = (m, t='SuccÃ¨s')      => Swal.fire({ icon:'success', title:t, text:m, confirmButtonColor:'#3b82f6' });
window.showError       = (m, t='Erreur')       => Swal.fire({ icon:'error',   title:t, text:m, confirmButtonColor:'#ef4444' });
window.showWarning     = (m, t='Attention')    => Swal.fire({ icon:'warning', title:t, text:m, confirmButtonColor:'#f59e0b' });
window.showInfo        = (m, t='Information')  => Swal.fire({ icon:'info',    title:t, text:m, confirmButtonColor:'#3b82f6' });
window.showLoading     = (m='Chargement...')   => Swal.fire({ title:m, allowOutsideClick:false, showConfirmButton:false, didOpen:()=>Swal.showLoading() });
window.hideLoading     = ()                    => Swal.close();
window.showConfirm     = (m, t='Confirmation') => Swal.fire({ title:t, text:m, icon:'warning', showCancelButton:true, confirmButtonColor:'#3b82f6', cancelButtonColor:'#6b7280', confirmButtonText:'Confirmer', cancelButtonText:'Annuler', reverseButtons:true });
window.showDeleteConfirm = (item='cet Ã©lÃ©ment')=> Swal.fire({ title:'ÃŠtes-vous sÃ»r ?', html:`Supprimer <strong>${item}</strong> ?<br><small>Cette action est irrÃ©versible.</small>`, icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#6b7280', confirmButtonText:'<i class="fas fa-trash me-1"></i> Supprimer', cancelButtonText:'Annuler', reverseButtons:true });
window.showInputPrompt = (t, ph='', v='')      => Swal.fire({ title:t, input:'text', inputPlaceholder:ph, inputValue:v, showCancelButton:true, confirmButtonColor:'#3b82f6', cancelButtonColor:'#6b7280', confirmButtonText:'Valider', cancelButtonText:'Annuler', inputValidator:v=>!v?'Veuillez entrer une valeur':null });
</script>

{{-- Sidebar nav-link styles via Tailwind arbitrary --}}
<style>
/* ── Sidebar icon-rail ───────────────────────────── */
.nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 8px;
    font-size: 13.5px; font-weight: 500;
    color: #94a3b8; text-decoration: none;
    white-space: nowrap; overflow: hidden;
    transition: color 150ms, background 150ms;
    position: relative;
}
.nav-link:hover { color: #fff; background: rgba(255,255,255,0.07); }
.nav-link.active { color: #fff; background: #2563eb; box-shadow: 0 2px 8px rgba(37,99,235,0.35); }

/* Icon-only when not expanded */
.sidebar-nav:not(.is-expanded) .nav-link { justify-content: center; padding-left: 0; padding-right: 0; }

/* Labels: visible when expanded, hidden when collapsed */
.sidebar-nav .nav-label {
    transition: opacity 150ms, max-width 200ms;
    max-width: 200px; opacity: 1; overflow: hidden;
}
.sidebar-nav:not(.is-expanded) .nav-label {
    max-width: 0; opacity: 0; pointer-events: none;
}

/* Section labels */
.sidebar-nav:not(.is-expanded) .nav-section-label {
    max-height: 0; opacity: 0; overflow: hidden;
    padding-top: 0 !important; padding-bottom: 0 !important;
    margin: 0 !important; pointer-events: none; transition: all 150ms;
}

/* Tooltip when sidebar is collapsed */
.sidebar-nav:not(.is-expanded) .nav-link[title]:hover::after {
    content: attr(title);
    position: absolute;
    left: calc(100% + 12px);
    top: 50%; transform: translateY(-50%);
    background: #1e293b;
    color: #f1f5f9;
    padding: 5px 12px;
    border-radius: 7px;
    font-size: 12px; font-weight: 500;
    white-space: nowrap; z-index: 9999;
    border: 1px solid #334155;
    box-shadow: 0 4px 16px rgba(0,0,0,0.5);
    pointer-events: none;
}

/* Notification badge collapsed state */
.sidebar-nav:not(.is-expanded) .nav-badge-count {
    position: absolute; top: 3px; right: 3px;
    width: 8px; height: 8px; padding: 0; font-size: 0;
    border-radius: 50%; min-width: 0;
}

[x-cloak] { display: none !important; }


</style>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
