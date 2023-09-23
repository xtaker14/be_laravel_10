<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Laravel\Telescope\Http\Controllers as TC;
use App\Http\Controllers\api\TelescopeApiController; 

Route::group(['prefix' => 'telescope', 'middleware' => [ 
    'auth:api', 
    'role:super-admin',
    'permission:all',
]], function () {
    // Requests entries...
    Route::post('requests', [TC\RequestsController::class, 'index']);
    Route::get('requests/{telescopeEntryId}', [TC\RequestsController::class, 'show']);

    // Scheduled Commands entries...
    Route::post('schedule', [TC\ScheduleController::class, 'index']);
    Route::get('schedule/{telescopeEntryId}', [TC\ScheduleController::class, 'show']);

    // Exception entries...
    Route::post('exceptions', [TC\ExceptionController::class, 'index']);
    Route::get('exceptions/{telescopeEntryId}', [TC\ExceptionController::class, 'show']);

    // Queries entries...
    Route::post('queries', [TC\QueriesController::class, 'index']);
    Route::get('queries/{telescopeEntryId}', [TC\QueriesController::class, 'show']);
});

