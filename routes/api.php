<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::apiResource('users', UserController::class);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/auth/profile', [AuthController::class, 'profile']);
// Route::post('/auth/forgot', [AuthController::class, 'forgot']);
// Route::post('/auth/reset', [AuthController::class, 'reset']);