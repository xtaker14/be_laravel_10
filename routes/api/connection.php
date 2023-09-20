<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\ConnectionController;

Route::group(['middleware' => ['throttle:60,1']], function() {
    Route::get('check-connection', [ConnectionController::class, 'checkConnection']);
});
