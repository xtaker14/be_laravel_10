<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\DeliveryorderController;
use App\Http\Controllers\web\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login-validation', [LoginController::class, 'login_validation'])->name('login-validation');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// delivery order
Route::get('/order/request-waybill', [DeliveryorderController::class, 'index'])->name('request-waybill');
Route::get('/order/waybill-list', [DeliveryorderController::class, 'list'])->name('waybill-list');
Route::get('/order/adjustment', [DeliveryorderController::class, 'adjustment'])->name('adjustment');

// language
Route::get('lang/{locale}', $controller_path . '\language\LanguageController@swap');
