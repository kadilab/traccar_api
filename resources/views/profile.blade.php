@extends('layouts.app')

@section('title', 'Profil Utilisateur - Traccar TF')

@section('content')

<div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar device-sidebar">
        <div class="sidebar-profile">
            <div class="profile-avatar">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'U') . '&background=7556D6&color=fff&size=150' }}" 
                     alt="Avatar"
                     class="avatar-img">
            </div>
            <h3 class="profile-username">{{ Auth::user()->name }}</h3>
            <p class="profile-email-small">{{ Auth::user()->email }}</p>
            @if(Auth::user()->administrator)
                <span class="badge badge-admin">
                    <i class="fas fa-crown me-1"></i>Admin
                </span>
            @endif
        </div>

        <nav class="sidebar-nav">
            <a href="#information" class="nav-link active" data-tab="information">
                <i class="fas fa-user-circle"></i>
                <span>Informations</span>
            </a>
            <a href="#security" class="nav-link" data-tab="security">
                <i class="fas fa-lock"></i>
                <span>Sécurité</span>
            </a>
            <a href="#preferences" class="nav-link" data-tab="preferences">
                <i class="fas fa-sliders-h"></i>
                <span>Préférences</span>
            </a>
            <a href="#notifications" class="nav-link" data-tab="notifications">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
            <a href="#activity" class="nav-link" data-tab="activity">
                <i class="fas fa-history"></i>
                <span>Activité</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-card">
                <!-- Informations Tab -->
                <section class="tab-panel active" id="information">
                    <div class="tab-header">
                        <h2>Informations Personnelles</h2>
                        <p class="tab-subtitle">Gérez vos informations de profil</p>
                    </div>

                    <div class="card-section">
                        <form id="profileForm">
                            <div class="form-section">
                                <h3 class="section-title">Informations de Base</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstName" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName" 
                                                   value="{{ explode(' ', Auth::user()->name)[0] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName"
                                                   value="{{ implode(' ', array_slice(explode(' ', Auth::user()->name), 1)) ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ Auth::user()->email }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="+33 6 XX XX XX XX">
                                </div>

                                <div class="form-group">
                                    <label for="company" class="form-label">Entreprise</label>
                                    <input type="text" class="form-control" id="company" name="company" 
                                           placeholder="Nom de votre entreprise">
                                </div>

                                <div class="form-group">
                                    <label for="jobTitle" class="form-label">Poste</label>
                                    <input type="text" class="form-control" id="jobTitle" name="jobTitle" 
                                           placeholder="Votre poste">
                                </div>

                                <div class="form-group">
                                    <label for="bio" class="form-label">Biographie</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" 
                                             placeholder="Décrivez-vous..."></textarea>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3 class="section-title">Localisation</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country" class="form-label">Pays</label>
                                            <input type="text" class="form-control" id="country" name="country" 
                                                   placeholder="France">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="form-label">Ville</label>
                                            <input type="text" class="form-control" id="city" name="city" 
                                                   placeholder="Paris">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" id="btnCancelProfile">
                                    Annuler
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Security Tab -->
                <section class="tab-panel" id="security">
                    <div class="tab-header">
                        <h2>Sécurité</h2>
                        <p class="tab-subtitle">Gérez la sécurité de votre compte</p>
                    </div>
                    <!-- Change Password -->
                    <div class="card-section">
                        <div class="section-header">
                            <div>
                                <h3 class="section-title">
                                    <i class="fas fa-key"></i> Changer le Mot de Passe
                                </h3>
                                <p class="section-description">Mettez à jour votre mot de passe tous les 3 mois</p>
                            </div>
                        </div>

                        <form id="passwordForm">
                            <div class="form-group">
                                <label for="currentPassword" class="form-label">
                                    <i class="fas fa-lock"></i> Mot de passe actuel
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" 
                                           placeholder="Entrez votre mot de passe actuel">
                                    <button type="button" class="password-toggle" onclick="togglePasswordVisibility('currentPassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="newPassword" class="form-label">
                                            <i class="fas fa-lock-open"></i> Nouveau mot de passe
                                        </label>
                                        <div class="password-input-wrapper">
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" 
                                                   placeholder="Minimum 8 caractères" onkeyup="updatePasswordStrength()">
                                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('newPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirmPassword" class="form-label">
                                            <i class="fas fa-check-circle"></i> Confirmer le mot de passe
                                        </label>
                                        <div class="password-input-wrapper">
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" 
                                                   placeholder="Confirmez le mot de passe">
                                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirmPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="password-strength">
                                <span class="strength-label">Force:</span>
                                <div class="strength-bar">
                                    <div class="strength-indicator" id="strengthIndicator" style="width: 0%; background: #e5e7eb;"></div>
                                </div>
                                <span id="strengthText" class="strength-label" style="margin-left: 10px; color: #6b7280;">Faible</span>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key me-1"></i>Mettre à jour le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Two Factor Authentication -->
                    <div class="card-section">
                        <div class="section-header">
                            <div>
                                <h3 class="section-title">
                                    <i class="fas fa-shield-alt"></i> Authentification à Deux Facteurs
                                </h3>
                                <p class="section-description">Ajoutez une couche de sécurité supplémentaire</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="btnEnable2FA">
                                <i class="fas fa-plus me-1"></i>Activer
                            </button>
                        </div>
                        
                        <div style="padding: 0 20px;">
                            <p class="text-muted" style="margin: 0; font-size: 13px; line-height: 1.6;">
                                L'authentification à deux facteurs ajoute une couche supplémentaire de sécurité à votre compte 
                                en exigeant un code de vérification en plus de votre mot de passe.
                            </p>
                        </div>
                    </div>

                    <!-- Active Sessions -->
                    <div class="card-section">
                        <div class="section-header">
                            <div>
                                <h3 class="section-title">
                                    <i class="fas fa-desktop"></i> Sessions Actives
                                </h3>
                                <p class="section-description">Appareils connectés à votre compte</p>
                            </div>
                        </div>
                        
                        <div class="session-list">
                            <div class="session-item">
                                <div class="session-device">
                                    <i class="fas fa-desktop"></i>
                                    <div>
                                        <p class="session-name">Windows - Chrome</p>
                                        <p class="session-details">IP: 192.168.1.1 • Algérie</p>
                                    </div>
                                </div>
                                <div class="session-meta">
                                    <span class="session-status" style="background: #dcfce7; color: #166534;">
                                        <i class="fas fa-check me-1"></i>Actif
                                    </span>
                                    <small class="text-muted">Dernière activité: il y a 5 min</small>
                                </div>
                            </div>

                            <div class="session-item">
                                <div class="session-device">
                                    <i class="fas fa-mobile-alt"></i>
                                    <div>
                                        <p class="session-name">iPhone 14 - Safari</p>
                                        <p class="session-details">IP: 192.168.1.50 • Algérie</p>
                                    </div>
                                </div>
                                <div class="session-meta">
                                    <span class="session-status" style="background: #dcfce7; color: #166534;">
                                        <i class="fas fa-check me-1"></i>Actif
                                    </span>
                                    <small class="text-muted">Dernière activité: il y a 1h</small>
                                </div>
                            </div>

                            <div class="session-item">
                                <div class="session-device">
                                    <i class="fas fa-tablet-alt"></i>
                                    <div>
                                        <p class="session-name">iPad - Safari</p>
                                        <p class="session-details">IP: 192.168.1.75 • Algérie</p>
                                    </div>
                                </div>
                                <div class="session-meta">
                                    <span class="session-status" style="background: #fee2e2; color: #991b1b;">
                                        <i class="fas fa-times me-1"></i>Inactif
                                    </span>
                                    <small class="text-muted">Dernière activité: il y a 3 jours</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Preferences Tab -->
                <section class="tab-panel" id="preferences">
                    <div class="tab-header">
                        <h2>
                            <i class="fas fa-sliders-h me-2"></i>Préférences
                        </h2>
                        <p class="tab-subtitle">Personnalisez votre expérience utilisateur</p>
                    </div>

                    <!-- Display Preferences -->
                    <div class="card-section">
                        <h3 class="section-title">
                            <i class="fas fa-palette"></i> Affichage
                        </h3>
                        <div class="preferences-group">
                            <div class="preference-item">
                                <div class="preference-info">
                                    <label for="theme" class="form-label">
                                        <i class="fas fa-moon me-1"></i>Thème
                                    </label>
                                    <p class="text-muted">Choisissez votre thème préféré</p>
                                </div>
                                <select class="form-select" id="theme" name="theme">
                                    <option value="light">☀️ Clair</option>
                                    <option value="dark">🌙 Sombre</option>
                                    <option value="auto">🔄 Automatique</option>
                                </select>
                            </div>

                            <div class="preference-item">
                                <div class="preference-info">
                                    <label for="language" class="form-label">
                                        <i class="fas fa-globe me-1"></i>Langue
                                    </label>
                                    <p class="text-muted">Sélectionnez votre langue préférée</p>
                                </div>
                                <select class="form-select" id="language" name="language">
                                    <option value="fr">🇫🇷 Français</option>
                                    <option value="en">🇬🇧 English</option>
                                    <option value="es">🇪🇸 Español</option>
                                    <option value="de">🇩🇪 Deutsch</option>
                                    <option value="ar">🇩🇿 العربية</option>
                                </select>
                            </div>

                            <div class="preference-item">
                                <div class="preference-info">
                                    <label for="dateFormat" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Format de la Date
                                    </label>
                                    <p class="text-muted">Comment afficher les dates</p>
                                </div>
                                <select class="form-select" id="dateFormat" name="dateFormat">
                                    <option value="dd/mm/yyyy">JJ/MM/AAAA</option>
                                    <option value="mm/dd/yyyy">MM/JJ/AAAA</option>
                                    <option value="yyyy-mm-dd">AAAA-MM-JJ</option>
                                </select>
                            </div>

                            <div class="preference-item">
                                <div class="preference-info">
                                    <label for="timeFormat" class="form-label">
                                        <i class="fas fa-clock me-1"></i>Format de l'Heure
                                    </label>
                                    <p class="text-muted">Affichage au format 12h ou 24h</p>
                                </div>
                                <select class="form-select" id="timeFormat" name="timeFormat">
                                    <option value="12h">12 heures (AM/PM)</option>
                                    <option value="24h">24 heures</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Preferences -->
                    <div class="card-section">
                        <h3 class="section-title">
                            <i class="fas fa-lock"></i> Confidentialité
                        </h3>
                        <div class="preferences-group">
                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-eye me-1"></i>Profil Public
                                    </label>
                                    <p class="text-muted">Permettre aux autres de voir votre profil</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="publicProfile">
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Afficher l'Email
                                    </label>
                                    <p class="text-muted">Afficher votre email sur votre profil</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showEmail">
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Tracker ma Localisation
                                    </label>
                                    <p class="text-muted">Permettre au système de connaître ma localisation</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="trackLocation" checked>
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-user-secret me-1"></i>Mode Privé
                                    </label>
                                    <p class="text-muted">Masquer votre statut de connexion</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privateMode">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Enregistrer les Préférences
                        </button>
                    </div>
                </section>

                <!-- Notifications Tab -->
                <section class="tab-panel" id="notifications">
                    <div class="tab-header">
                        <h2>
                            <i class="fas fa-bell me-2"></i>Notifications
                        </h2>
                        <p class="tab-subtitle">Gérez vos préférences de notification</p>
                    </div>

                    <!-- Email Notifications -->
                    <div class="card-section">
                        <h3 class="section-title">
                            <i class="fas fa-envelope"></i> Notifications par Email
                        </h3>
                        <div class="preferences-group">
                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-shield-alt me-1"></i>Alertes de Sécurité
                                    </label>
                                    <p class="text-muted">Connexion à partir d'un nouvel appareil</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-rocket me-1"></i>Mises à Jour du Système
                                    </label>
                                    <p class="text-muted">Nouvelles fonctionnalités et améliorations</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-chart-bar me-1"></i>Rapports Hebdomadaires
                                    </label>
                                    <p class="text-muted">Résumé hebdomadaire de votre activité</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-car me-1"></i>Alertes de Véhicules
                                    </label>
                                    <p class="text-muted">Problèmes ou alertes de véhicules</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Push Notifications -->
                    <div class="card-section">
                        <h3 class="section-title">
                            <i class="fas fa-bell"></i> Notifications Push
                        </h3>
                        <div class="preferences-group">
                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-flash me-1"></i>Alertes en Temps Réel
                                    </label>
                                    <p class="text-muted">Recevoir les alertes immédiatement</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-lightbulb me-1"></i>Suggestions
                                    </label>
                                    <p class="text-muted">Suggestions personnalisées</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </div>

                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-envelope-open-text me-1"></i>Newsletters
                                    </label>
                                    <p class="text-muted">Contenus éducatifs et conseils pratiques</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Schedule -->
                    <div class="card-section">
                        <h3 class="section-title">
                            <i class="fas fa-clock"></i> Période Silencieuse
                        </h3>
                        <p class="text-muted" style="margin-bottom: 15px;">
                            Définissez une période durant laquelle vous ne souhaitez pas recevoir de notifications
                        </p>
                        <div class="preferences-group">
                            <div class="preference-toggle">
                                <div class="preference-info">
                                    <label class="form-label">
                                        <i class="fas fa-moon me-1"></i>Activer la Période Silencieuse
                                    </label>
                                    <p class="text-muted">Les notifications seront restreintes durant cette période</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="quietHoursEnabled">
                                </div>
                            </div>

                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quietStart" class="form-label">Heure de Début</label>
                                        <input type="time" class="form-control" id="quietStart" value="22:00" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quietEnd" class="form-label">Heure de Fin</label>
                                        <input type="time" class="form-control" id="quietEnd" value="08:00" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Enregistrer les Notifications
                        </button>
                    </div>
                </section>

                <!-- Activity Tab -->
                <section class="tab-panel" id="activity">
                    <div class="tab-header">
                        <h2>
                            <i class="fas fa-history me-2"></i>Activité Récente
                        </h2>
                        <p class="tab-subtitle">Historique complet de vos actions</p>
                    </div>

                    <!-- Activity Statistics -->
                    <div class="card-section">
                        <h3 class="section-title">
                            <i class="fas fa-chart-pie"></i> Statistiques d'Activité
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 20px;">
                            <div style="background: linear-gradient(135deg, #1e88e5 0%, #1976d2 100%); color: white; padding: 20px; border-radius: 10px; text-align: center;">
                                <div style="font-size: 28px; font-weight: 700; margin-bottom: 5px;">12</div>
                                <div style="font-size: 12px; opacity: 0.9;">Connexions</div>
                            </div>
                            <div style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; padding: 20px; border-radius: 10px; text-align: center;">
                                <div style="font-size: 28px; font-weight: 700; margin-bottom: 5px;">45</div>
                                <div style="font-size: 12px; opacity: 0.9;">Modifications</div>
                            </div>
                            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px; border-radius: 10px; text-align: center;">
                                <div style="font-size: 28px; font-weight: 700; margin-bottom: 5px;">28</div>
                                <div style="font-size: 12px; opacity: 0.9;">Appareils Ajoutés</div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Filters -->
                    <div class="card-section">
                        <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                            <select class="form-select" id="activityFilter" style="max-width: 250px;">
                                <option value="">
                                    <i class="fas fa-filter me-1"></i>Tous les événements
                                </option>
                                <option value="login">
                                    <i class="fas fa-sign-in-alt me-1"></i>Connexions
                                </option>
                                <option value="devices">
                                    <i class="fas fa-car me-1"></i>Appareils
                                </option>
                                <option value="settings">
                                    <i class="fas fa-cog me-1"></i>Paramètres
                                </option>
                                <option value="security">
                                    <i class="fas fa-shield-alt me-1"></i>Sécurité
                                </option>
                            </select>
                            <input type="date" class="form-control" style="max-width: 200px;" id="activityDate">
                        </div>

                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon login">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-check-circle me-1" style="color: #10b981;"></i>Connexion réussie
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-chrome me-1"></i>Chrome sur Windows • <i class="fas fa-map-pin me-1"></i>Algérie (192.168.1.1)
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 2 heures
                                    </p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon devices">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-plus-circle me-1" style="color: #22c55e;"></i>Ajout de 3 nouveaux véhicules
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-folder me-1"></i>Groupe "Véhicules de livraison" • <strong>Toyota, Honda, BMW</strong>
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 5 heures
                                    </p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon settings">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-user-edit me-1" style="color: #f59e0b;"></i>Modification du profil
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-phone me-1"></i>Téléphone et <i class="fas fa-envelope me-1"></i>Email mise à jour
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 1 jour
                                    </p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon login">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-check-circle me-1" style="color: #10b981;"></i>Connexion réussie
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-firefox me-1"></i>Firefox sur Ubuntu • <i class="fas fa-map-pin me-1"></i>Algérie (192.168.1.50)
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 2 jours
                                    </p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon devices">
                                    <i class="fas fa-trash"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-trash me-1" style="color: #ef4444;"></i>Suppression d'appareils
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-car me-1"></i>2 appareils supprimés du système • <strong>Maruti, Tata</strong>
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 3 jours
                                    </p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon settings">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-lock me-1" style="color: #7556D6;"></i>Changement du mot de passe
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-check-circle me-1"></i>Mot de passe mis à jour avec succès
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 1 semaine
                                    </p>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon login">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="activity-details">
                                    <p class="activity-title">
                                        <i class="fas fa-exclamation-circle me-1" style="color: #ef4444;"></i>Tentative de connexion échouée
                                    </p>
                                    <p class="activity-meta">
                                        <i class="fas fa-shield me-1"></i>Compte potentiellement à risque • <i class="fas fa-map-pin me-1"></i>IP inconnue
                                    </p>
                                    <p class="activity-time">
                                        <i class="fas fa-clock me-1"></i>Il y a 1 semaine
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Load More -->
                    <div style="display: flex; justify-content: center; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" style="width: auto;">
                            <i class="fas fa-chevron-down me-1"></i>Charger plus d'activités
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Navigation
        const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
        const tabPanels = document.querySelectorAll('.tab-panel');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tab = this.getAttribute('data-tab');

                // Remove active class from all items and panels
                navLinks.forEach(l => l.classList.remove('active'));
                tabPanels.forEach(p => p.classList.remove('active'));

                // Add active class to clicked item and corresponding panel
                this.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });

        // Password Strength Indicator
        const newPasswordInput = document.getElementById('newPassword');
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value);
            });
        }

        // Quiet Hours Toggle
        const quietHoursCheckbox = document.getElementById('quietHoursEnabled');
        if (quietHoursCheckbox) {
            quietHoursCheckbox.addEventListener('change', function() {
                const quietStart = document.getElementById('quietStart');
                const quietEnd = document.getElementById('quietEnd');
                if (quietStart && quietEnd) {
                    quietStart.disabled = !this.checked;
                    quietEnd.disabled = !this.checked;
                }
            });
        }

        // Form Submission
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showNotification('success', 'Profil mis à jour avec succès!');
            });
        }

        const passwordForm = document.getElementById('passwordForm');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const newPass = document.getElementById('newPassword').value;
                const confirmPass = document.getElementById('confirmPassword').value;
                
                if (newPass !== confirmPass) {
                    showNotification('error', 'Les mots de passe ne correspondent pas!');
                    return;
                }
                
                if (newPass.length < 8) {
                    showNotification('error', 'Le mot de passe doit contenir au moins 8 caractères!');
                    return;
                }
                
                showNotification('success', 'Mot de passe changé avec succès!');
            });
        }

        // Activity Filter
        const activityFilter = document.getElementById('activityFilter');
        if (activityFilter) {
            activityFilter.addEventListener('change', function() {
                filterActivities(this.value);
            });
        }

        // Date Filter for Activity
        const activityDate = document.getElementById('activityDate');
        if (activityDate) {
            activityDate.addEventListener('change', function() {
                filterActivitiesByDate(this.value);
            });
        }

        // Initialize
        if (navLinks.length > 0) {
            navLinks[0].click();
        }
    });

    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const button = event.currentTarget;
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function updatePasswordStrength(password) {
        const indicator = document.getElementById('strengthIndicator');
        const strengthText = document.getElementById('strengthText');
        if (!indicator) return;

        let strength = 0;
        let feedback = 'Très Faible';

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        const strengthLevels = {
            0: { width: '0%', color: '#e5e7eb', text: 'Très Faible' },
            1: { width: '25%', color: '#ef4444', text: 'Faible' },
            2: { width: '50%', color: '#f59e0b', text: 'Moyen' },
            3: { width: '75%', color: '#3b82f6', text: 'Bon' },
            4: { width: '100%', color: '#10b981', text: 'Excellent' }
        };

        const level = strengthLevels[strength];
        indicator.style.width = level.width;
        indicator.style.backgroundColor = level.color;
        if (strengthText) {
            strengthText.textContent = level.text;
            strengthText.style.color = level.color;
        }
    }

    function filterActivities(type) {
        const activityItems = document.querySelectorAll('.activity-item');
        activityItems.forEach(item => {
            if (!type) {
                item.style.display = 'flex';
            } else {
                const icon = item.querySelector('.activity-icon');
                if (icon.classList.contains(type)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }

    function filterActivitiesByDate(date) {
        // Placeholder for date filtering
        console.log('Filter by date:', date);
    }

    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease;
            font-weight: 600;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush

@push('styles')
<style>
/* Profile Page Styles - Adapted to App Theme */

/* Sidebar Profile Section */
.sidebar-profile {
    padding: 25px 20px;
    text-align: center;
    background: linear-gradient(135deg, #1e88e5 0%, #1976d2 100%);
    color: #fff;
    border-bottom: 2px solid #1565c0;
    position: relative;
    overflow: hidden;
}

.sidebar-profile::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(20px); }
}

.profile-avatar {
    margin: 0 0 15px;
    position: relative;
    z-index: 1;
}

.profile-avatar .avatar-img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.profile-avatar .avatar-img:hover {
    transform: scale(1.05);
}

.profile-username {
    margin: 0 0 6px;
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    position: relative;
    z-index: 1;
}

.profile-email-small {
    margin: 0 0 12px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.85);
    position: relative;
    z-index: 1;
}

.badge-admin {
    display: inline-block;
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    position: relative;
    z-index: 1;
}

/* Sidebar Navigation */
.sidebar-nav {
    padding: 15px 0;
}

.sidebar-nav .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    margin: 0 8px;
    color: #64748b;
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 8px;
    position: relative;
}

.sidebar-nav .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 3px;
    height: 0;
    background: #1976d2;
    transform: translateY(-50%);
    border-radius: 0 3px 3px 0;
    transition: height 0.3s ease;
}

.sidebar-nav .nav-link:hover {
    background: #e3f2fd;
    color: #1976d2;
    transform: translateX(4px);
}

.sidebar-nav .nav-link:hover::before {
    height: 24px;
}

.sidebar-nav .nav-link.active {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1976d2;
    font-weight: 600;
}

.sidebar-nav .nav-link.active::before {
    height: 24px;
}

.sidebar-nav .nav-link i {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

/* Main Content Card */
.content-card {
    background: #fff;
    border-radius: 12px;
    overflow: visible;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    scroll-padding-top: 120px;
}

.card-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 30px;
    background: linear-gradient(135deg, #1e88e5 0%, #1976d2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
}

.card-header-custom h3 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-header-custom h3 i {
    font-size: 1.3rem;
}

.realtime-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.realtime-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
    box-shadow: 0 0 8px #10b981;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Profile Content Wrapper */
.profile-content-wrapper {
    padding: 30px;
}

/* Tab Panels */
.tab-panel {
    display: none;
    animation: slideIn 0.4s ease;
    padding: 0;
}

.tab-panel.active {
    display: block;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tab-header {
    margin: 0 -30px 30px -30px;
    padding: 30px 30px 25px 30px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-bottom: 2px solid #1976d2;
    border-radius: 8px 8px 0 0;
}

.tab-header h2 {
    margin: 0 0 8px;
    font-size: 26px;
    font-weight: 700;
    color: #1976d2;
    display: flex;
    align-items: center;
    gap: 12px;
}

.tab-header h2 i {
    font-size: 24px;
}

.tab-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

/* Card Sections */
.card-section {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 24px;
    margin: 0 30px 24px 30px;
    transition: all 0.3s ease;
}

.tab-panel .card-section:first-of-type {
    margin-top: 30px;
}

.card-section:hover {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.card-section:last-child {
    margin-bottom: 0;
}

.section-title {
    margin: 0 0 18px;
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 4px;
    background: #1976d2;
    border-radius: 50%;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.section-header > div {
    flex: 1;
}

/* Form Styles */
.form-section {
    margin-bottom: 20px;
}

.form-section:last-child {
    margin-bottom: 0;
}

.row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.col-md-6 {
    flex: 1;
}

.form-group {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #374151;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-control,
.form-select {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    color: #374151;
    background: #fff;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
    background: #f8f9fa;
}

.form-control:disabled {
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
}

.form-control::placeholder {
    color: #9ca3af;
}

/* Password Input */
.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    font-size: 14px;
    transition: color 0.3s ease;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle:hover {
    color: #1976d2;
}

/* Password Strength */
.password-strength {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
    padding: 12px;
    background: #f3f4f6;
    border-radius: 8px;
}

.strength-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    white-space: nowrap;
}

.strength-bar {
    flex: 1;
    height: 6px;
    background: #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
}

.strength-indicator {
    height: 100%;
    border-radius: 10px;
    background: #e0e0e0;
    transition: all 0.4s ease;
    width: 0%;
}

/* Preferences */
.preferences-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.preference-item,
.preference-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.preference-item:hover,
.preference-toggle:hover {
    background: #f0f7ff;
    border-color: #1976d2;
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.1);
}

.preference-info {
    flex: 1;
}

.preference-info .form-label {
    margin-bottom: 3px;
}

.form-check {
    display: flex;
    align-items: center;
    height: 24px;
}

.form-check-input {
    width: 48px;
    height: 26px;
    cursor: pointer;
    appearance: none;
    background: #d1d5db;
    border-radius: 13px;
    position: relative;
    transition: all 0.3s ease;
}

.form-check-input:checked {
    background: #1976d2;
}

.form-check-input::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: #fff;
    border-radius: 50%;
    top: 3px;
    left: 3px;
    transition: left 0.3s ease;
}

.form-check-input:checked::after {
    left: 25px;
}

/* Session List */
.session-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.session-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #fff;
    border-radius: 8px;
    border-left: 4px solid #1976d2;
    transition: all 0.3s ease;
}

.session-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateX(2px);
}

.session-device {
    display: flex;
    align-items: center;
    gap: 12px;
}

.session-device i {
    font-size: 20px;
    color: #1976d2;
    width: 24px;
    text-align: center;
}

.session-name {
    margin: 0;
    font-weight: 600;
    color: #334155;
    font-size: 13px;
}

.session-details {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 11px;
}

.session-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.session-status {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 12px;
    background: #dcfce7;
    color: #166534;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Activity List */
.activity-filters {
    margin-bottom: 20px;
}

.activity-filters .form-select {
    max-width: 250px;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.activity-item {
    display: flex;
    gap: 14px;
    padding: 16px;
    background: #fff;
    border-radius: 8px;
    border-left: 4px solid #1976d2;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: #f0f7ff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateX(2px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    font-weight: 600;
}

.activity-icon.login {
    background: rgba(25, 118, 210, 0.1);
    color: #1976d2;
}

.activity-icon.devices {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

.activity-icon.settings {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.activity-details {
    flex: 1;
    min-width: 0;
}

.activity-title {
    margin: 0 0 3px;
    font-weight: 600;
    color: #334155;
    font-size: 13px;
}

.activity-meta {
    margin: 0 0 3px;
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.activity-time {
    margin: 0;
    font-size: 11px;
    color: #9ca3af;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding-top: 15px;
    margin-top: 15px;
    border-top: 1px solid #e0e0e0;
}

/* Buttons */
.btn {
    padding: 11px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.btn-primary {
    background: linear-gradient(135deg, #1e88e5 0%, #1976d2 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.25);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(25, 118, 210, 0.35);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-secondary {
    background: #e0e0e0;
    color: #334155;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #d1d5db;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 8px 14px;
    font-size: 12px;
}

.text-muted {
    color: #64748b;
}

.text-danger {
    color: #ef4444;
}

/* Responsive */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
        gap: 0;
    }

    .col-md-6 {
        flex: 1;
    }

    .card-header-custom {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .session-item,
    .activity-item {
        flex-direction: column;
        gap: 10px;
    }

    .session-meta {
        align-items: flex-start;
    }
}
</style>
@endpush
