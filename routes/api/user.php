<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\UserController;

Route::group(['prefix' => 'user', 'middleware' => [
    'throttle:60,1',
    'auth:api',
]], function() {
    Route::get('profile', [UserController::class, 'profile']);
});
