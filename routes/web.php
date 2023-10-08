<?php

use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\DeliveryorderController;
use App\Http\Controllers\web\DeliveryrecordController;
use App\Http\Controllers\web\InboundController;
use App\Http\Controllers\web\RoutingController;
use App\Http\Controllers\web\LoginController;
use App\Http\Controllers\web\VendorController;
use App\Http\Controllers\web\HubController;
use App\Http\Controllers\web\RegionController;
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

Route::group(['middleware' => ['auth']], function()
{
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/order/request-waybill', [DeliveryorderController::class, 'index'])->name('request-waybill');
    Route::post('/order/upload-reqwaybill', [DeliveryorderController::class, 'upload_reqwaybill'])->name('upload-reqwaybill');
    Route::get('/order/list-upload', [DeliveryorderController::class, 'list_upload'])->name('list-upload');
    Route::get('/order/waybill-list', [DeliveryorderController::class, 'list'])->name('waybill-list');
    Route::get('/order/list-package', [DeliveryorderController::class, 'list_package'])->name('list-package');
    Route::get('/order/adjustment', [DeliveryorderController::class, 'adjustment'])->name('adjustment');

    Route::get('/record/create', [DeliveryrecordController::class, 'index'])->name('create-record');
    Route::get('/record/update', [DeliveryrecordController::class, 'update'])->name('update-record');

    Route::get('/inbound', [InboundController::class, 'inbound'])->name('inbound');
    Route::get('/routing', [RoutingController::class, 'routing'])->name('routing');
    Route::get('/cod-collection', [CodController::class, 'cod-collection'])->name('cod-collection');

    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::resource('vendor', VendorController::class);
        Route::resource('hub', HubController::class);
        Route::resource('region', RegionController::class);
    });
});