<?php

session_start();
$container = require __DIR__ . '/config/app.php';

use App\Middlewares\Auth;
use App\Providers\BladeServiceProvider;
use App\Providers\Route;

Auth::user();

if (!function_exists('view')) {
    function view($view, $data = []): string
    {
        return BladeServiceProvider::render($view, $data);
    }
}

require __DIR__ . '/routes/web.php';

Route::dispatch();

