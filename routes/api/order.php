<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderController;

Route::group(['prefix' => 'order', 'middleware' => [
    'throttle:60,1',
    'auth:api',
]], function() {
    Route::get('summary-delivery', [OrderController::class, 'summaryDelivery']);
    Route::get('latest', [OrderController::class, 'latest']);
});
