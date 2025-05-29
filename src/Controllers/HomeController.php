<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;

class HomeController {

    public function index() {
        // If logged in, redirect to dashboard
        if (Auth::isLoggedIn()) {
            Auth::redirect("/dashboard");
        } else {
            // If not logged in, redirect to login page
            Auth::redirect("/login");
        }
    }
}

