<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AuthController;
 
Route::group(['prefix' => 'auth', 'middleware' => []], function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::get('logout', [AuthController::class, 'logout'])
        ->middleware('auth:api');

    Route::get('check-token', [AuthController::class, 'checkToken']);

    Route::get('refresh-token', [AuthController::class, 'refreshToken'])
        ->middleware('auth:api');
    
    Route::post('generate-otp', [AuthController::class, 'generateOtp'])
        ->middleware('auth:api');
        
    Route::post('verify-otp', [AuthController::class, 'verifyOtp'])
        ->middleware('auth:api');

    Route::post('set-password', [AuthController::class, 'setPassword'])
        ->middleware('auth:api');
}); 