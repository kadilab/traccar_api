<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $request->session()->regenerate();

            // Stocker les credentials cryptés pour pouvoir recréer la session Traccar
            $request->session()->put('traccar_credentials', encrypt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ]));

            // Créer une session Traccar et stocker le cookie
            $this->createTraccarSession($request, $credentials['email'], $credentials['password']);

            // Rediriger vers dashboard si admin, sinon vers monitor
            $redirect = Auth::user()->administrator ? '/dashboard' : '/monitor';
            return redirect()->intended($redirect);
        }

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
        // Supprimer la session Traccar
        $this->deleteTraccarSession($request);
        
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
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:traccar.users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Utiliser le hasher Traccar pour créer le mot de passe
        $hasher = new \App\Services\TraccarHasher();
        $hashResult = $hasher->make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'hashedpassword' => $hashResult['hash'],
            'salt' => $hashResult['salt'],
            'attributes' => '{}', // Traccar exige un JSON vide
        ]);

        Auth::login($user);

        // Les nouveaux utilisateurs ne sont pas admin, rediriger vers monitor
        return redirect('/monitor');
    }
}
