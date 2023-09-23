<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Helpers\ResponseFormatter;

Route::group(['prefix' => 'error', 'middleware' => []], function () { 
    Route::get('unauthenticated', function (Request $request) {
        $res = new ResponseFormatter; 
        
        return $res::error(401, __('messages.unauthenticated'), $res::traceCode('AUTH003')); 
    });
});