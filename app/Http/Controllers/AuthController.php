<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SecurityAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Vérifier si l'utilisateur est désactivé
            if ($user->disabled) {
                Auth::logout();
                SecurityAuditService::logLoginFailed($credentials['email'], 'account_disabled');
                throw ValidationException::withMessages([
                    'email' => ['Ce compte a été désactivé. Veuillez contacter l\'administrateur.'],
                ]);
            }
            
            // Vérifier si le compte est expiré
            if ($user->isExpired()) {
                Auth::logout();
                SecurityAuditService::logLoginFailed($credentials['email'], 'account_expired');
                throw ValidationException::withMessages([
                    'email' => ['Ce compte a expiré. Veuillez contacter l\'administrateur.'],
                ]);
            }
            
            $request->session()->regenerate();

            // Stocker les credentials cryptés pour pouvoir recréer la session Traccar
            $request->session()->put('traccar_credentials', encrypt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]));

            // Créer une session Traccar et stocker le cookie
            $this->createTraccarSession($request, $credentials['email'], $credentials['password']);

            // Logger la connexion réussie
            SecurityAuditService::logLogin($user->id, $user->email);

            // Rediriger vers dashboard si admin, sinon vers monitor
            $redirect = $user->administrator ? '/dashboard' : '/monitor';
            return redirect()->intended($redirect);
        }

        SecurityAuditService::logLoginFailed($credentials['email'], 'invalid_credentials');
        
        throw ValidationException::withMessages([
            'email' => ['Les identifiants fournis sont incorrects.'],
        ]);
    }

    /**
     * Créer une session Traccar et stocker le cookie JSESSIONID
     */
    private function createTraccarSession(Request $request, string $email, string $password): void
    {
        try {
            $traccarUrl = config('traccar.url');
            
            $response = Http::asForm()->post($traccarUrl . 'session', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                // Extraire le cookie JSESSIONID de la réponse
                $cookies = $response->cookies();
                foreach ($cookies as $cookie) {
                    if ($cookie->getName() === 'JSESSIONID') {
                        // Stocker le cookie dans la session Laravel
                        $request->session()->put('traccar_session_id', $cookie->getValue());
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas bloquer le login
            \Log::warning('Impossible de créer la session Traccar: ' . $e->getMessage());
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $userId = auth()->id();
        
        // Supprimer la session Traccar
        $this->deleteTraccarSession($request);
        
        // Logger la déconnexion
        if ($userId) {
            SecurityAuditService::logLogout($userId);
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Supprimer la session Traccar
     */
    private function deleteTraccarSession(Request $request): void
    {
        try {
            $sessionId = $request->session()->get('traccar_session_id');
            if ($sessionId) {
                $traccarUrl = config('traccar.url');
                Http::withCookies(['JSESSIONID' => $sessionId], parse_url($traccarUrl, PHP_URL_HOST))
                    ->delete($traccarUrl . 'session');
            }
        } catch (\Exception $e) {
            \Log::warning('Erreur suppression session Traccar: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la page d'inscription
     * Cette page n'est accessible que si aucun administrateur n'existe (première installation)
     */
    public function showRegister()
    {
        // Vérifier si un administrateur existe déjà
        $adminExists = User::where('administrator', true)->exists();
        
        if ($adminExists) {
            // Si un admin existe, rediriger vers login avec un message
            return redirect()->route('login')
                ->with('info', 'L\'installation a déjà été effectuée. Connectez-vous ou contactez l\'administrateur pour créer un compte.');
        }
        
        return view('auth.register');
    }

    /**
     * Traiter l'inscription (création du premier administrateur)
     */
    public function register(Request $request)
    {
        // Vérifier si un administrateur existe déjà
        $adminExists = User::where('administrator', true)->exists();
        
        if ($adminExists) {
            return redirect()->route('login')
                ->with('error', 'L\'inscription n\'est pas autorisée. Contactez l\'administrateur.');
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:traccar.users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Utiliser le hasher Traccar pour créer le mot de passe
        $hasher = new \App\Services\TraccarHasher();
        $hashResult = $hasher->make($validated['password']);

        // Créer le premier utilisateur comme administrateur
        $user = User::create([
            'name' => htmlspecialchars($validated['name'], ENT_QUOTES, 'UTF-8'),
            'email' => $validated['email'],
            'hashedpassword' => $hashResult['hash'],
            'salt' => $hashResult['salt'],
            'administrator' => true, // Premier utilisateur = administrateur
            'userlimit' => -1, // Pas de limite d'utilisateurs
            'devicelimit' => -1, // Pas de limite d'appareils
            'attributes' => '{}', // Traccar exige un JSON vide
        ]);

        // Logger la création de l'administrateur
        SecurityAuditService::logUserCreated($user->id, $user->email, 'initial_admin_setup');

        Auth::login($user);

        // Créer la session Traccar pour l'admin
        $this->createTraccarSession($request, $validated['email'], $validated['password']);

        // Stocker les credentials cryptés
        $request->session()->put('traccar_credentials', encrypt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]));

        // Rediriger vers le dashboard admin
        return redirect('/dashboard')
            ->with('success', 'Bienvenue ! Votre compte administrateur a été créé avec succès.');
    }
    
    /**
     * Vérifier si un admin existe (API endpoint pour le frontend)
     */
    public function checkAdminExists()
    {
        return response()->json([
            'exists' => User::where('administrator', true)->exists()
        ]);
    }
}
