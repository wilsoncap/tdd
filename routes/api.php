<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Route::post('/login', [\app\Http\Controllers\LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'login']);
