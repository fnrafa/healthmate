<?php

namespace App\Controllers;

use App\Middlewares\Auth;
use App\Models\User;
use App\Providers\Request;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function showLoginForm(): string
    {
        return view('login');
    }

    public function login(Request $request): string
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return view('dashboard');
        } else {
            return view('login', ['error' => 'Invalid credentials']);
        }
    }

    public function logout(): string
    {
        Auth::logout();
        return view('login');
    }

    public function showRegistrationForm(): string
    {
        return view('register');
    }

    /**
     * @throws Exception
     */
    public function register(Request $request): string
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $_SESSION['user_id'] = $user->id;
        return view('dashboard');
    }
}
