<?php

namespace App\Middlewares;

use function App\Providers\view;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            echo view('login', ['error' => 'You must be logged in to access this page']);
            exit();
        }
    }
}
