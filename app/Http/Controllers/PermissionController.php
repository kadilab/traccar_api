<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Récupérer les permissions d'un utilisateur
     * GET /api/traccar/permissions?userId={id} ou GET /api/traccar/permissions/{userId}
     */
    public function getPermissions(Request $request, $userId = null)
    {
        try {
            // Accepter userId depuis le paramètre de route ou query
            if (!$userId) {
                $userId = $request->query('userId');
            }

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'userId est requis'
                ], 400);
            }

            // Convertir en entier
            $userId = (int)$userId;

            // Récupérer les permissions depuis la DB Traccar
            $permissions = $this->fetchUserPermissions($userId);

            return response()->json([
                'success' => true,
                'permissions' => $permissions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des permissions: ' . $e->getMessage(),
                'error' => $e->getFile() . ':' . $e->getLine()
            ], 500);
        }
    }

    /**
     * Créer une permission
     * POST /api/traccar/permissions
     */
    public function createPermission(Request $request)
    {
        try {
            $validated = $request->validate([
                'userId' => 'required|integer',
            ]);

            // Au moins un des champs doit être fourni
            $hasItem = $request->has('deviceId') || $request->has('groupId') ||
                      $request->has('geofenceId') || $request->has('notificationId') ||
                      $request->has('calendarId') || $request->has('attributeId') ||
                      $request->has('driverId') || $request->has('managedUserId') ||
                      $request->has('commandId');

            if (!$hasItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Au moins un ID d\'élément est requis'
                ], 400);
            }

            $userId = $validated['userId'];
            $permissionData = [];

            // Mapper les données pour insérer dans les bonnes tables
            if ($request->has('deviceId')) {
                DB::connection('traccar')->table('user_device')->updateOrCreate(
                    ['userid' => $userId, 'deviceid' => $request->input('deviceId')],
                    ['userid' => $userId, 'deviceid' => $request->input('deviceId')]
                );
                $permissionData['deviceId'] = $request->input('deviceId');
            }

            if ($request->has('groupId')) {
                DB::connection('traccar')->table('user_group')->updateOrCreate(
                    ['userid' => $userId, 'groupid' => $request->input('groupId')],
                    ['userid' => $userId, 'groupid' => $request->input('groupId')]
                );
                $permissionData['groupId'] = $request->input('groupId');
            }

            if ($request->has('geofenceId')) {
                DB::connection('traccar')->table('user_geofence')->updateOrCreate(
                    ['userid' => $userId, 'geofenceid' => $request->input('geofenceId')],
                    ['userid' => $userId, 'geofenceid' => $request->input('geofenceId')]
                );
                $permissionData['geofenceId'] = $request->input('geofenceId');
            }

            if ($request->has('notificationId')) {
                DB::connection('traccar')->table('user_notification')->updateOrCreate(
                    ['userid' => $userId, 'notificationid' => $request->input('notificationId')],
                    ['userid' => $userId, 'notificationid' => $request->input('notificationId')]
                );
                $permissionData['notificationId'] = $request->input('notificationId');
            }

            if ($request->has('calendarId')) {
                DB::connection('traccar')->table('user_calendar')->updateOrCreate(
                    ['userid' => $userId, 'calendar_id' => $request->input('calendarId')],
                    ['userid' => $userId, 'calendar_id' => $request->input('calendarId')]
                );
                $permissionData['calendarId'] = $request->input('calendarId');
            }

            if ($request->has('attributeId')) {
                DB::connection('traccar')->table('user_attribute')->updateOrCreate(
                    ['userid' => $userId, 'attributeid' => $request->input('attributeId')],
                    ['userid' => $userId, 'attributeid' => $request->input('attributeId')]
                );
                $permissionData['attributeId'] = $request->input('attributeId');
            }

            if ($request->has('driverId')) {
                DB::connection('traccar')->table('user_driver')->updateOrCreate(
                    ['userid' => $userId, 'driverid' => $request->input('driverId')],
                    ['userid' => $userId, 'driverid' => $request->input('driverId')]
                );
                $permissionData['driverId'] = $request->input('driverId');
            }

            if ($request->has('managedUserId')) {
                DB::connection('traccar')->table('user_user')->updateOrCreate(
                    ['userid' => $userId, 'managed_userid' => $request->input('managedUserId')],
                    ['userid' => $userId, 'managed_userid' => $request->input('managedUserId')]
                );
                $permissionData['managedUserId'] = $request->input('managedUserId');
            }

            if ($request->has('commandId')) {
                DB::connection('traccar')->table('user_command')->updateOrCreate(
                    ['userid' => $userId, 'commandid' => $request->input('commandId')],
                    ['userid' => $userId, 'commandid' => $request->input('commandId')]
                );
                $permissionData['commandId'] = $request->input('commandId');
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission créée avec succès',
                'permission' => $permissionData
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une permission
     * DELETE /api/traccar/permissions
     */
    public function deletePermission(Request $request)
    {
        try {
            $validated = $request->validate([
                'userId' => 'required|integer',
            ]);

            $userId = $validated['userId'];

            // Supprimer selon le type d'élément fourni
            if ($request->has('deviceId')) {
                DB::connection('traccar')->table('user_device')
                    ->where('userid', $userId)
                    ->where('deviceid', $request->input('deviceId'))
                    ->delete();
            }

            if ($request->has('groupId')) {
                DB::connection('traccar')->table('user_group')
                    ->where('userid', $userId)
                    ->where('groupid', $request->input('groupId'))
                    ->delete();
            }

            if ($request->has('geofenceId')) {
                DB::connection('traccar')->table('user_geofence')
                    ->where('userid', $userId)
                    ->where('geofenceid', $request->input('geofenceId'))
                    ->delete();
            }

            if ($request->has('notificationId')) {
                DB::connection('traccar')->table('user_notification')
                    ->where('userid', $userId)
                    ->where('notificationid', $request->input('notificationId'))
                    ->delete();
            }

            if ($request->has('calendarId')) {
                DB::connection('traccar')->table('user_calendar')
                    ->where('userid', $userId)
                    ->where('calendar_id', $request->input('calendarId'))
                    ->delete();
            }

            if ($request->has('attributeId')) {
                DB::connection('traccar')->table('user_attribute')
                    ->where('userid', $userId)
                    ->where('attributeid', $request->input('attributeId'))
                    ->delete();
            }

            if ($request->has('driverId')) {
                DB::connection('traccar')->table('user_driver')
                    ->where('userid', $userId)
                    ->where('driverid', $request->input('driverId'))
                    ->delete();
            }

            if ($request->has('managedUserId')) {
                DB::connection('traccar')->table('user_user')
                    ->where('userid', $userId)
                    ->where('managed_userid', $request->input('managedUserId'))
                    ->delete();
            }

            if ($request->has('commandId')) {
                DB::connection('traccar')->table('user_command')
                    ->where('userid', $userId)
                    ->where('commandid', $request->input('commandId'))
                    ->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer toutes les permissions d'un utilisateur depuis la DB Traccar
     */
    private function fetchUserPermissions($userId)
    {
        $permissions = [];
        $userId = (int)$userId;

        try {
            // Récupérer les devices liés
            $devices = DB::connection('traccar')->table('user_device')
                ->where('userid', $userId)
                ->get(['userid', 'deviceid']);

            foreach ($devices as $device) {
                $permissions[] = [
                    'userId' => $userId,
                    'deviceId' => (int)$device->deviceid,
                    'type' => 'device'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_device for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les groupes liés
            $groups = DB::connection('traccar')->table('user_group')
                ->where('userid', $userId)
                ->get(['userid', 'groupid']);

            foreach ($groups as $group) {
                $permissions[] = [
                    'userId' => $userId,
                    'groupId' => (int)$group->groupid,
                    'type' => 'group'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_group for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les géofences liées
            $geofences = DB::connection('traccar')->table('user_geofence')
                ->where('userid', $userId)
                ->get(['userid', 'geofenceid']);

            foreach ($geofences as $geofence) {
                $permissions[] = [
                    'userId' => $userId,
                    'geofenceId' => (int)$geofence->geofenceid,
                    'type' => 'geofence'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_geofence for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les notifications liées
            $notifications = DB::connection('traccar')->table('user_notification')
                ->where('userid', $userId)
                ->get(['userid', 'notificationid']);

            foreach ($notifications as $notification) {
                $permissions[] = [
                    'userId' => $userId,
                    'notificationId' => (int)$notification->notificationid,
                    'type' => 'notification'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_notification for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les calendriers liés
            $calendars = DB::connection('traccar')->table('user_calendar')
                ->where('userid', $userId)
                ->get(['userid', 'calendar_id']);

            foreach ($calendars as $calendar) {
                $permissions[] = [
                    'userId' => $userId,
                    'calendarId' => (int)$calendar->calendar_id,
                    'type' => 'calendar'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_calendar for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les attributs liés
            $attributes = DB::connection('traccar')->table('user_attribute')
                ->where('userid', $userId)
                ->get(['userid', 'attributeid']);

            foreach ($attributes as $attribute) {
                $permissions[] = [
                    'userId' => $userId,
                    'attributeId' => (int)$attribute->attributeid,
                    'type' => 'attribute'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_attribute for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les chauffeurs liés
            $drivers = DB::connection('traccar')->table('user_driver')
                ->where('userid', $userId)
                ->get(['userid', 'driverid']);

            foreach ($drivers as $driver) {
                $permissions[] = [
                    'userId' => $userId,
                    'driverId' => (int)$driver->driverid,
                    'type' => 'driver'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_driver for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les utilisateurs gérés
            $managedUsers = DB::connection('traccar')->table('user_user')
                ->where('userid', $userId)
                ->get(['userid', 'managed_userid']);

            foreach ($managedUsers as $managedUser) {
                $permissions[] = [
                    'userId' => $userId,
                    'managedUserId' => (int)$managedUser->managed_userid,
                    'type' => 'managedUser'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_user for userId ' . $userId . ': ' . $e->getMessage());
        }

        try {
            // Récupérer les commandes liées
            $commands = DB::connection('traccar')->table('user_command')
                ->where('userid', $userId)
                ->get(['userid', 'commandid']);

            foreach ($commands as $command) {
                $permissions[] = [
                    'userId' => $userId,
                    'commandId' => (int)$command->commandid,
                    'type' => 'command'
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user_command for userId ' . $userId . ': ' . $e->getMessage());
        }

        return $permissions;
    }
}
