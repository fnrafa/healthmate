<?php

namespace App\Middlewares;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Auth
{
    protected static ?User $user = null;

    public static function user(): ?User
    {
        if (isset($_SESSION['user_id'])) {
            self::$user = User::find($_SESSION['user_id']);
        }
        return self::$user;
    }

    public static function id()
    {
        return self::user() ? self::user()->id : null;
    }

    public static function check(): bool
    {
        return (bool)self::user();
    }

    public static function attempt($credentials): bool
    {
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $_SESSION['user_id'] = $user->id;
            self::$user = $user;
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        self::$user = null;
    }
}
