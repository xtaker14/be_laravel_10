<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;


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
Route::get('/', $controller_path . '\web\login@index')->name('login');

// dashboard
Route::get('/dashboard', $controller_path . '\web\dashboard@index')->name('dashboard');

// delivery order
Route::get('/order/request-waybill', $controller_path . '\web\deliveryOrder@index')->name('request-waybill');
Route::get('/order/waybill-list', $controller_path . '\web\deliveryOrder@list')->name('waybill-list');
Route::get('/order/adjustment', $controller_path . '\web\deliveryOrder@adjustment')->name('adjustment');
Route::get('/dashboard/analytics', $controller_path . '\dashboard\Analytics@index')->name('dashboard-analytics');

// language
Route::get('lang/{locale}', $controller_path . '\language\LanguageController@swap');

// laravel example
Route::get('/laravel/user-management', [UserManagement::class, 'UserManagement'])->name('laravel-example-user-management');
Route::resource('/user-list', UserManagement::class);
