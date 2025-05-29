<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\View; // Assuming a simple View class later or direct require

class AuthController {

    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_name(SESSION_NAME); // Use defined session name
            session_start();
        }
    }

    /**
     * Show the login page
     */
    public function showLoginForm() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION["user_id"])) {
            header("Location: " . APP_URL . "/dashboard"); // Assuming /dashboard route
            exit;
        }
        // Load login view (we will create this view file later)
        // For now, just a placeholder action
        // View::render("auth/login");
        echo "Login form placeholder"; // Placeholder
    }

    /**
     * Process the login attempt
     */
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"] ?? ";
            $password = $_POST["password"] ?? ";

            if (empty($username) || empty($password)) {
                // Handle error - fields required
                $_SESSION["error_message"] = "Usuário e senha são obrigatórios.";
                header("Location: " . APP_URL . "/login");
                exit;
            }

            $userModel = new User();
            $user = $userModel->verifyPassword($username, $password);

            if ($user) {
                // Login successful
                $_SESSION["user_id"] = $user->id;
                $_SESSION["username"] = $user->username;
                unset($_SESSION["error_message"]); // Clear any previous error
                header("Location: " . APP_URL . "/dashboard"); // Redirect to dashboard
                exit;
            } else {
                // Login failed
                $_SESSION["error_message"] = "Usuário ou senha inválidos.";
                header("Location: " . APP_URL . "/login");
                exit;
            }
        }
        // If not POST, redirect to login form
        header("Location: " . APP_URL . "/login");
        exit;
    }

    /**
     * Show the registration page
     */
    public function showRegisterForm() {
         // If already logged in, redirect to dashboard
        if (isset($_SESSION["user_id"])) {
            header("Location: " . APP_URL . "/dashboard");
            exit;
        }
        // Load register view (we will create this view file later)
        // View::render("auth/register");
         echo "Register form placeholder"; // Placeholder
    }

    /**
     * Process the registration attempt
     */
    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"] ?? ";
            $password = $_POST["password"] ?? ";
            $confirm_password = $_POST["confirm_password"] ?? ";

            // Basic validation
            if (empty($username) || empty($password) || empty($confirm_password)) {
                $_SESSION["error_message"] = "Todos os campos são obrigatórios.";
                header("Location: " . APP_URL . "/register");
                exit;
            }

            if ($password !== $confirm_password) {
                $_SESSION["error_message"] = "As senhas não coincidem.";
                header("Location: " . APP_URL . "/register");
                exit;
            }

            // Add more validation if needed (e.g., password strength)

            $userModel = new User();

            // Check if username exists before attempting creation
            if ($userModel->findByUsername($username)) {
                 $_SESSION["error_message"] = "Nome de usuário já existe.";
                 header("Location: " . APP_URL . "/register");
                 exit;
            }

            if ($userModel->create($username, $password)) {
                // Registration successful - redirect to login or auto-login
                $_SESSION["success_message"] = "Cadastro realizado com sucesso! Faça o login.";
                header("Location: " . APP_URL . "/login");
                exit;
            } else {
                // Registration failed
                $_SESSION["error_message"] = "Erro ao registrar usuário. Tente novamente.";
                header("Location: " . APP_URL . "/register");
                exit;
            }
        }
        // If not POST, redirect to register form
        header("Location: " . APP_URL . "/register");
        exit;
    }

    /**
     * Log the user out
     */
    public function logout() {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), ", time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        // Redirect to login page
        header("Location: " . APP_URL . "/login");
        exit;
    }
}

