<?php

use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\DeliveryorderController;
use App\Http\Controllers\web\AdjustmentController;
use App\Http\Controllers\web\DeliveryrecordController;
use App\Http\Controllers\web\LoginController;
use App\Http\Controllers\web\VendorController;
use App\Http\Controllers\web\HubController;
use App\Http\Controllers\web\RegionController;
use App\Http\Controllers\web\CourierController;
use App\Http\Controllers\web\TransferController;
use App\Http\Controllers\web\CodCollectionController;
use App\Http\Controllers\web\RoutingController;
use App\Http\Controllers\web\InboundController;
use App\Http\Controllers\web\ReportingController;
use App\Http\Controllers\web\OrganizationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

Route::get('/image-s3/{path}', function ($path) {
    $url = Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addMinutes(15));
    $response = Http::get($url);

    if ($response->successful()) {
        $contentType = $response->header('content-type');
        return response($response->body())->header('Content-Type', $contentType);
    }

    return response('Image not found', 404);
})->where('path', '.*')->name('image-s3');

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
        Route::get('upload-result', [DeliveryorderController::class, 'upload_result'])->name('upload-result');

        Route::prefix('adjustment')->name('adjustment.')->group(function () {
            Route::get('master-waybill', [AdjustmentController::class, 'masterWaybill'])->name('master-waybill');
            Route::post('master-waybill', [AdjustmentController::class, 'masterWaybillStore'])->name('master-waybill');
            Route::post('master-waybill-information', [AdjustmentController::class, 'masterWaybillInfo'])->name('master-waybill-information');

            Route::get('single-waybill', [AdjustmentController::class, 'singleWaybill'])->name('single-waybill');
            Route::post('single-waybill', [AdjustmentController::class, 'singleWaybillStore'])->name('single-waybill');
            Route::post('single-waybill-information', [AdjustmentController::class, 'singleWaybillInfo'])->name('single-waybill-information');

            Route::get('delivery-process', [AdjustmentController::class, 'deliveryProcess'])->name('delivery-process');
            Route::post('delivery-process', [AdjustmentController::class, 'deliveryProcessStore'])->name('delivery-process');
            Route::post('delivery-process-information', [AdjustmentController::class, 'deliveryProcessInfo'])->name('delivery-process-information');
        });
      
        Route::get('detail-waybill/{id}', [DeliveryorderController::class, 'detail_waybill'])->name('detail-waybill');
    });

    Route::group(['prefix' => 'record'], function() {
        Route::get('create', [DeliveryrecordController::class, 'index'])->name('create-record');
        Route::get('update', [DeliveryrecordController::class, 'update'])->name('update-record');
        Route::post('create-dr', [DeliveryrecordController::class, 'create_process'])->name('create-dr');
        Route::post('update-dr', [DeliveryrecordController::class, 'update_process'])->name('update-dr');
        Route::post('drop-waybill', [DeliveryrecordController::class, 'drop_waybill'])->name('drop-waybill');
        Route::get('generate-qr', [DeliveryrecordController::class, 'generate_qr'])->name('generate-qr');
        Route::post('check-courier', [DeliveryrecordController::class, 'checkCourier'])->name('check-courier');
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
        Route::resource('organization', OrganizationController::class);
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
        Route::post('create-undelivered', [InboundController::class, 'create_undelivered'])->name('create-inbound-undelivered');
        Route::post('check-delivery-record', [InboundController::class, 'check_delivery_record'])->name('check-delivery-record');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('inbound', [ReportingController::class, 'inbound'])->name('inbound');
        Route::post('inbound-detail', [ReportingController::class, 'inboundDetail'])->name('inbound-detail');

        Route::get('transfer', [ReportingController::class, 'transfer'])->name('transfer');
        Route::post('report_transfer', [ReportingController::class, 'report_transfer'])->name('report_transfer');

        Route::get('waybill', [ReportingController::class, 'waybill'])->name('waybill');
        Route::post('waybill-transaction', [ReportingController::class, 'waybillTransaction'])->name('waybill-transaction');
        Route::post('waybill-history', [ReportingController::class, 'waybillHistory'])->name('waybill-history');
        
        Route::get('delivery-record-report', [ReportingController::class, 'deliveryrecordModule'])->name('delivery-record-report');
        Route::post('record-detail-report', [ReportingController::class, 'detailrecordReport'])->name('record-detail-report');
        Route::post('courier-perf-report', [ReportingController::class, 'courierperfReport'])->name('courier-perf-report');
        
        Route::get('cod-report', [ReportingController::class, 'codReport'])->name('cod-report');
        Route::post('cod-report-summary', [ReportingController::class, 'codreportSummary'])->name('cod-report-summary');
        Route::post('cod-report-detail', [ReportingController::class, 'codreportDetail'])->name('cod-report-detail');
    });
});