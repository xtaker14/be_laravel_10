<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\role\RoleController;
use App\Http\Controllers\api\role\PermissionController;

Route::group(['middleware' => [
    'throttle:60,1',
    'auth:api',
]], function() {
    Route::middleware([
        'role:super-admin',
    ])->post('create-role', [RoleController::class, 'createRole']);
    Route::middleware([
        'role:super-admin',
    ])->post('assign-role-to-user', [RoleController::class, 'assignRoleToUser']);
    Route::middleware([
        'role:super-admin',
    ])->post('create-permission', [PermissionController::class, 'createPermission']);
    Route::middleware([
        'role:super-admin',
    ])->post('assign-permission-to-role', [PermissionController::class, 'assignPermissionToRole']);
});