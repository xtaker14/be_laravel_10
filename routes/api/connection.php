<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\ConnectionController;


Route::group(['middleware' => []], function () {
    Route::get('health-check', [ConnectionController::class, 'healthCheck']);
    Route::get('check-connection', [ConnectionController::class, 'checkConnection']);
});
