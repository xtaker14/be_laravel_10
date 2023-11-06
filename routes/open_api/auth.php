<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AuthController;
 
Route::group(['prefix' => 'auth', 'middleware' => []], function () {
    Route::post('login', [AuthController::class, 'loginOpenApi'])
        ->middleware(['role:OPEN_API,api,after']);
}); 