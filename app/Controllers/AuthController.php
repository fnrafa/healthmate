<?php

namespace App\Controllers;

use App\Middlewares\Auth;
use App\Models\User;
use App\Providers\Request;
use Exception;

class AuthController
{
    public function showLoginForm(): string
    {
        return view('login');
    }

    public function login(Request $request): string
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return view('login', ['error' => 'Email not registered']);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            redirect('/');
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

    public function register(Request $request): string
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        if (!$validate) {
            return view('register', ['errors' => $request->getErrors()]);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => hash_make($request->password),
                'role' => User::ROLE_PATIENT
            ]);
            redirect('login');
        } catch (Exception $e) {
            return view('register', ['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
