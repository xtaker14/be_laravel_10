<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderController;

Route::group(['prefix' => 'order', 'middleware' => [
    'acc.json',
    'auth:api',
    'role:COURIER,api',
]], function () {
    Route::post('scan-delivery-record', [OrderController::class, 'scanDeliveryRecord']);
    Route::get('summary-delivery-record', [OrderController::class, 'summaryDeliveryRecord']);
    Route::get('delivery-record', [OrderController::class, 'deliveryRecordList']);
    Route::get('delivery-record-detail', [OrderController::class, 'deliveryRecordDetail']);
    Route::get('download-delivery-record', [OrderController::class, 'downloadDeliveryRecord']);

    Route::post('scan-delivery', [OrderController::class, 'scanDelivery']);
    Route::get('latest-delivery', [OrderController::class, 'latestDelivery']);
    Route::get('delivery', [OrderController::class, 'deliveryList']);
    Route::post('sorting-delivery-numbers', [OrderController::class, 'sortingDeliveryNumbers']);
    Route::get('delivery-detail', [OrderController::class, 'deliveryDetail']);
    Route::post('update-delivery-detail', [OrderController::class, 'updateDeliveryDetail']);
});

Route::group(['prefix' => 'order', 'middleware' => [
    'auth:api',
    'role:COURIER,api',
]], function () {
    Route::get('pdf-delivery-record/{id}/{type}', [\App\Http\Controllers\web\CodCollectionController::class, 'createPdf'])
        ->name('mobile-pdf-delivery-record.pdf');
});

