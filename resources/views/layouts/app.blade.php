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
        </div>
        
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
            <!-- <a href="#" class="nav-item">
                <i class="fas fa-video"></i>
                {{ __('messages.nav.video') }}
            </a> -->
            <!-- <a href="{{ route('fleet') }}" class="nav-item {{ request()->routeIs('fleet') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                {{ __('messages.nav.fleet') }}
            </a> -->
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
        // Profile & Language Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Dropdown
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileMenu');
            
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileMenu.classList.toggle('show');
                    // Fermer le menu de langue si ouvert
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
                    // Fermer le menu profil si ouvert
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
