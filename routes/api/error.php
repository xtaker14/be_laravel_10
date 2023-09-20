<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Helpers\ResponseFormatter;

Route::group(['prefix' => 'error', 'middleware' => ['throttle:60,1']], function() {
    Route::get('unauthenticated', function (Request $request) {
        return ResponseFormatter::error(401, __('messages.unauthenticated'));
    });
});