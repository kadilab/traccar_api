<?php
use App\Http\Controllers\TraccarController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

// ==================== TRACCAR API ROUTES ====================

Route::prefix('traccar')->group(function () {

    // Health
    Route::get('/health', [TraccarController::class, 'health']);

    // Server
    Route::get('/server', [TraccarController::class, 'getServer']);
    Route::put('/server', [TraccarController::class, 'updateServer']);

    // Session
    Route::get('/session', [TraccarController::class, 'getSession']);
    Route::post('/session', [TraccarController::class, 'createSession']);
    Route::delete('/session', [TraccarController::class, 'deleteSession']);
    Route::post('/session/token', [TraccarController::class, 'generateToken']);
    Route::post('/session/token/revoke', [TraccarController::class, 'revokeToken']);

    // Devices - Lecture accessible à tous
    Route::get('/devices', [TraccarController::class, 'getDevices']);
    Route::get('/devices/{id}', [TraccarController::class, 'getDevice']);
    
    // Devices - Création/Modification/Suppression réservées aux admins
    Route::middleware('admin.api')->group(function () {
        Route::post('/devices', [TraccarController::class, 'createDevice']);
        Route::put('/devices/{id}', [TraccarController::class, 'updateDevice']);
        Route::delete('/devices/{id}', [TraccarController::class, 'deleteDevice']);
        Route::put('/devices/{id}/accumulators', [TraccarController::class, 'updateDeviceAccumulators']);
    });

    // Groups - Lecture accessible à tous
    Route::get('/groups', [TraccarController::class, 'getGroups']);
    
    // Groups - Création/Modification/Suppression réservées aux admins
    Route::middleware('admin.api')->group(function () {
        Route::post('/groups', [TraccarController::class, 'createGroup']);
        Route::put('/groups/{id}', [TraccarController::class, 'updateGroup']);
        Route::delete('/groups/{id}', [TraccarController::class, 'deleteGroup']);
    });

    // Users - Tout réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/users', [TraccarController::class, 'getUsers']);
        Route::post('/users', [TraccarController::class, 'createUser']);
        Route::put('/users/{id}', [TraccarController::class, 'updateUser']);
        Route::delete('/users/{id}', [TraccarController::class, 'deleteUser']);
    });

    // Permissions - Accessible à tous (Traccar gère les droits)
    Route::get('/permissions', [TraccarController::class, 'getPermissions']);
    Route::post('/permissions', [TraccarController::class, 'createPermission']);
    Route::delete('/permissions', [TraccarController::class, 'deletePermission']);

    // Positions - Lecture accessible à tous
    Route::get('/positions', [TraccarController::class, 'getPositions']);
    
    // Positions - Suppression réservée aux admins
    Route::middleware('admin.api')->group(function () {
        Route::delete('/positions', [TraccarController::class, 'deletePositions']);
        Route::delete('/positions/{id}', [TraccarController::class, 'deletePosition']);
    });

    // Events - Accessible à tous
    Route::get('/events/{id}', [TraccarController::class, 'getEvent']);

    // Reports - Accessible à tous
    Route::get('/reports/route', [TraccarController::class, 'getReportRoute']);
    Route::get('/reports/events', [TraccarController::class, 'getReportEvents']);
    Route::get('/reports/summary', [TraccarController::class, 'getReportSummary']);
    Route::get('/reports/trips', [TraccarController::class, 'getReportTrips']);
    Route::get('/reports/stops', [TraccarController::class, 'getReportStops']);

    // Notifications - Accessible à tous les utilisateurs connectés
    Route::get('/notifications', [TraccarController::class, 'getNotifications']);
    Route::get('/notifications/types', [TraccarController::class, 'getNotificationTypes']);
    Route::post('/notifications', [TraccarController::class, 'createNotification']);
    Route::put('/notifications/{id}', [TraccarController::class, 'updateNotification']);
    Route::delete('/notifications/{id}', [TraccarController::class, 'deleteNotification']);
    Route::post('/notifications/test', [TraccarController::class, 'testNotification']);

    // Geofences - Accessible à tous les utilisateurs connectés
    Route::get('/geofences', [TraccarController::class, 'getGeofences']);
    Route::post('/geofences', [TraccarController::class, 'createGeofence']);
    Route::put('/geofences/{id}', [TraccarController::class, 'updateGeofence']);
    Route::delete('/geofences/{id}', [TraccarController::class, 'deleteGeofence']);

    // Commands - Tout réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/commands', [TraccarController::class, 'getCommands']);
        Route::post('/commands', [TraccarController::class, 'createCommand']);
        Route::put('/commands/{id}', [TraccarController::class, 'updateCommand']);
        Route::delete('/commands/{id}', [TraccarController::class, 'deleteCommand']);
        Route::get('/commands/send', [TraccarController::class, 'getCommandsSend']);
        Route::post('/commands/send', [TraccarController::class, 'sendCommand']);
        Route::get('/commands/types', [TraccarController::class, 'getCommandTypes']);
    });

    // Statistics - Réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/statistics', [TraccarController::class, 'getStatistics']);
    });

    // Calendars - Réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/calendars', [TraccarController::class, 'getCalendars']);
        Route::post('/calendars', [TraccarController::class, 'createCalendar']);
        Route::put('/calendars/{id}', [TraccarController::class, 'updateCalendar']);
        Route::delete('/calendars/{id}', [TraccarController::class, 'deleteCalendar']);
    });

    // Attributes (Computed) - Réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/attributes/computed', [TraccarController::class, 'getAttributes']);
        Route::post('/attributes/computed', [TraccarController::class, 'createAttribute']);
        Route::put('/attributes/computed/{id}', [TraccarController::class, 'updateAttribute']);
        Route::delete('/attributes/computed/{id}', [TraccarController::class, 'deleteAttribute']);
    });

    // Drivers - Réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/drivers', [TraccarController::class, 'getDrivers']);
        Route::post('/drivers', [TraccarController::class, 'createDriver']);
        Route::put('/drivers/{id}', [TraccarController::class, 'updateDriver']);
        Route::delete('/drivers/{id}', [TraccarController::class, 'deleteDriver']);
    });

    // Maintenance - Réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/maintenance', [TraccarController::class, 'getMaintenance']);
        Route::post('/maintenance', [TraccarController::class, 'createMaintenance']);
        Route::put('/maintenance/{id}', [TraccarController::class, 'updateMaintenance']);
        Route::delete('/maintenance/{id}', [TraccarController::class, 'deleteMaintenance']);
    });

    // Orders - Réservé aux admins
    Route::middleware('admin.api')->group(function () {
        Route::get('/orders', [TraccarController::class, 'getOrders']);
        Route::post('/orders', [TraccarController::class, 'createOrder']);
        Route::put('/orders/{id}', [TraccarController::class, 'updateOrder']);
        Route::delete('/orders/{id}', [TraccarController::class, 'deleteOrder']);
    });
});

// ==================== PERMISSION CONTROLLER ROUTES ====================
Route::prefix('traccar/permissions-test')->group(function () {
    Route::get('/{userId}', [PermissionController::class, 'getPermissions']);
    Route::post('/', [PermissionController::class, 'createPermission']);
    Route::delete('/', [PermissionController::class, 'deletePermission']);
});

// ==================== USER STATUS ROUTE ====================
Route::get('/user-status', function () {
    return response()->json([
        'isAdmin' => auth()->check() ? (bool)auth()->user()->administrator : false,
    ]);
});