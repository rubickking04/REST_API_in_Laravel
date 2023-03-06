<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\API\Admin\Auth\LogoutController as AdminLogoutController;
/*
|--------------------------------------------------------------------------
| API Routes for User Authentication
|--------------------------------------------------------------------------
*/
Route::controller(RegisterController::class)->group(function() {
    Route::post('/register', 'store');
});
Route::controller(LoginController::class)->group(function() {
    Route::post('/login', 'login');
    Route::get('/login', 'index')->name('login');
});
Route::middleware('auth:user-api')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Guards the API Routes for User Authentication
    |--------------------------------------------------------------------------
    */
    Route::controller(HomeController::class)->group(function () {
        Route::get('/user', 'auth')->name('home');
    });
    Route::controller(LogoutController::class)->group(function () {
        Route::post('/logout', 'logout');
    });
});
/*
|--------------------------------------------------------------------------
| API Routes for Admin Authentication
|--------------------------------------------------------------------------
*/
Route::controller(AdminLoginController::class)->group(function () {
    Route::post('/admin/login', 'login')->name('admin.login');
});
Route::middleware('auth:admin-api')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Guards the API Routes for Admin Authentication
    |--------------------------------------------------------------------------
    */
    Route::get('/admin', function(Request $request) {
        return $request->user();
    });
    Route::controller(AdminLogoutController::class)->group(function () {
        Route::post('/admin/logout', 'logout')->name('admin.logout');
    });
});
