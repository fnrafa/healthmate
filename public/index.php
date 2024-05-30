<?php

session_start();
$container = require __DIR__ . '/../config/app.php';

use App\Middlewares\Auth;
use App\Providers\ExceptionHandler;
use App\Providers\Route;

ExceptionHandler::init();

Auth::user();

require __DIR__ . '/../routes/web.php';

Route::dispatch();

