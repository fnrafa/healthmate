<?php

namespace App\Controllers;

use App\Models\User;
use App\Providers\Request;
use Exception;

class AdminController
{
    public function index(): string
    {
        $user = auth();

        if (!$user) {
            return view('login', showAlert(403, 'You must be logged in to access this page'));
        }
        if ($user->role != 'admin') {

            return view('login', showAlert(403, "Unauthorized, you can't access this page"));
        }
        return view('admin.index');
    }


    public function addUser(Request $request): string
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:patient,doctor,hospital,admin',
            'password' => 'required|min:6|confirmed'
        ]);

        if (!$validate) {
            return view('admin.index', ['errors' => $request->getErrors()]);
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => hash_make($request->password),
                'role' => $request->role
            ]);
            redirect('/');
        } catch (Exception $e) {
            return view('admin.index', ['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function deleteUser(Request $request): string
    {
        $validate = $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        if (!$validate) {
            return view('admin.index', ['errors' => $request->getErrors()]);
        }

        try {
            $user = User::findOrFail($request->id);
            $user->delete();

            redirect('/');
        } catch (Exception $e) {
            return view('admin.index', ['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    public function updateUser(Request $request): string
    {
        $validate = $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string',
            'role' => 'required|in:patient,doctor,hospital,admin'
        ]);

        if (!$validate) {
            return view('admin.index', ['errors' => $request->getErrors()]);
        }

        try {
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->role = $request->role;
            $user->save();

            redirect('/');
        } catch (Exception $e) {
            return view('admin.index', ['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

}