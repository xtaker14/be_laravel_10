<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ConnectionController;


Route::group(['middleware' => ['acc.json']], function () {
    Route::get('check-connection', [ConnectionController::class, 'checkConnection']);
});
