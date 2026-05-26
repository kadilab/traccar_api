@extends('layouts.app')

@section('title', 'Paramètres — ' . setting('app_name', 'GeoTrack Pro'))
@section('page-title', 'Paramètres')
@section('page-subtitle', 'Configuration générale de l\'application')

@section('content')
<div x-data="{ tab: 'general' }" class="max-w-4xl mx-auto">

    {{-- ─── Flash ────────────────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm">
        <i class="fas fa-check-circle flex-shrink-0"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm">
        <i class="fas fa-exclamation-circle flex-shrink-0"></i>{{ session('error') }}
    </div>
    @endif

    {{-- ─── Tab nav ──────────────────────────────────────────────────────── --}}
    <div class="flex gap-1 bg-gray-100 dark:bg-slate-800/50 p-1 rounded-xl mb-6 overflow-x-auto">
        @foreach([
            ['general',    'fa-cog',        'Général'],
            ['appearance', 'fa-palette',    'Apparence'],
            ['company',    'fa-building',   'Entreprise'],
        ] as [$id, $icon, $label])
        <button @click="tab = '{{ $id }}'"
                :class="tab === '{{ $id }}' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap flex-shrink-0">
            <i class="fas {{ $icon }} text-xs"></i>{{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ═══════════════ GÉNÉRAL ════════════════════════════════════════════ --}}
    <div x-show="tab === 'general'" x-cloak>
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- App name & basics --}}
            <div class="card p-6 space-y-5">
                <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>Informations générales
                </h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Nom de l'application</label>
                    <input type="text" name="app_name" value="{{ $settings->firstWhere('key','app_name')?->value ?? 'GeoTrack Pro' }}"
                           class="input-field" placeholder="GeoTrack Pro">
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Affiché dans l'onglet du navigateur et le sidebar</p>
                </div>

                {{-- Logo upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Logo de l'application</label>
                    <div class="flex items-start gap-4">
                        @php $currentLogo = $settings->firstWhere('key','app_logo')?->value; @endphp
                        <div class="w-16 h-16 rounded-xl border-2 border-dashed border-gray-300 dark:border-slate-600 flex items-center justify-center overflow-hidden flex-shrink-0 bg-gray-50 dark:bg-slate-800">
                            @if($currentLogo)
                                <img id="logo-preview" src="{{ Storage::url($currentLogo) }}" alt="Logo" class="w-full h-full object-contain p-1">
                            @else
                                <div id="logo-placeholder" class="text-center">
                                    <i class="fas fa-image text-gray-300 dark:text-slate-600 text-xl block mb-0.5"></i>
                                    <span class="text-[10px] text-gray-400 dark:text-slate-500">Logo</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="app_logo" id="logo-input" accept="image/*"
                                   class="hidden" onchange="previewImage(this, 'logo-preview', 'logo-placeholder')">
                            <button type="button" onclick="document.getElementById('logo-input').click()"
                                    class="btn-secondary text-sm">
                                <i class="fas fa-upload mr-2 text-xs"></i>Choisir un logo
                            </button>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1.5">PNG, JPG, SVG · Max 2 Mo · Recommandé 200×60px</p>
                        </div>
                    </div>
                </div>

                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Favicon</label>
                    <div class="flex items-start gap-4">
                        @php $currentFavicon = $settings->firstWhere('key','app_favicon')?->value; @endphp
                        <div class="w-10 h-10 rounded-lg border-2 border-dashed border-gray-300 dark:border-slate-600 flex items-center justify-center overflow-hidden flex-shrink-0 bg-gray-50 dark:bg-slate-800">
                            @if($currentFavicon)
                                <img id="fav-preview" src="{{ Storage::url($currentFavicon) }}" alt="Favicon" class="w-full h-full object-contain">
                            @else
                                <i id="fav-placeholder" class="fas fa-star text-gray-300 dark:text-slate-600 text-sm"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="app_favicon" id="fav-input" accept="image/*,image/x-icon"
                                   class="hidden" onchange="previewImage(this, 'fav-preview', 'fav-placeholder')">
                            <button type="button" onclick="document.getElementById('fav-input').click()"
                                    class="btn-secondary text-sm">
                                <i class="fas fa-upload mr-2 text-xs"></i>Choisir un favicon
                            </button>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1.5">ICO, PNG · 32×32px ou 64×64px</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2 text-xs"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════ APPARENCE ══════════════════════════════════════════ --}}
    <div x-show="tab === 'appearance'" x-cloak>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf

            <div class="card p-6 space-y-5">
                <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3">
                    <i class="fas fa-palette text-purple-500 mr-2"></i>Couleurs et thème
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach([
                        ['primary_color',   'Couleur principale',   '#3b82f6'],
                        ['secondary_color', 'Couleur secondaire',   '#7556D6'],
                        ['sidebar_color',   'Couleur sidebar',      '#0f172a'],
                    ] as [$key, $label, $default])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">{{ $label }}</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="{{ $key }}"
                                   value="{{ $settings->firstWhere('key',$key)?->value ?? $default }}"
                                   class="w-12 h-10 rounded-lg border border-gray-200 dark:border-slate-700 cursor-pointer bg-transparent p-0.5">
                            <input type="text" value="{{ $settings->firstWhere('key',$key)?->value ?? $default }}"
                                   class="input-field font-mono text-sm flex-1"
                                   readonly>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Dark mode default --}}
                <div class="flex items-center justify-between py-3 border-t border-gray-100 dark:border-slate-700">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Mode sombre par défaut</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Les nouveaux utilisateurs voient l'interface en mode sombre</p>
                    </div>
                    @php $darkDefault = $settings->firstWhere('key','dark_mode_default')?->value ?? '0'; @endphp
                    <label class="relative inline-flex cursor-pointer" x-data="{ on: {{ $darkDefault === '1' ? 'true' : 'false' }} }">
                        <input type="hidden" name="dark_mode_default" :value="on ? '1' : '0'">
                        <button type="button" @click="on = !on"
                                class="h-6 w-11 rounded-full transition-colors duration-200 focus:outline-none"
                                :class="on ? 'bg-blue-600' : 'bg-gray-200 dark:bg-slate-700'">
                            <div class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transform transition-transform duration-200"
                                 :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                        </button>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2 text-xs"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════ ENTREPRISE ══════════════════════════════════════════ --}}
    <div x-show="tab === 'company'" x-cloak>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf

            <div class="card p-6 space-y-5">
                <h3 class="font-semibold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 pb-3">
                    <i class="fas fa-building text-amber-500 mr-2"></i>Informations entreprise
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach([
                        ['company_name',    'text',  'Nom de l\'entreprise',  'Ma Société'],
                        ['company_email',   'email', 'E-mail de contact',     'contact@exemple.com'],
                        ['company_phone',   'tel',   'Téléphone',             '+213 6xx xxx xxx'],
                        ['company_address', 'text',  'Adresse',               '123 Rue de la Flotte'],
                    ] as [$key, $type, $label, $placeholder])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">{{ $label }}</label>
                        <input type="{{ $type }}" name="{{ $key }}"
                               value="{{ $settings->firstWhere('key',$key)?->value ?? '' }}"
                               class="input-field" placeholder="{{ $placeholder }}">
                    </div>
                    @endforeach
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Texte de pied de page</label>
                    <input type="text" name="footer_text"
                           value="{{ $settings->firstWhere('key','footer_text')?->value ?? '' }}"
                           class="input-field" placeholder="Tous droits réservés · Solutions GPS">
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Affiché dans le footer de l'application</p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2 text-xs"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId, placeholderId) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) { showToast('Fichier trop grand (max 2 Mo)', 'warning'); input.value = ''; return; }
    const reader = new FileReader();
    reader.onload = function(e) {
        let preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        if (!preview) {
            preview = document.createElement('img');
            preview.id = previewId;
            preview.className = 'w-full h-full object-contain p-1';
            if (placeholder) placeholder.replaceWith(preview);
        }
        preview.src = e.target.result;
        if (placeholder) placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

// Sync color picker ↔ text input
document.querySelectorAll('input[type="color"]').forEach(picker => {
    const textInput = picker.nextElementSibling;
    picker.addEventListener('input', () => textInput.value = picker.value);
});
</script>
@endpush
