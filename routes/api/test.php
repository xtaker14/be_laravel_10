<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\TestController;

Route::group(['prefix' => 'test', 'middleware' => []], function () {
    
    Route::get('/', function (Request $request) {
        return ['test'];
    });

    Route::get('without-token', [TestController::class, 'withoutToken']);

    Route::get('token-and-json-only', [TestController::class, 'tokenAndJsonOnly'])
        ->middleware('auth:api');

    Route::get('token-and-role-in-route', [TestController::class, 'tokenAndRoleInRoute'])
        ->middleware([
            'auth:api', 
            'role:super-admin,api',
            'permission:all,api',
        ]);

    Route::get('token-and-role-in-controller', [TestController::class, 'tokenAndRoleInController'])
        ->middleware([
            'auth:api', 
        ]);

    Route::post('aws3', [TestController::class, 'aws3'])
        ->middleware([
            'auth:api', 
        ]);

    Route::post('excel', [TestController::class, 'excel'])
        ->middleware([
            'auth:api', 
        ]);

    Route::get('check-relation-table', [TestController::class, 'checkRelationTable']);
});