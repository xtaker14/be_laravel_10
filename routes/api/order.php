<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\OrderController;

Route::group(['prefix' => 'order', 'middleware' => [ 
    'auth:api',
    'role:COURIER,api',
]], function () {
    Route::post('scan-delivery-record', [OrderController::class, 'scanDeliveryRecord']);
    Route::get('summary-delivery-record', [OrderController::class, 'summaryDeliveryRecord']);
    Route::get('delivery-record', [OrderController::class, 'deliveryRecordList']);

    Route::get('latest-delivery', [OrderController::class, 'latestDelivery']);
    Route::get('delivery', [OrderController::class, 'deliveryList']);
    Route::post('sorting-delivery-numbers', [OrderController::class, 'sortingDeliveryNumbers']);
    Route::get('delivery-detail', [OrderController::class, 'deliveryDetail']);
    Route::post('update-delivery-detail', [OrderController::class, 'updateDeliveryDetail']);

    // Route::get('/generate-pdf', function (Request $request) {
    //     $data = [
    //         'courier_name' => 'Handani',
    //     ];

    //     $view = ($request->header('User-Agent') && strpos($request->header('User-Agent'), 'Mobile') !== false) ? 'pdf_mobile' : 'pdf_desktop';

    //     $pdf = PDF::loadView($view, $data);
    //     return $pdf->download('delivery-record.pdf');
    // });
});

