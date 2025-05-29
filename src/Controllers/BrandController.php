<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Core\View;
use App\Core\Auth; // Assuming an Auth helper/class to check login status

class BrandController {

    public function __construct() {
        // Ensure user is logged in for all brand actions
        if (!Auth::isLoggedIn()) {
            Auth::redirect("/login");
        }
    }

    /**
     * Show the user's brand or the create form if none exists.
     */
    public function show() {
        $brandModel = new Brand();
        $userId = $_SESSION["user_id"];
        $brand = $brandModel->findByUserId($userId);

        if ($brand) {
            View::render("brand/show", ["brand" => $brand, "title" => "Minha Marca"]);
        } else {
            // If no brand, redirect to create form (or show a message)
            // header("Location: " . APP_URL . "/brand/create");
            // exit;
            // Or render a view telling them to create a brand
             View::render("brand/no_brand", ["title" => "Cadastrar Marca"]);
        }
    }

    /**
     * Show the form to create a new brand.
     */
    public function create() {
        $brandModel = new Brand();
        $userId = $_SESSION["user_id"];
        // Prevent creating if one already exists
        if ($brandModel->findByUserId($userId)) {
             $_SESSION["error_message"] = "Você já possui uma marca cadastrada.";
             header("Location: " . APP_URL . "/brand");
             exit;
        }
        View::render("brand/create", ["title" => "Cadastrar Nova Marca"]);
    }

    /**
     * Store the newly created brand in storage.
     */
    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"] ?? ";
            $domain = $_POST["domain"] ?? ";
            $userId = $_SESSION["user_id"];

            if (empty($name) || empty($domain)) {
                $_SESSION["error_message"] = "Nome e domínio da marca são obrigatórios.";
                header("Location: " . APP_URL . "/brand/create");
                exit;
            }

            // Basic domain validation (example)
            if (!filter_var("http://" . $domain, FILTER_VALIDATE_URL)) { // Add http for validation
                 $_SESSION["error_message"] = "Domínio inválido.";
                 header("Location: " . APP_URL . "/brand/create");
                 exit;
            }

            $brandModel = new Brand();
            if ($brandModel->create($name, $domain, $userId)) {
                $_SESSION["success_message"] = "Marca cadastrada com sucesso!";
                header("Location: " . APP_URL . "/brand");
                exit;
            } else {
                $_SESSION["error_message"] = "Erro ao cadastrar marca. Você já pode ter uma marca cadastrada.";
                header("Location: " . APP_URL . "/brand/create");
                exit;
            }
        }
        // If not POST, redirect
        header("Location: " . APP_URL . "/brand/create");
        exit;
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit() {
        $brandModel = new Brand();
        $userId = $_SESSION["user_id"];
        $brand = $brandModel->findByUserId($userId); // Get brand by user ID

        if (!$brand) {
            $_SESSION["error_message"] = "Marca não encontrada ou não pertence a você.";
            header("Location: " . APP_URL . "/brand");
            exit;
        }

        View::render("brand/edit", ["brand" => $brand, "title" => "Editar Marca"]);
    }

    /**
     * Update the specified brand in storage.
     */
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"] ?? ";
            $domain = $_POST["domain"] ?? ";
            $brandId = $_POST["brand_id"] ?? null; // Get brand ID from form
            $userId = $_SESSION["user_id"];

            if (empty($name) || empty($domain) || $brandId === null) {
                $_SESSION["error_message"] = "Nome, domínio e ID da marca são obrigatórios.";
                // Redirect back to edit form if possible, otherwise to brand page
                 header("Location: " . APP_URL . "/brand/edit"); // Assumes edit route doesn't need ID in URL
                 exit;
            }

             // Basic domain validation (example)
            if (!filter_var("http://" . $domain, FILTER_VALIDATE_URL)) {
                 $_SESSION["error_message"] = "Domínio inválido.";
                 header("Location: " . APP_URL . "/brand/edit");
                 exit;
            }

            $brandModel = new Brand();

            // Verify ownership before updating
            $brand = $brandModel->findByIdAndUserId((int)$brandId, $userId);
            if (!$brand) {
                 $_SESSION["error_message"] = "Operação não permitida. Marca não encontrada ou não pertence a você.";
                 header("Location: " . APP_URL . "/brand");
                 exit;
            }

            if ($brandModel->update((int)$brandId, $name, $domain, $userId)) {
                $_SESSION["success_message"] = "Marca atualizada com sucesso!";
                header("Location: " . APP_URL . "/brand");
                exit;
            } else {
                $_SESSION["error_message"] = "Erro ao atualizar marca.";
                header("Location: " . APP_URL . "/brand/edit"); // Redirect back to edit form
                exit;
            }
        }
        // If not POST, redirect
        header("Location: " . APP_URL . "/brand");
        exit;
    }
}

// Simple Auth Helper (Create this in Core or Helpers)
namespace App\Core;

class Auth {
    public static function isLoggedIn(): bool {
         if (session_status() == PHP_SESSION_NONE) {
            session_name(SESSION_NAME); // Ensure session name is set
            session_start();
        }
        return isset($_SESSION["user_id"]);
    }

    public static function redirect(string $path) {
        header("Location: " . APP_URL . $path);
        exit;
    }
}


