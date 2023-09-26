<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 
use App\Http\Controllers\api\role\PermissionController;

Route::group(['prefix' => 'permission', 'middleware' => [ 
    'auth:api',
]], function () {  
    Route::post('create-permission', [PermissionController::class, 'createPermission'])
        ->middleware([
            'role:super-admin,api',
        ]);
        
    Route::post('assign-permission-to-role', [PermissionController::class, 'assignPermissionToRole'])
        ->middleware([
            'role:super-admin,api',
        ]);
});