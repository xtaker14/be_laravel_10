<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TestController;

Route::group(['prefix' => 'test', 'middleware' => []], function () {
    
    Route::get('/', function (Request $request) {
        return ['test'];
    });

    Route::get('without-token', [TestController::class, 'withoutToken'])
        ->middleware(['acc.json']);

    Route::get('token-and-json-only', [TestController::class, 'tokenAndJsonOnly'])
        ->middleware(['acc.json', 'auth:api']);

    Route::get('token-and-role-in-route', [TestController::class, 'tokenAndRoleInRoute'])
        ->middleware([
            'acc.json',
            'auth:api', 
            'role:super-admin,api',
            'permission:all,api',
        ]);

    Route::get('token-and-role-in-controller', [TestController::class, 'tokenAndRoleInController'])
        ->middleware([
            'acc.json',
            'auth:api', 
        ]);

    Route::post('aws3', [TestController::class, 'aws3'])
        ->middleware([
            'acc.json',
            'auth:api', 
        ]);

    Route::post('excel', [TestController::class, 'excel'])
        ->middleware([
            'acc.json',
            'auth:api', 
        ]);

    Route::get('check-relation-table', [TestController::class, 'checkRelationTable'])
        ->middleware(['acc.json']);

    Route::get('generate-pdf', [TestController::class, 'generatePdf']);
    Route::get('link-pdf', [TestController::class, 'linkPdf']);
});