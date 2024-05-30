<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            echo view('login');
            exit();
        }
    }
}