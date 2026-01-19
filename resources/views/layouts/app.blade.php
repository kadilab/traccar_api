<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Traccar TF')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if(app()->getLocale() === 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Mobile Navigation */
        .mobile-nav-toggle {
            display: none;
            background: rgba(255,255,255,0.15);
            border: none;
            padding: 8px 10px;
            border-radius: 6px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            margin-left: 10px;
        }
        .nav-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.35);
            z-index: 999;
        }
        @media (max-width: 991px) {
            .nav-menu {
                position: fixed;
                top: 0;
                left: -280px;
                width: 260px;
                height: 100vh;
                background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
                flex-direction: column;
                align-items: flex-start;
                gap: 0;
                padding: 60px 0 20px 0;
                box-shadow: 2px 0 16px rgba(0,0,0,0.15);
                transition: left 0.3s;
                z-index: 1001;
                overflow-y: auto;
            }
            .nav-menu.open {
                left: 0;
            }
            .nav-menu .nav-item {
                width: 100%;
                border-radius: 0;
                padding: 14px 24px;
                font-size: 15px;
            }
            .nav-overlay.active {
                display: block;
            }
            .mobile-nav-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .header-right {
                margin-left: auto;
            }
        }
        @media (max-width: 600px) {
            .header {
                padding: 0 6px;
            }
            .logo-text {
                display: none;
            }
            .profile-details {
                display: none;
            }
            .nav-menu {
                width: 80vw;
                min-width: 200px;
                max-width: 280px;
            }
        }
    </style>
    @stack('styles')
   
</head>
<body class="@yield('body-class')">
    @auth
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="logo-container">
                <i class="fas fa-layer-group logo-icon"></i>
                <span class="logo-text">Tracker</span>
            </div>
            <button class="mobile-nav-toggle" aria-label="Ouvrir le menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="nav-overlay"></div>
        <nav class="nav-menu">
            {{-- Pages réservées aux administrateurs --}}
            @if(Auth::user()->administrator ?? false)
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                {{ __('messages.nav.dashboard') }}
            </a>
            @endif
            <a href="{{ route('monitor') }}" class="nav-item {{ request()->routeIs('monitor') ? 'active' : '' }}">
                <i class="fas fa-desktop"></i>
                {{ __('messages.nav.monitor') }}
            </a>
            <a href="{{ route('device') }}" class="nav-item {{ request()->routeIs('device') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                {{ __('messages.nav.device') }}
            </a>
            <a href="{{ route('geofence') }}" class="nav-item {{ request()->routeIs('geofence') ? 'active' : '' }}">
                <i class="fas fa-draw-polygon"></i>
                {{ __('messages.nav.geofence') }}
            </a>
            <a href="{{ route('events') }}" class="nav-item {{ request()->routeIs('events') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                {{ __('messages.nav.events') }}
            </a>
            
            {{-- Pages supplémentaires réservées aux administrateurs --}}
            @if(Auth::user()->administrator ?? false)
            <a href="{{ route('groupe') }}" class="nav-item {{ request()->routeIs('groupe') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i>
                {{ __('messages.nav.groupe') }}
            </a>
            <a href="{{ route('account') }}" class="nav-item {{ request()->routeIs('account') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                {{ __('messages.nav.account') }}
            </a>
            <a href="{{ route('attributs') }}" class="nav-item {{ request()->routeIs('attributs') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i>
                {{ __('messages.nav.attributs') }}
            </a>
            @endif
           
        </nav>

        <div class="header-right">
            <!-- Language Selector -->
            <div class="language-dropdown">
                <button class="language-btn" id="languageDropdownBtn">
                    @php
                        $currentLocale = app()->getLocale();
                        $languages = [
                            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
                            'en' => ['name' => 'English', 'flag' => '🇬🇧'],
                            'ar' => ['name' => 'العربية', 'flag' => '🇸🇦']
                        ];
                    @endphp
                    <span class="lang-flag">{{ $languages[$currentLocale]['flag'] ?? '🌐' }}</span>
                    <span class="lang-code">{{ strtoupper($currentLocale) }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="language-menu" id="languageMenu">
                    @foreach($languages as $code => $lang)
                        <a href="{{ route('lang.switch', $code) }}" 
                           class="language-menu-item {{ $currentLocale === $code ? 'active' : '' }}">
                            <span class="lang-flag">{{ $lang['flag'] }}</span>
                            <span class="lang-name">{{ $lang['name'] }}</span>
                            @if($currentLocale === $code)
                                <i class="fas fa-check"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="profile-dropdown">
                <div class="profile-info">
                    <div class="profile-details">
                        <span class="profile-name">{{ Auth::user()->name ?? __('messages.common.user') }}</span>
                        @if(Auth::user()->administrator)
                            <span class="profile-role">{{ __('messages.roles.admin') }}</span>
                        @else
                            <span class="profile-role">{{ __('messages.roles.user') }}</span>
                        @endif
                    </div>
                    <button class="profile-avatar-btn" id="profileDropdownBtn">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'U') . '&background=random' }}" 
                             alt="Avatar" 
                             class="profile-avatar">
                    </button>
                </div>
                <div class="profile-menu" id="profileMenu">
                    <a href="{{ route('profile') }}" class="profile-menu-item">
                        <i class="fas fa-user"></i>
                        {{ __('messages.nav.profile') }}
                    </a>
                    <a href="{{ route('profile') }}#preferences" class="profile-menu-item">
                        <i class="fas fa-cog"></i>
                        {{ __('messages.common.settings') }}
                    </a>
                    <div class="profile-menu-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-menu-item logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            {{ __('messages.auth.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    @endauth

    @yield('content')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile nav toggle
            const mobileToggle = document.querySelector('.mobile-nav-toggle');
            const navMenu = document.querySelector('.nav-menu');
            const navOverlay = document.querySelector('.nav-overlay');
            function toggleMobileMenu() {
                navMenu.classList.toggle('open');
                navOverlay.classList.toggle('active');
            }
            if (mobileToggle && navMenu && navOverlay) {
                mobileToggle.addEventListener('click', toggleMobileMenu);
                navOverlay.addEventListener('click', toggleMobileMenu);
            }
            // Fermer le menu mobile si on clique sur un lien
            document.querySelectorAll('.nav-menu .nav-item').forEach(item => {
                item.addEventListener('click', function() {
                    if (navMenu.classList.contains('open')) {
                        navMenu.classList.remove('open');
                        navOverlay.classList.remove('active');
                    }
                });
            });

            // Profile Dropdown
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileMenu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileMenu.classList.toggle('show');
                    document.getElementById('languageMenu')?.classList.remove('show');
                });
            }

            // Language Dropdown
            const languageBtn = document.getElementById('languageDropdownBtn');
            const languageMenu = document.getElementById('languageMenu');
            if (languageBtn && languageMenu) {
                languageBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    languageMenu.classList.toggle('show');
                    document.getElementById('profileMenu')?.classList.remove('show');
                });
            }

            // Fermer les menus si on clique ailleurs
            document.addEventListener('click', function(e) {
                if (profileMenu && !profileMenu.contains(e.target) && !profileBtn?.contains(e.target)) {
                    profileMenu.classList.remove('show');
                }
                if (languageMenu && !languageMenu.contains(e.target) && !languageBtn?.contains(e.target)) {
                    languageMenu.classList.remove('show');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
