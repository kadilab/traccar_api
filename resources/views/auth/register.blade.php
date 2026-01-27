<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuration initiale - GeoTrack Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e88e5;
            --primary-dark: #1565c0;
            --secondary: #7556D6;
            --accent: #00c6ff;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-600: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--dark);
            overflow: hidden;
        }
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(117, 86, 214, 0.25) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(30, 136, 229, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.15) 0%, transparent 60%);
        }
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 450px;
        }
        .setup-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 45px;
            color: white;
            box-shadow: 0 20px 60px rgba(117, 86, 214, 0.4);
        }
        .left-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .left-title span {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .left-description {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            margin-bottom: 3rem;
            line-height: 1.7;
        }
        .setup-steps {
            text-align: left;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
        }
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 1.5rem;
        }
        .step-item:last-child { margin-bottom: 0; }
        .step-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .step-content h4 {
            color: white;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .step-content p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin: 0;
        }
        .right-panel {
            width: 520px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            overflow-y: auto;
        }
        .register-header { margin-bottom: 2rem; }
        .register-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--warning);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .register-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .register-header p { color: var(--gray-600); }
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--gray-100);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            background: white;
            box-shadow: 0 0 0 4px rgba(117, 86, 214, 0.1);
        }
        .input-group { position: relative; }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }
        .input-group .form-control { padding-left: 2.75rem; }
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-600);
            cursor: pointer;
            padding: 0;
        }
        .form-hint {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .form-hint i { color: var(--primary); }
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        .btn-register {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1.5rem;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(117, 86, 214, 0.3);
        }
        .btn-register:active { transform: translateY(0); }
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        .alert-danger i { font-size: 1.2rem; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }
        .back-link:hover { color: var(--secondary); }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray-600);
        }
        .login-link a {
            color: var(--secondary);
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover { text-decoration: underline; }
        .admin-notice {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .admin-notice i {
            color: var(--success);
            font-size: 1.2rem;
            margin-top: 2px;
        }
        .admin-notice-content h5 {
            color: var(--success);
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }
        .admin-notice-content p {
            color: var(--gray-600);
            font-size: 0.85rem;
            margin: 0;
        }
        @media (max-width: 991px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; max-width: 100%; }
        }
        @media (max-width: 576px) {
            .right-panel { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="grid-pattern"></div>
        <div class="left-content">
            <div class="setup-icon">
                <i class="fas fa-cog"></i>
            </div>
            <h2 class="left-title">Configuration <span>initiale</span></h2>
            <p class="left-description">
                Bienvenue dans l'assistant d'installation de GeoTrack Pro. 
                Créez votre compte administrateur pour commencer.
            </p>
            <div class="setup-steps">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Créer le compte admin</h4>
                        <p>Ce compte aura tous les droits d'administration</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Configurer vos appareils</h4>
                        <p>Ajoutez vos traceurs GPS à la plateforme</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Inviter votre équipe</h4>
                        <p>Créez des comptes pour vos utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <a href="/" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Retour à l'accueil
        </a>
        <div class="register-header">
            <div class="register-badge">
                <i class="fas fa-shield-alt"></i>
                Compte Administrateur
            </div>
            <h1>Créer votre compte</h1>
            <p>Ce sera le compte principal avec tous les droits d'administration</p>
        </div>
        <div class="admin-notice">
            <i class="fas fa-info-circle"></i>
            <div class="admin-notice-content">
                <h5>Première installation</h5>
                <p>Cette page n'est accessible que lors de la configuration initiale. Une fois le compte admin créé, elle sera désactivée.</p>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name') }}" placeholder="Jean Dupont" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email') }}" placeholder="admin@votreentreprise.com" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="••••••••" required onkeyup="checkPasswordStrength(this.value)">
                    <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                        <i class="fas fa-eye" id="toggleIcon1"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <div class="form-hint">
                    <i class="fas fa-info-circle"></i>
                    Minimum 8 caractères avec majuscules et chiffres
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                           placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                        <i class="fas fa-eye" id="toggleIcon2"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-register">
                <i class="fas fa-rocket"></i>
                Créer le compte administrateur
            </button>
        </form>
        <div class="login-link">
            <p>Déjà configuré ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </div>
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strengthBar');
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/)) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            bar.style.width = strength + '%';
            if (strength <= 25) {
                bar.style.background = '#ef4444';
            } else if (strength <= 50) {
                bar.style.background = '#f59e0b';
            } else if (strength <= 75) {
                bar.style.background = '#3b82f6';
            } else {
                bar.style.background = '#10b981';
            }
        }
    </script>
</body>
</html>
