<?php

namespace App\Controllers;

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

}