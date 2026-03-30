<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (! auth()->loggedIn()) {
        return redirect()->to('/login');
        }
        // 1. Check if the user is logged in
        if (auth()->loggedIn()) {

            // 3. If Admin, send to Admin Dashboard & Admin Dashboard
            if (auth()->user()->inGroup('superadmin','admin')) {
                return redirect()->to('/admin/dashboard');
            }

            // 4. If SACCO User, send to SACCO Dashboard
            if (auth()->user()->inGroup('sacco_user')) {
                return redirect()->to('/sacco/dashboard');
            }
        }

        // 5. If not logged in at all, show the default welcome page (or login)
        return "User has no assigned regulatory role";
    }
}
