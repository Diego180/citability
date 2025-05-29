<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;

class HomeController {

   public function index() {
    // Temporariamente desativado por ausência da classe Auth
    // if (Auth::isLoggedIn()) {
    //     Auth::redirect("/dashboard");
    // } else {
    //     Auth::redirect("/login");
    // }

    // Exibição de teste
    echo "<h1>Protótipo Citability funcionando!</h1>";
}

