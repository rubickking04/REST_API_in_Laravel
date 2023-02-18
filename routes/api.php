<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Admin\Auth\LoginController as AdminLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(RegisterController::class)->group(function() {
    Route::post('/register', 'store')->name('register');
});
Route::controller(LoginController::class)->group(function() {
    Route::post('/login', 'login')->name('login');
});
Route::controller(LogoutController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
});
Route::middleware('auth:admin')->get('/admin', function (Request $request) {
    return $request->user();
});
Route::controller(AdminLoginController::class)->group(function () {
    Route::post('/admin/login', 'login')->name('login');
});
