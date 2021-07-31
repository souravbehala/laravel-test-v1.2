<?php

Use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\AuthController;
Use App\Http\Controllers\CreateUser;
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {


    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('update', [AuthController::class, 'update']);
    Route::post('createuser', [CreateUser::class, 'createUser']);
   

});