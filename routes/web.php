<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\ConsultationController;
use App\Controllers\DashboardController;
use App\Controllers\HistoryController;
use App\Controllers\MessageController;
use App\Controllers\RequestController;
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
Route::get('/consultation', [ConsultationController::class, 'index']);
Route::post('/request/consultation', [ConsultationController::class, 'requestConsultation']);
Route::post('/update/consultation', [ConsultationController::class, 'update']);
Route::get('/history', [HistoryController::class, 'index']);
Route::get('/request', [RequestController::class, 'index']);
Route::post('/send-message', [MessageController::class, 'sendMessage']);
Route::post('/update-status-message', [MessageController::class, 'updateMessageStatus']);


