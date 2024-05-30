<?php

use App\Middlewares\Auth;
use App\Models\User;

if (!function_exists('auth')) {
    function auth(): ?User
    {
        return Auth::user();
    }
}
