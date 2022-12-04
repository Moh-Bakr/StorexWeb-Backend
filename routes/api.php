<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    
    Route::get('users', [AuthController::class, 'GetAllUsers'])->name('users');
    Route::post('createuser', [AuthController::class, 'createUser'])->name('createuser');

    Route::resource('category', CategoryController::class);
    Route::resource('movies', MovieController::class);
    Route::get('movies/search', [ MovieController::class, 'searchMovies']);
    
});


Route::post('login', [AuthController::class, 'login'])->name('login');