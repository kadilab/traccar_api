@extends('layouts.app')

@section('title', 'Mon Profil — ' . setting('app_name', 'GeoTrack Pro'))
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'Gérez vos informations personnelles et vos préférences')

@section('content')
<div x-data="profilePage()" class="max-w-5xl mx-auto">

    {{-- ─── Profile header card ─────────────────────────────────────────── --}}
    <div class="card p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <img id="avatar-preview"
                     src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'U').'&background=3b82f6&color=fff&size=150' }}"
                     alt="Avatar"
                     class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-800 shadow-xl">
                <button @click="$refs.avatarInput.click()"
                        class="absolute -bottom-2 -right-2 w-7 h-7 rounded-full bg-blue-600 hover:bg-blue-500 text-white flex items-center justify-center shadow-lg transition-colors">
                    <i class="fas fa-camera text-[10px]"></i>
                </button>
                <input type="file" x-ref="avatarInput" accept="image/*" @change="previewAvatar($event)" class="hidden">
            </div>

            {{-- Info --}}
            <div class="text-center sm:text-left flex-1">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
                <p class="text-gray-500 dark:text-slate-400 text-sm">{{ Auth::user()->email }}</p>
                <div class="flex items-center justify-center sm:justify-start gap-2 mt-2">
                    @if(Auth::user()->administrator ?? false)
                        <span class="badge-info">
                            <i class="fas fa-crown text-[10px] mr-1"></i>Administrateur
                        </span>
                    @else
                        <span class="badge-info bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                            Utilisateur
                        </span>
                    @endif
                    <span class="text-xs text-gray-400 dark:text-slate-500">
                        Membre depuis {{ Auth::user()->created_at?->format('M Y') ?? '—' }}
                    </span>
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="hidden sm:grid grid-cols-3 gap-6 text-center ml-auto">
                @foreach([['—','Appareils'],['—','Groupes'],['—','Alertes']] as $s)
                <div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $s[0] }}</div>
                    <div class="text-xs text-gray-400 dark:text-slate-500">{{ $s[1] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ─── Tab nav ──────────────────────────────────────────────────────── --}}
    <div class="flex gap-1 bg-gray-100 dark:bg-slate-800/50 p-1 rounded-xl mb-6 overflow-x-auto">
        @foreach([
            ['information',   'fa-user',           'Informations'],
            ['security',      'fa-lock',           'Sécurité'],
            ['preferences',   'fa-sliders-h',      'Préférences'],
            ['notifications', 'fa-bell',           'Notifications'],
            ['activity',      'fa-clock',          'Activité'],
        ] as [$id, $icon, $label])
        <button @click="activeTab = '{{ $id }}'"
                :class="activeTab === '{{ $id }}' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap flex-shrink-0">
            <i class="fas {{ $icon }} text-xs"></i>
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ─── Flash messages ──────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm">
        <i class="fas fa-check-circle flex-shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm">
        <i class="fas fa-exclamation-circle flex-shrink-0"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- ═══════════════ TAB: INFORMATIONS ══════════════════════════════════ --}}
    <div x-show="activeTab === 'information'" x-cloak>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card p-6 space-y-5">
            @csrf @method('PUT')
            <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3">Informations personnelles</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                           class="input-field" placeholder="Votre nom">
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Adresse e-mail</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                           class="input-field" placeholder="email@exemple.com">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Téléphone</label>
                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                           class="input-field" placeholder="+213 6xx xxx xxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Langue</label>
                    <select name="locale" class="input-field">
                        @foreach(['fr' => 'Français', 'en' => 'English', 'ar' => 'العربية'] as $code => $name)
                        <option value="{{ $code }}" {{ (old('locale', app()->getLocale()) === $code) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2 text-xs"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════ TAB: SÉCURITÉ ══════════════════════════════════════ --}}
    <div x-show="activeTab === 'security'" x-cloak>
        <form method="POST" action="{{ route('profile.password') }}" class="card p-6 space-y-5"
              x-data="{ showOld: false, showNew: false, showConf: false }">
            @csrf @method('PUT')
            <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3">Changer le mot de passe</h3>

            @foreach([
                ['current_password', 'showOld', 'Mot de passe actuel',      'Entrez votre mot de passe actuel'],
                ['password',         'showNew', 'Nouveau mot de passe',      'Min. 8 caractères'],
                ['password_confirmation', 'showConf', 'Confirmer le mot de passe', 'Répétez le nouveau mot de passe'],
            ] as [$fname, $xvar, $lbl, $ph])
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">{{ $lbl }}</label>
                <div class="relative">
                    <input :type="{{ $xvar }} ? 'text' : 'password'" name="{{ $fname }}"
                           class="input-field pr-10" placeholder="{{ $ph }}">
                    <button type="button" @click="{{ $xvar }} = !{{ $xvar }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 transition-colors">
                        <i :class="{{ $xvar }} ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                    </button>
                </div>
                @error($fname)<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach

            {{-- Strength indicator --}}
            <div id="pwd-strength" class="hidden">
                <div class="flex gap-1 mb-1">
                    @for($i=0;$i<4;$i++)
                    <div class="h-1 flex-1 rounded-full bg-gray-200 dark:bg-slate-700 strength-bar"></div>
                    @endfor
                </div>
                <p class="text-xs text-gray-400 dark:text-slate-500" id="pwd-strength-label"></p>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-key mr-2 text-xs"></i>Changer le mot de passe
                </button>
            </div>
        </form>

        {{-- Sessions --}}
        <div class="card p-6 mt-6">
            <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3 mb-4">Sessions actives</h3>
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                    <i class="fas fa-desktop text-emerald-600 dark:text-emerald-400 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Session actuelle</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ request()->ip() }} · {{ request()->userAgent() ? substr(request()->userAgent(), 0, 60).'…' : 'Navigateur inconnu' }}</p>
                </div>
                <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">Active</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════ TAB: PRÉFÉRENCES ═══════════════════════════════════ --}}
    <div x-show="activeTab === 'preferences'" x-cloak>
        <form method="POST" action="{{ route('profile.preferences') }}" class="card p-6 space-y-6">
            @csrf @method('PUT')
            <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3">Préférences d'affichage</h3>

            {{-- Dark mode --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Mode sombre</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Interface en thème sombre</p>
                </div>
                <button type="button" @click="$dispatch('toggle-dark')"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                        :class="$root.closest('[x-data]').__x.$data.dark ? 'bg-blue-600' : 'bg-gray-200 dark:bg-slate-700'">
                    <span class="inline-block h-4 w-4 rounded-full bg-white transform transition-transform duration-200"
                          :class="$root.closest('[x-data]').__x.$data.dark ? 'translate-x-6' : 'translate-x-1'"></span>
                </button>
            </div>

            {{-- Timezone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Fuseau horaire</label>
                <select name="timezone" class="input-field">
                    @foreach(['Africa/Algiers' => 'Alger (UTC+1)', 'Europe/Paris' => 'Paris (UTC+1/+2)', 'UTC' => 'UTC'] as $tz => $label)
                    <option value="{{ $tz }}" {{ old('timezone', Auth::user()->timezone ?? 'Africa/Algiers') === $tz ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Units --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Unité de distance</label>
                <div class="flex gap-2">
                    @foreach(['km' => 'Kilomètres (km)', 'mi' => 'Miles (mi)'] as $unit => $label)
                    <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5 rounded-xl border transition-all flex-1
                                  {{ old('distance_unit', Auth::user()->distance_unit ?? 'km') === $unit
                                     ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300'
                                     : 'border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:border-blue-300' }}">
                        <input type="radio" name="distance_unit" value="{{ $unit }}" class="text-blue-600"
                               {{ old('distance_unit', Auth::user()->distance_unit ?? 'km') === $unit ? 'checked' : '' }}>
                        <span class="text-sm font-medium">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2 text-xs"></i>Enregistrer les préférences
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════ TAB: NOTIFICATIONS ═════════════════════════════════ --}}
    <div x-show="activeTab === 'notifications'" x-cloak>
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3 mb-5">Préférences de notification</h3>
            <div class="space-y-4">
                @foreach([
                    ['Alertes de géofence',    'Notifier lors des entrées/sorties de zone', 'notif_geofence'],
                    ['Alertes d\'appareil',    'Déconnexion, batterie faible, excès de vitesse', 'notif_device'],
                    ['Rapports hebdomadaires', 'Résumé de la semaine par e-mail', 'notif_weekly'],
                    ['Alertes critiques',      'Alarmes urgentes et alertes de sécurité', 'notif_critical'],
                ] as [$title, $desc, $name])
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-slate-700/50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $title }}</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ $desc }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer" x-data="{ on: true }">
                        <input type="checkbox" name="{{ $name }}" class="sr-only" :checked="on" @change="on = !on">
                        <div class="h-6 w-11 rounded-full transition-colors duration-200"
                             :class="on ? 'bg-blue-600' : 'bg-gray-200 dark:bg-slate-700'">
                            <div class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transform transition-transform duration-200"
                                 :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
            <div class="flex justify-end mt-4">
                <button class="btn-primary">
                    <i class="fas fa-save mr-2 text-xs"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════ TAB: ACTIVITÉ ═══════════════════════════════════════ --}}
    <div x-show="activeTab === 'activity'" x-cloak>
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3 mb-5">Journal d'activité récente</h3>
            <div id="activity-log" class="space-y-3">
                <div class="flex items-center gap-3 py-3 border-b border-gray-50 dark:border-slate-800">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-sign-in-alt text-blue-600 dark:text-blue-400 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Connexion</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500">{{ Auth::user()->last_login_at ?? now()->format('d/m/Y H:i') }} · {{ request()->ip() }}</p>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-slate-500 flex-shrink-0">Aujourd'hui</span>
                </div>
                <p class="text-center text-sm text-gray-400 dark:text-slate-500 py-6">Historique complet non disponible</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function profilePage() {
    return {
        activeTab: '{{ session('activeTab', 'information') }}',

        previewAvatar(event) {
            const file = event.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            if (file.size > 2 * 1024 * 1024) {
                showToast('Image trop grande (max 2 Mo)', 'warning');
                return;
            }
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    };
}

// Password strength indicator
const pwdInput = document.querySelector('input[name="password"]');
if (pwdInput) {
    pwdInput.addEventListener('input', function() {
        const val = this.value;
        const bars = document.querySelectorAll('.strength-bar');
        const label = document.getElementById('pwd-strength-label');
        const indicator = document.getElementById('pwd-strength');
        if (!val) { indicator.classList.add('hidden'); return; }
        indicator.classList.remove('hidden');
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const colors = ['bg-red-500','bg-orange-400','bg-yellow-400','bg-emerald-500'];
        const labels = ['Très faible','Faible','Moyen','Fort'];
        bars.forEach((b, i) => {
            b.className = 'h-1 flex-1 rounded-full ' + (i < score ? colors[score-1] : 'bg-gray-200 dark:bg-slate-700');
        });
        label.textContent = labels[score-1] || '';
    });
}
</script>
@endpush
