<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - GeoTrack Pro</title>
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
                radial-gradient(circle at 20% 30%, rgba(30, 136, 229, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(117, 86, 214, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(0, 198, 255, 0.1) 0%, transparent 60%);
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
            max-width: 500px;
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 3rem;
        }
        .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        .brand-text {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }
        .illustration-container {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .dashboard-preview {
            background: linear-gradient(135deg, rgba(30, 136, 229, 0.1) 0%, rgba(117, 86, 214, 0.1) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
        }
        .dashboard-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .dashboard-dots {
            display: flex;
            gap: 6px;
        }
        .dashboard-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .dashboard-dots span:nth-child(1) { background: #ef4444; }
        .dashboard-dots span:nth-child(2) { background: #f59e0b; }
        .dashboard-dots span:nth-child(3) { background: #10b981; }
        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
            text-align: left;
        }
        .stat-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }
        .stat-card .label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }
        .stat-card.primary .value { color: var(--primary); }
        .stat-card.secondary .value { color: var(--secondary); }
        .stat-card.accent .value { color: var(--accent); }
        .stat-card.success .value { color: var(--success); }
        .floating-notification {
            position: absolute;
            right: -20px;
            top: 20px;
            background: white;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .floating-notification i {
            color: var(--success);
            font-size: 1.2rem;
        }
        .floating-notification span {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--dark);
        }
        .feature-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }
        .feature-item i {
            color: var(--success);
        }
        .right-panel {
            width: 480px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }
        .login-header { margin-bottom: 2rem; }
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .login-header p { color: var(--gray-600); }
        .form-group { margin-bottom: 1.5rem; }
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
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(30, 136, 229, 0.1);
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
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-check-input {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .form-check-label {
            font-size: 0.9rem;
            color: var(--gray-600);
            cursor: pointer;
        }
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(30, 136, 229, 0.3);
        }
        .btn-login:active { transform: translateY(0); }
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
        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
        .alert i { font-size: 1.2rem; }
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
        .back-link:hover { color: var(--primary); }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray-600);
        }
        .register-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .register-link a:hover { text-decoration: underline; }
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
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-satellite-dish"></i>
                </div>
                <span class="brand-text">GeoTrack Pro</span>
            </div>
            <div class="illustration-container">
                <div class="dashboard-preview">
                    <div class="dashboard-header">
                        <div class="dashboard-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="dashboard-content">
                        <div class="stat-card primary">
                            <div class="value">24</div>
                            <div class="label">Véhicules actifs</div>
                        </div>
                        <div class="stat-card success">
                            <div class="value">98%</div>
                            <div class="label">Disponibilité</div>
                        </div>
                        <div class="stat-card secondary">
                            <div class="value">12.5k</div>
                            <div class="label">Km parcourus</div>
                        </div>
                        <div class="stat-card accent">
                            <div class="value">156</div>
                            <div class="label">Alertes traitées</div>
                        </div>
                    </div>
                    <div class="floating-notification">
                        <i class="fas fa-check-circle"></i>
                        <span>Véhicule arrivé</span>
                    </div>
                </div>
            </div>
            <div class="feature-list">
                <div class="feature-item">
                    <i class="fas fa-check"></i>
                    <span>Suivi en temps réel</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check"></i>
                    <span>Alertes instantanées</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check"></i>
                    <span>Rapports détaillés</span>
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <a href="/" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Retour à l'accueil
        </a>
        <div class="login-header">
            <h1>Bienvenue</h1>
            <p>Connectez-vous pour accéder à votre tableau de bord</p>
        </div>
        
        @if (session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        
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
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email') }}" placeholder="votre@email.com" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            <div class="form-options">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Se connecter
            </button>
        </form>
        
        @if(!\App\Models\User::where('administrator', true)->exists())
        <div class="register-link">
            <p>Première installation ? <a href="{{ route('register') }}">Créer le compte administrateur</a></p>
        </div>
        @endif
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
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
    </script>
</body>
</html>
