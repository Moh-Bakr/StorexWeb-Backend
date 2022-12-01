<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'Login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/createuser', [AuthController::class, 'CreateUser']);
});