<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
//Route::post('/login', [LoginController::class, 'login']);

Route::post('/users', [App\Http\Controllers\RegisterController::class, 'store']);
