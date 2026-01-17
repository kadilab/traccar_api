<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TraccarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;

use Illuminate\Support\Facades\Route;

// Route pour changer de langue (accessible à tous)
Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification (accessibles uniquement aux invités)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes protégées (nécessitent une authentification)
Route::middleware('auth')->group(function () {
    // ============================================
    // PAGES ACCESSIBLES À TOUS LES UTILISATEURS
    // ============================================
    
    // Monitor - suivi temps réel (page d'accueil pour non-admins)
    Route::get('/monitor', function () {
        return view('monitor');
    })->name('monitor');

    // Device - gestion des appareils
    Route::get('/device', function () {
        return view('device');
    })->name('device');
    Route::get('/devices', function () {
        return view('device');
    })->name('devices.index');

    // Geofence - gestion des géobarrières
    Route::get('/geofence', function () {
        return view('geofence');
    })->name('geofence');

    // History - historique des véhicules
    Route::get('/history', function () {
        return view('history');
    })->name('history');

    // Tracking - localisation en temps réel
    Route::get('/tracking', function () {
        return view('tracking');
    })->name('tracking');

    // Events/Notifications - gestion des événements
    Route::get('/events', function () {
        return view('events');
    })->name('events');

    // Profile - profil utilisateur (accessible à tous)
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ============================================
    // PAGES RÉSERVÉES AUX ADMINISTRATEURS
    // ============================================
    Route::middleware('admin')->group(function () {
        // Dashboard avec statistiques (admin seulement)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::post('/dashboard/refresh', [DashboardController::class, 'refreshStats'])->name('dashboard.refresh');
        
        // Groupe - gestion des groupes (admin seulement)
        Route::get('/groupe', function () {
            return view('groupe');
        })->name('groupe');
        
        // Account - gestion des utilisateurs
        Route::get('/account', function () {
            return view('account');
        })->name('account');
        Route::get('/account/index', function () {
            return view('account');
        })->name('account.index');

        // Attributs - gestion des attributs personnalisés
        Route::get('/attributs', function () {
            return view('attributs');
        })->name('attributs');

        // Reports - rapports
        Route::get('/reports', function () {
            return view('reports');
        })->name('reports');

        // Commandes - gestion des commandes
        Route::get('/commandes', function () {
            return view('commandes');
        })->name('commandes');

        // POI - Points d'intérêt
        Route::get('/poi', function () {
            return view('poi');
        })->name('poi.index');

        // Alerts - gestion des alertes
        Route::get('/alerts', function () {
            return view('alerts');
        })->name('alerts.index');

        // Fleet - gestion de flotte
        Route::get('/fleet', function () {
            return view('fleet');
        })->name('fleet');
    });
});

