<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\role\RoleController;

Route::group(['prefix' => 'role', 'middleware' => [ 
    'auth:api',
]], function () { 
    Route::post('create-role', [RoleController::class, 'createRole'])
        ->middleware([
            'role:super-admin,api',
        ]);
        
    Route::post('assign-role-to-user', [RoleController::class, 'assignRoleToUser'])
        ->middleware([
            'role:super-admin,api',
        ]);
});