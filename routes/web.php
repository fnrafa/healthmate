<?php

use App\Controllers\AuthController;
use App\Middlewares\AuthMiddleware;
use App\Providers\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'], 'login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'], 'register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/dashboard', function () {
    AuthMiddleware::handle();
    return view('dashboard');
}, 'dashboard');