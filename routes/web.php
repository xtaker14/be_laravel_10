<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\web\UserController;
use App\Http\Controllers\web\RoleController;

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

// Route::get('/image-s3/{path}', function ($path) {
//     $url = Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addMinutes(15));
//     $response = Http::get($url);

//     if ($response->successful()) {
//         $contentType = $response->header('content-type');
//         return response($response->body())->header('Content-Type', $contentType);
//     }

//     return response('Image not found', 404);
// })->where('path', '.*')->name('image-s3');

Route::prefix('artisan')->group(function () {
    Route::get('/schedule-run', function(){
        Artisan::call('schedule:run');

        return "Scheduled tasks executed.";
    });

    Route::get('/check-dr-collected', function(){
        Artisan::call('check:delivery-record-collected');

        return "Scheduled tasks executed.";
    });
});

Auth::routes();

Route::get('/', function(){
    if (Auth::check()) {
        return redirect()->route('configuration.user');
    } else {
        return redirect()->route('login');
    }
});

Route::group(['middleware' => ['auth']], function()
{
    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::resource('user', UserController::class)->middleware('can:user-access');
        Route::resource('role', RoleController::class)->middleware('can:user-access');
    });
});