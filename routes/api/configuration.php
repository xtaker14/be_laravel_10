<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ConfigurationController;

Route::group(['prefix' => 'configuration', 'middleware' => []], function () {

    Route::get('/', function (Request $request) {
        return ['test'];
    });

    Route::get('generate-api', [ConfigurationController::class, 'generateApi'])
        ->middleware(['acc.json']); 
});
