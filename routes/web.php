<?php

use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\updateController;
Use App\Http\Controllers\otpController;
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

Route::get('/update/{id}', [updateController::class, 'update']);

Route::get('/otp', [otpController::class, 'index'])->name('otp');

Route::post('/otp', [otpController::class, 'verify']);

Route::post('/update/{id}', [updateController::class, 'edit']);
