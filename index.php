<?php

$container = require __DIR__ . '/config/app.php';

use App\Middlewares\Auth;
use App\Providers\BladeServiceProvider;
use App\Providers\Route;

session_start();

Auth::user();

if (!function_exists('view')) {
    function view($view, $data = []): string
    {
        return BladeServiceProvider::render($view, $data);
    }
}

require __DIR__ . '/routes/web.php';

Route::dispatch();

