<?php

use App\Middlewares\Auth;
use App\Models\User;
use App\Providers\BladeServiceProvider;
use Illuminate\Hashing\BcryptHasher;

if (!function_exists('auth')) {
    function auth(): ?User
    {
        return Auth::user();
    }
}

if (!function_exists('old')) {
    function old($key)
    {
        return $_POST[$key] ?? '';
    }
}

if (!function_exists('view')) {
    function view($view, $data = []): string
    {
        return BladeServiceProvider::render($view, $data);
    }
}

if (!function_exists('hash_make')) {
    function hash_make($value): string
    {
        $hash = new BcryptHasher();
        return $hash->make($value);
    }
}