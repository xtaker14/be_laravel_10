<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* 

jalankan ini :
`php artisan route:cache` 
untuk menyimpan cache (performance lebih cepat tapi minusnya setelah ada penambahan / pengubahan code route tidak berefek kecuali di lakukan `php artisan route:clear` terlebih dahulu lalu jalankan `php artisan route:cache` nya lagi)

fungsi middleware throttle:60,1 :
rate limiting / membatasi frekuensi permintaan yang diterima oleh app dari satu alamat IP tertentu
'60' adalah jumlah permintaan yang diizinkan dalam satu menit.
'1' adalah jumlah menit yang digunakan sebagai periode waktu atau "jendela" untuk membatasi permintaan. 

fungsi middleware role:super-admin :
dibutuhkan token user yang memiliki role 'super-admin' untuk mengakses end point

fungsi middleware permission:all :
dibutuhkan token user yang memiliki permission 'all' untuk mengakses end point

fungsi middleware auth:api :
untuk validating akses menggunakan passport/sanctum/JWT Token 

*/ 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// $v = env('API_VERSION', 1);

// Route::group([
//     'prefix' => 'tms/mobile/'.$v,
//     'middleware' => ['throttle:60,1'],
// ], function () {
//     includeRouteFiles(__DIR__.'/api/');
// }); 
