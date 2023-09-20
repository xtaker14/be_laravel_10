<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\TestController;

Route::group(['prefix' => 'test', 'middleware' => ['throttle:60,1']], function() {
    Route::get('without-token', [TestController::class, 'withoutToken']);

    Route::middleware('auth:api')
        ->get('token-and-json-only', [TestController::class, 'tokenAndJsonOnly']);

    Route::middleware([
        'auth:api', 
        'role:super-admin',
        'permission:all',
    ])->get('token-and-role-in-route', [TestController::class, 'tokenAndRoleInRoute']);

    Route::middleware([
        'auth:api', 
    ])->get('token-and-role-in-controller', [TestController::class, 'tokenAndRoleInController']);

    Route::middleware([
        'auth:api', 
    ])->post('aws3', [TestController::class, 'aws3']);

    Route::middleware([
        'auth:api', 
    ])->post('excel', [TestController::class, 'excel']);

});