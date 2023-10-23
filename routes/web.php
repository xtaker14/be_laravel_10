<?php

use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\DeliveryorderController;
use App\Http\Controllers\web\DeliveryrecordController;
use App\Http\Controllers\web\LoginController;
use App\Http\Controllers\web\VendorController;
use App\Http\Controllers\web\HubController;
use App\Http\Controllers\web\RegionController;
use App\Http\Controllers\web\CourierController;
use App\Http\Controllers\web\TransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/health-check', function () {
    return response()->json('Success')
            ->header('Content-Type', 'application/json');
});

Route::get('/', [LoginController::class, 'index'])->name('login');

Route::post('/login-validation', [LoginController::class, 'login_validation'])->name('login-validation');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Auth::routes();

Route::group(['middleware' => ['auth', 'prevent-back-history']], function()
{
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'order'], function() {
        Route::get('request-waybill', [DeliveryorderController::class, 'index'])->name('request-waybill');
        Route::post('upload-reqwaybill', [DeliveryorderController::class, 'upload_reqwaybill'])->name('upload-reqwaybill');
        Route::get('list-upload', [DeliveryorderController::class, 'list_upload'])->name('list-upload');
        Route::get('waybill-list', [DeliveryorderController::class, 'list'])->name('waybill-list');
        Route::get('list-package', [DeliveryorderController::class, 'list_package'])->name('list-package');
        Route::get('adjustment', [DeliveryorderController::class, 'adjustment'])->name('adjustment');
        Route::get('upload-result', [DeliveryorderController::class, 'upload_result'])->name('upload-result');
    });

    Route::group(['prefix' => 'record'], function() {
        Route::get('create', [DeliveryrecordController::class, 'index'])->name('create-record');
        Route::get('update', [DeliveryrecordController::class, 'update'])->name('update-record');
        Route::post('create-dr', [DeliveryrecordController::class, 'create_process'])->name('create-dr');
        Route::post('update-dr', [DeliveryrecordController::class, 'update_process'])->name('update-dr');
    });

    Route::group(['prefix' => 'transfer'], function() {
        Route::get('/', [TransferController::class, 'index'])->name('transfer');
        Route::post('create', [TransferController::class, 'create'])->name('create-transfer');
    });

    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::resource('vendor', VendorController::class);
        Route::resource('hub', HubController::class);
        Route::resource('region', RegionController::class);
        Route::resource('courier', CourierController::class);
    });

    Route::post('upload-region', [RegionController::class, 'upload'])->name('upload-region');
});