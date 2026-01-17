<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Langues supportées avec leurs détails
     */
    protected array $languages = [
        'fr' => [
            'name' => 'Français',
            'native' => 'Français',
            'flag' => '🇫🇷',
            'dir' => 'ltr'
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇬🇧',
            'dir' => 'ltr'
        ],
        'ar' => [
            'name' => 'Arabic',
            'native' => 'العربية',
            'flag' => '🇸🇦',
            'dir' => 'rtl'
        ]
    ];

    /**
     * Changer la langue
     */
    public function switchLang(Request $request, string $locale)
    {
        if (array_key_exists($locale, $this->languages)) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }

    /**
     * Obtenir les langues disponibles (API)
     */
    public function getLanguages()
    {
        return response()->json([
            'success' => true,
            'languages' => $this->languages,
            'current' => Session::get('locale', config('app.locale', 'fr'))
        ]);
    }
}
