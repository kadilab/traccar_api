<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Langues supportées
     */
    protected array $supportedLocales = ['fr', 'en', 'ar'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si une langue est passée dans la requête
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, $this->supportedLocales)) {
                Session::put('locale', $locale);
            }
        }

        // Récupérer la langue de la session ou utiliser la langue par défaut
        $locale = Session::get('locale', config('app.locale', 'fr'));

        // Appliquer la langue
        App::setLocale($locale);

        return $next($request);
    }
}
