<?php

namespace App\Controllers;

use App\Core\View;

class AuthController
{
    public function showLoginForm()
    {
        echo "<h1>Tela de Login</h1>";
        // Aqui você pode incluir um formulário real futuramente
    }

    public function login()
    {
        // Simulação de login (normalmente aqui validaria o usuário e iniciaria a sessão)
        echo "Login simulado.";
    }

    public function showRegisterForm()
    {
        echo "<h1>Tela de Cadastro</h1>";
        // Aqui você pode incluir um formulário real futuramente
    }

    public function register()
    {
        // Simulação de registro
        echo "Registro simulado.";
    }

    public function logout()
    {
        // Simulação de logout
        echo "Logout simulado.";
    }
}
