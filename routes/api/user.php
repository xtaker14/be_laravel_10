<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\UserController;

Route::group(['prefix' => 'user', 'middleware' => [
    'acc.json',
    'auth:api',
]], function () {
    Route::get('profile', [UserController::class, 'profile']);
});
