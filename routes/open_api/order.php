<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderController;

Route::group([
    'prefix' => 'order', 
    'middleware' => [
        'acc.json',
        'auth:api',
        'role:OPEN_API,api',
    ],
], function () {
    Route::post('generate-waybill', [OrderController::class, 'generateWaybill']);
    Route::post('update-waybill', [OrderController::class, 'updateWaybill']);
});
