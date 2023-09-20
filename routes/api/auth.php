<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\AuthController;

Route::group(['middleware' => ['throttle:60,1']], function() {
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('api')->get('check-token', [AuthController::class, 'checkToken']);
    Route::middleware('auth:api')->get('refresh-token', [AuthController::class, 'refreshToken']);
    
    Route::middleware('auth:api')->post('generate-otp', [AuthController::class, 'generateOtp']);
    Route::middleware('auth:api')->post('verify-otp', [AuthController::class, 'verifyOtp']);

    Route::middleware('auth:api')->post('set-password', [AuthController::class, 'setPassword']);
});