<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Providers\Route;

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/add-user', [AdminController::class, 'addUser']);
Route::post('/delete-user', [AdminController::class, 'deleteUser']);
Route::post('/update-user', [AdminController::class, 'updateUser']);

Route::get('/', [DashboardController::class, 'index']);

