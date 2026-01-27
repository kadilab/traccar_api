<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InputValidationService
{
    /**
     * Règles de validation communes
     */
    private static array $commonRules = [
        'email' => 'required|email:rfc,dns|max:255',
        'name' => 'required|string|min:2|max:255',
        'phone' => 'nullable|string|regex:/^[\+]?[(]?[0-9]{1,3}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/|max:20',
        'id' => 'required|integer|min:1',
        'deviceId' => 'required|integer|min:1',
        'userId' => 'required|integer|min:1',
    ];

    /**
     * Valide les données utilisateur
     */
    public static function validateUserData(array $data, bool $isUpdate = false): array
    {
        $rules = [
            'name' => $isUpdate ? 'sometimes|string|min:2|max:255' : 'required|string|min:2|max:255',
            'email' => $isUpdate ? 'sometimes|email:rfc|max:255' : 'required|email:rfc|max:255',
            'password' => $isUpdate ? 'nullable|string|min:8' : 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'administrator' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
            'readonly' => 'nullable|boolean',
            'deviceReadonly' => 'nullable|boolean',
            'deviceLimit' => 'nullable|integer|min:-1',
            'userLimit' => 'nullable|integer|min:-1',
            'expirationTime' => 'nullable|date',
        ];
        
        return self::validate($data, $rules);
    }

    /**
     * Valide les données de device
     */
    public static function validateDeviceData(array $data, bool $isUpdate = false): array
    {
        $rules = [
            'name' => $isUpdate ? 'sometimes|string|min:1|max:255' : 'required|string|min:1|max:255',
            'uniqueId' => $isUpdate ? 'sometimes|string|min:1|max:255' : 'required|string|min:1|max:255',
            'groupId' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'model' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'disabled' => 'nullable|boolean',
        ];
        
        return self::validate($data, $rules);
    }

    /**
     * Valide les données de géofence
     */
    public static function validateGeofenceData(array $data, bool $isUpdate = false): array
    {
        $rules = [
            'name' => $isUpdate ? 'sometimes|string|min:1|max:255' : 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:500',
            'area' => $isUpdate ? 'sometimes|string' : 'required|string',
            'calendarId' => 'nullable|integer|min:1',
        ];
        
        return self::validate($data, $rules);
    }

    /**
     * Valide les données de commande
     */
    public static function validateCommandData(array $data): array
    {
        $rules = [
            'deviceId' => 'required|integer|min:1',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'attributes' => 'nullable|array',
        ];
        
        return self::validate($data, $rules);
    }

    /**
     * Valide les données de notification
     */
    public static function validateNotificationData(array $data, bool $isUpdate = false): array
    {
        $rules = [
            'type' => $isUpdate ? 'sometimes|string|max:50' : 'required|string|max:50',
            'notificators' => 'nullable|string|max:255',
            'always' => 'nullable|boolean',
            'calendarId' => 'nullable|integer|min:1',
        ];
        
        return self::validate($data, $rules);
    }

    /**
     * Valide les paramètres de rapport
     */
    public static function validateReportParams(array $data): array
    {
        $rules = [
            'deviceId' => 'required_without:groupId|integer|min:1',
            'groupId' => 'required_without:deviceId|integer|min:1',
            'from' => 'required|date',
            'to' => 'required|date|after:from',
        ];
        
        return self::validate($data, $rules);
    }

    /**
     * Nettoie et échappe une chaîne pour prévenir XSS
     */
    public static function sanitizeString(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }
        
        // Supprimer les caractères null
        $input = str_replace("\0", '', $input);
        
        // Échapper les caractères HTML dangereux
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Supprimer les balises potentiellement dangereuses
        $input = strip_tags($input);
        
        return trim($input);
    }

    /**
     * Nettoie un tableau de données
     */
    public static function sanitizeArray(array $data, array $keysToSanitize = []): array
    {
        $stringKeys = ['name', 'email', 'phone', 'description', 'contact', 'model', 'category'];
        $keysToSanitize = !empty($keysToSanitize) ? $keysToSanitize : $stringKeys;
        
        foreach ($data as $key => $value) {
            if (is_string($value) && in_array($key, $keysToSanitize)) {
                $data[$key] = self::sanitizeString($value);
            } elseif (is_array($value)) {
                $data[$key] = self::sanitizeArray($value, $keysToSanitize);
            }
        }
        
        return $data;
    }

    /**
     * Valide un ID
     */
    public static function validateId($id): int
    {
        $validated = self::validate(['id' => $id], ['id' => 'required|integer|min:1']);
        return (int) $validated['id'];
    }

    /**
     * Effectue la validation et retourne les données validées
     */
    private static function validate(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
        
        return $validator->validated();
    }
}
