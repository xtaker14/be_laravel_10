<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderController;

Route::group(['prefix' => 'order', 'middleware' => [ 
    'auth:api',
    'role:driver,api',
]], function () {
    Route::get('/', [OrderController::class, 'list']);
    Route::get('detail', [OrderController::class, 'detail']);
    Route::post('update-detail', [OrderController::class, 'updateDetail']);
    Route::post('scan-delivery', [OrderController::class, 'scanDelivery']);
    Route::get('summary-delivery', [OrderController::class, 'summaryDelivery']);
    Route::get('latest-order', [OrderController::class, 'latest']);
    Route::post('sorting-numbers', [OrderController::class, 'sortingNumbers']);
});

