<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name',     'value' => 'GeoTrack Pro', 'type' => 'text',    'group' => 'general',    'label' => "Nom de l'application"],
            ['key' => 'app_logo',     'value' => null,            'type' => 'file',    'group' => 'general',    'label' => 'Logo application'],
            ['key' => 'app_favicon',  'value' => null,            'type' => 'file',    'group' => 'general',    'label' => 'Favicon'],
            // Appearance
            ['key' => 'primary_color',    'value' => '#3b82f6', 'type' => 'color',   'group' => 'appearance', 'label' => 'Couleur principale'],
            ['key' => 'secondary_color',  'value' => '#7556D6', 'type' => 'color',   'group' => 'appearance', 'label' => 'Couleur secondaire'],
            ['key' => 'sidebar_color',    'value' => '#0f172a', 'type' => 'color',   'group' => 'appearance', 'label' => 'Couleur sidebar'],
            ['key' => 'dark_mode_default','value' => '0',       'type' => 'boolean', 'group' => 'appearance', 'label' => 'Mode sombre par défaut'],
            // Company
            ['key' => 'company_name',    'value' => '',  'type' => 'text',     'group' => 'company', 'label' => "Nom de l'entreprise"],
            ['key' => 'company_email',   'value' => '',  'type' => 'email',    'group' => 'company', 'label' => 'Email support'],
            ['key' => 'company_phone',   'value' => '',  'type' => 'text',     'group' => 'company', 'label' => 'Téléphone'],
            ['key' => 'company_address', 'value' => '',  'type' => 'textarea', 'group' => 'company', 'label' => 'Adresse'],
            ['key' => 'footer_text',     'value' => '',  'type' => 'text',     'group' => 'company', 'label' => 'Texte footer personnalisé'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
