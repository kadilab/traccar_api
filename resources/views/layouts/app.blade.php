<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Traccar TF')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

        /* Notification Icon */
        .notification-icon {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: #ffffff;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .notification-icon:hover {
            background: rgba(117, 86, 214, 0.1);
            color: #d8d7db;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .notification-badge.hidden {
            display: none;
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
            <a href="{{ route('commandes') }}" class="nav-item {{ request()->routeIs('commandes') ? 'active' : '' }}">
                <i class="fas fa-terminal"></i>
                Commandes
            </a>
            
            {{-- Gestion utilisateurs pour admins et managers --}}
            @if(Auth::user()->administrator ?? false || Auth::user()->isManager())
            <a href="{{ route('account') }}" class="nav-item {{ request()->routeIs('account') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                {{ __('messages.nav.account') }}
            </a>
            @endif
            
            {{-- Pages supplémentaires réservées aux administrateurs --}}
            @if(Auth::user()->administrator ?? false)
            <a href="{{ route('groupe') }}" class="nav-item {{ request()->routeIs('groupe') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i>
                {{ __('messages.nav.groupe') }}
            </a>
            <a href="{{ route('drivers.index') }}" class="nav-item {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i>
                Conducteurs
            </a>
            <a href="{{ route('attribute') }}" class="nav-item {{ request()->routeIs('attribute', 'attributes') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i>
                {{ __('messages.nav.attributs') }}
            </a>
            @endif
           
        </nav>

        <div class="header-right">
            <!-- Notifications -->
            <a href="{{ route('events') }}" class="notification-icon" id="notificationBtn" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notificationBadge">0</span>
            </a>

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

            // Load notifications count (recent events from last 24h)
            async function loadNotificationsCount() {
                try {
                    const response = await fetch('/api/traccar/events/recent');
                    const data = await response.json();
                    const count = data.count || 0;
                    const badge = document.getElementById('notificationBadge');
                    
                    if (badge) {
                        badge.textContent = count > 99 ? '99+' : count;
                        if (count === 0) {
                            badge.classList.add('hidden');
                        } else {
                            badge.classList.remove('hidden');
                        }
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des notifications:', error);
                }
            }

            // Load notifications on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadNotificationsCount);
            } else {
                loadNotificationsCount();
            }

            // Refresh notifications every 30 seconds
            setInterval(loadNotificationsCount, 30000);
        });
    </script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Global SweetAlert2 Helper Functions -->
    <script>
        // Configure SweetAlert2 defaults
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Global helper functions
        window.showSuccess = function(message, title = 'Succès') {
            return Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonColor: '#7556D6'
            });
        };

        window.showError = function(message, title = 'Erreur') {
            return Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonColor: '#dc3545'
            });
        };

        window.showWarning = function(message, title = 'Attention') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'OK'
            });
        };

        window.showInfo = function(message, title = 'Information') {
            return Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                confirmButtonColor: '#1e88e5'
            });
        };

        window.showToast = function(message, type = 'success') {
            Toast.fire({
                icon: type,
                title: message
            });
        };

        window.showConfirm = function(message, title = 'Confirmation', confirmText = 'Oui, confirmer', cancelText = 'Annuler') {
            return Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7556D6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true
            });
        };

        window.showDeleteConfirm = function(itemName = 'cet élément') {
            return Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: `Vous êtes sur le point de supprimer <strong>${itemName}</strong>.<br>Cette action est irréversible !`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            });
        };

        window.showLoading = function(message = 'Chargement en cours...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        };

        window.hideLoading = function() {
            Swal.close();
        };

        window.showInputPrompt = function(title, inputPlaceholder = '', inputValue = '') {
            return Swal.fire({
                title: title,
                input: 'text',
                inputPlaceholder: inputPlaceholder,
                inputValue: inputValue,
                showCancelButton: true,
                confirmButtonColor: '#7556D6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Valider',
                cancelButtonText: 'Annuler',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Veuillez entrer une valeur';
                    }
                }
            });
        };
    </script>
    
    @stack('scripts')
</body>
</html>
