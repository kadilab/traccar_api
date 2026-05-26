<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ dark: localStorage.getItem('darkMode') === 'true', showPwd: false }"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.login.title') ?? 'Connexion' }} — {{ setting('app_name', 'GeoTrack Pro') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-sans antialiased flex">

{{-- ─── Left panel (branding, hidden on mobile) ───────────────────────── --}}
<div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex-col justify-between p-12">

    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(99,102,241,0.5) 1px, transparent 0); background-size: 32px 32px;"></div>

    {{-- Animated glow blobs --}}
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay:1s"></div>

    {{-- Logo --}}
    <div class="relative z-10 flex items-center gap-3">
        @php $logo = setting('app_logo'); @endphp
        @if($logo)
            <img src="{{ Storage::url($logo) }}" alt="Logo" class="h-10 w-auto">
        @else
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i class="fas fa-map-marker-alt text-white"></i>
            </div>
        @endif
        <div>
            <span class="text-white font-bold text-lg leading-none block">{{ setting('app_name', 'GeoTrack Pro') }}</span>
            <span class="text-blue-400 text-xs font-medium">GPS Fleet Management</span>
        </div>
    </div>

    {{-- Center content --}}
    <div class="relative z-10 space-y-6">
        <h1 class="text-4xl xl:text-5xl font-bold text-white leading-tight">
            Gérez votre flotte<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">en temps réel</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-md leading-relaxed">
            Plateforme complète de suivi GPS — surveillance en direct, historique, alertes géofencing et rapports détaillés.
        </p>

        {{-- Feature pills --}}
        <div class="flex flex-wrap gap-3">
            @foreach(['Suivi en direct', 'Alertes intelligentes', 'Rapports avancés', 'Géofencing'] as $feat)
            <span class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm font-medium">
                <i class="fas fa-check-circle text-xs text-blue-400"></i>
                {{ $feat }}
            </span>
            @endforeach
        </div>
    </div>

    {{-- Stats strip --}}
    <div class="relative z-10 grid grid-cols-3 gap-6">
        @foreach([['fa-satellite-dish','Appareils actifs','—'], ['fa-shield-alt','Uptime','99.9%'], ['fa-bolt','Temps réel','<1s']] as $s)
        <div>
            <i class="fas {{ $s[0] }} text-blue-400 text-xl mb-2 block"></i>
            <div class="text-2xl font-bold text-white">{{ $s[2] }}</div>
            <div class="text-slate-500 text-xs mt-0.5">{{ $s[1] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ─── Right panel (login form) ──────────────────────────────────────── --}}
<div class="w-full lg:w-1/2 xl:w-2/5 flex flex-col justify-center items-center px-6 sm:px-12 py-12 bg-gray-950 relative">

    {{-- Dark mode toggle --}}
    <button @click="dark = !dark; localStorage.setItem('darkMode', dark)"
            class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 transition-colors">
        <i x-show="!dark" class="fas fa-moon text-sm"></i>
        <i x-show="dark"  class="fas fa-sun text-sm text-amber-400"></i>
    </button>

    <div class="w-full max-w-sm">

        {{-- Mobile logo --}}
        <div class="lg:hidden flex items-center gap-3 mb-8">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-white text-sm"></i>
            </div>
            <span class="text-white font-bold">{{ setting('app_name', 'GeoTrack Pro') }}</span>
        </div>

        {{-- Heading --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white">Bon retour 👋</h2>
            <p class="text-slate-500 text-sm mt-1">Connectez-vous à votre espace de gestion</p>
        </div>

        {{-- Session errors --}}
        @if(session('error'))
        <div class="mb-4 flex items-start gap-3 p-3.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 flex items-start gap-3 p-3.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
            <ul class="list-none space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Language switcher --}}
        <div class="flex gap-1 mb-6 bg-slate-800/50 p-1 rounded-xl">
            @foreach(['fr' => '🇫🇷 FR', 'en' => '🇬🇧 EN', 'ar' => '🇸🇦 AR'] as $code => $label)
            <a href="{{ route('lang.switch', $code) }}"
               class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold transition-all
                      {{ app()->getLocale() === $code
                         ? 'bg-blue-600 text-white shadow'
                         : 'text-slate-500 hover:text-slate-300' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Adresse e-mail</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-500 text-sm"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all
                                  @error('email') border-red-500 @enderror"
                           placeholder="nom@exemple.com">
                </div>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-500 text-sm"></i>
                    </div>
                    <input :type="showPwd ? 'text' : 'password'" name="password" required autocomplete="current-password"
                           class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all
                                  @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    <button type="button" @click="showPwd = !showPwd"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors">
                        <i :class="showPwd ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                    </button>
                </div>
                @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Remember + forgot --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500/50">
                    <span class="text-sm text-slate-400">Se souvenir de moi</span>
                </label>
                <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Mot de passe oublié ?</a>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm
                           transition-all duration-200 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-950
                           flex items-center justify-center gap-2 mt-2">
                <i class="fas fa-sign-in-alt text-sm"></i>
                Se connecter
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-center text-slate-600 text-xs mt-8">
            © {{ date('Y') }} {{ setting('app_name', 'GeoTrack Pro') }}
            @if(setting('company_name')) · {{ setting('company_name') }} @endif
        </p>
    </div>
</div>

</body>
</html>
