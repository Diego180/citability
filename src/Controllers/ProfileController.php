<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\View;
use App\Core\Auth;

class ProfileController {

    private $userModel;
    private $userId;
    private $currentUser;

    public function __construct() {
        // Ensure user is logged in
        if (!Auth::isLoggedIn()) {
            Auth::redirect("/login");
        }

        $this->userId = $_SESSION["user_id"];
        $this->userModel = new User();

        // Fetch current user data (excluding password hash)
        $this->currentUser = $this->userModel->findById($this->userId);

        if (!$this->currentUser) {
            // Should not happen if logged in, but handle gracefully
            Auth::redirect("/logout");
        }
    }

    /**
     * Show the user profile page.
     */
    public function show() {
        View::render("profile/show", [
            "user" => $this->currentUser,
            "title" => "Meu Perfil"
        ]);
    }

    /**
     * Show the form for editing the user profile (only password change for now).
     */
    public function edit() {
        View::render("profile/edit", [
            "user" => $this->currentUser,
            "title" => "Alterar Senha"
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $currentPassword = $_POST["current_password"] ?? ";
            $newPassword = $_POST["new_password"] ?? ";
            $confirmPassword = $_POST["confirm_password"] ?? ";

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION["error_message"] = "Todos os campos de senha são obrigatórios.";
                Auth::redirect("/profile/edit");
                return; // Added return
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION["error_message"] = "A nova senha e a confirmação não coincidem.";
                Auth::redirect("/profile/edit");
                return; // Added return
            }

            // Verify current password
            // Need to fetch user with hash for verification
            $userWithHash = $this->userModel->findByUsername($this->currentUser->username);
            if (!$userWithHash || !password_verify($currentPassword, $userWithHash->password_hash)) {
                 $_SESSION["error_message"] = "Senha atual incorreta.";
                 Auth::redirect("/profile/edit");
                 return; // Added return
            }

            // Update password
            if ($this->userModel->updatePassword($this->userId, $newPassword)) {
                $_SESSION["success_message"] = "Senha alterada com sucesso!";
                Auth::redirect("/profile");
            } else {
                $_SESSION["error_message"] = "Erro ao alterar a senha. Tente novamente.";
                Auth::redirect("/profile/edit");
            }
        } else {
            // If not POST, redirect
            Auth::redirect("/profile/edit");
        }
    }
}

