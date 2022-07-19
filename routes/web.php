<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::resource('password-update', \App\Http\Controllers\PasswordUpdateController::class)
    ->only(['create','store']);
Route::resource('profile-update', \App\Http\Controllers\ProfileUpdateController::class)
    ->only(['create','store']);

Route::middleware('auth')->group(function(){
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::get('user/portal/{user}', [\App\Http\Controllers\UserController::class, 'portal'])->name('user.portal');

    Route::resource('password-update', \App\Http\Controllers\PasswordUpdateController::class)
        ->only(['create','store']);
    Route::resource('profile-update', \App\Http\Controllers\ProfileUpdateController::class)
        ->only(['create','store']);
});

Route::get('portal/{user}', \App\Http\Controllers\PortalController::class)->name('portal');


require __DIR__.'/auth.php';
