<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderController;

Route::group(['prefix' => 'order', 'middleware' => [
    'auth:api',
    'role:OPEN_API,api',
]], function () {
    Route::post('push-order', [OrderController::class, 'pushOrder']);
});
