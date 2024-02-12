<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
 
Route::group([
    'prefix' => 'auth',  
    'middleware' => ['acc.json'],
], function () {
    Route::post('login', [AuthController::class, 'loginOpenApi'])
        ->middleware(['role:OPEN_API,api,after']);
}); 