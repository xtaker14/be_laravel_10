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
use App\Http\Controllers\web\CodCollectionController;
use App\Http\Controllers\web\InboundController;
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
    Route::post('/dashboard-summary', [DashboardController::class, 'summary'])->name('dashboard-summary');
    Route::post('/dashboard-order-tracking', [DashboardController::class, 'orderTracking'])->name('dashboard-order-tracking');
    Route::post('/dashboard-routing-tracking', [DashboardController::class, 'routingTracking'])->name('dashboard-routing-tracking');

    Route::group(['prefix' => 'order'], function() {
        Route::get('request-waybill', [DeliveryorderController::class, 'index'])->name('request-waybill');
        Route::post('upload-reqwaybill', [DeliveryorderController::class, 'upload_reqwaybill'])->name('upload-reqwaybill');
        Route::get('waybill-list', [DeliveryorderController::class, 'waybill_list'])->name('waybill-list');
        Route::get('adjustment', [DeliveryorderController::class, 'adjustment'])->name('adjustment');
        Route::get('upload-result', [DeliveryorderController::class, 'upload_result'])->name('upload-result');
    });

    Route::group(['prefix' => 'record'], function() {
        Route::get('create', [DeliveryrecordController::class, 'index'])->name('create-record');
        Route::get('update', [DeliveryrecordController::class, 'update'])->name('update-record');
        Route::post('create-dr', [DeliveryrecordController::class, 'create_process'])->name('create-dr');
        Route::post('update-dr', [DeliveryrecordController::class, 'update_process'])->name('update-dr');
        Route::post('drop-waybill', [DeliveryrecordController::class, 'drop_waybill'])->name('drop-waybill');
        Route::get('generate-qr', [DeliveryrecordController::class, 'generate_qr'])->name('generate-qr');
    });

    Route::group(['prefix' => 'transfer'], function() {
        Route::get('/', [TransferController::class, 'index'])->name('transfer');
        Route::post('create', [TransferController::class, 'create'])->name('create-transfer');
    });
  
    Route::get('/routing/{code}/cod-collection', [RoutingController::class, 'codCollection'])->name('routing.cod-collection');
    
    Route::resource('/cod-collection', CodCollectionController::class);
  
    Route::get('/cod-collection/pdf/{id}/{type}', [CodCollectionController::class, 'createPdf'])->name('cod-collection.pdf');

    Route::get('/cod-collection/pdf-record', [CodCollectionController::class, 'pdf_record'])->name('cod-collection.pdf-record');

    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::resource('vendor', VendorController::class);
        Route::resource('hub', HubController::class);
        Route::resource('region', RegionController::class);
        Route::resource('courier', CourierController::class);
    });

    Route::post('upload-region', [RegionController::class, 'upload'])->name('upload-region');

    Route::post('courier/{id}/routing', [CourierController::class, 'getRouting'])->name('courier.routing');

    Route::group(['prefix' => 'inbound'], function() {
        Route::get('/', [InboundController::class, 'index'])->name('inbound');
        Route::post('create', [InboundController::class, 'create'])->name('create-inbound');
        Route::post('create-transfer', [InboundController::class, 'create_transfer'])->name('create-inbound-transfer');
    });
});