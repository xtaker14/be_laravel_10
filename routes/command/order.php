<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderWmsController;

Route::group(['prefix' => 'order-to-wms', 'middleware' => []], function () {
    Route::get('post-tracking-number', [OrderWmsController::class, 'postTrackingNumber']);
    Route::get('status-tracking-number', [OrderWmsController::class, 'statusTrackingNumber']);
});
