<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Query as QueryModel; // Alias to avoid conflict with class name
use App\Core\View;
use App\Core\Auth;

class QueryController {

    private $brandModel;
    private $queryModel;
    private $userId;
    private $userBrand;

    public function __construct() {
        // Ensure user is logged in
        if (!Auth::isLoggedIn()) {
            Auth::redirect("/login");
        }

        $this->userId = $_SESSION["user_id"];
        $this->brandModel = new Brand();
        $this->queryModel = new QueryModel();

        // Fetch the user's brand, required for most query actions
        $this->userBrand = $this->brandModel->findByUserId($this->userId);

        // If user has no brand, redirect them (except maybe for a specific message page)
        // For simplicity, redirecting to brand page where they'll be prompted to create one.
        if (!$this->userBrand && !$this->isRequestForSpecificPageWithoutBrand()) {
             $_SESSION["error_message"] = "Você precisa cadastrar uma marca antes de gerenciar consultas.";
             Auth::redirect("/brand");
        }
    }

    /**
     * Helper to check if the current request is for a page that doesn't require a brand.
     * (Not strictly needed here as all query actions depend on a brand, but good practice).
     */
    private function isRequestForSpecificPageWithoutBrand(): bool {
        // Example: Check if the requested URL is something like /queries/help
        // $url = $_GET["url"] ?? ";
        // return ($url === "queries/help");
        return false; // In this controller, all actions require a brand
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        if (!$this->userBrand) return; // Should have been redirected, but double-check

        $queries = $this->queryModel->findByBrandId((int)$this->userBrand->id);
        View::render("queries/index", [
            "queries" => $queries,
            "brandName" => $this->userBrand->name,
            "title" => "Minhas Consultas"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
         if (!$this->userBrand) return;

        View::render("queries/create", [
            "brandId" => $this->userBrand->id,
            "brandName" => $this->userBrand->name,
            "title" => "Adicionar Nova Consulta"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store() {
        if (!$this->userBrand) return;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $text = $_POST["text"] ?? ";
            $category = $_POST["category"] ?? null;
            $brandId = (int)$this->userBrand->id;

            if (empty($text)) {
                $_SESSION["error_message"] = "O texto da consulta é obrigatório.";
                header("Location: " . APP_URL . "/queries/create");
                exit;
            }

            // Add length validation if needed
            if (strlen($text) > 500) {
                 $_SESSION["error_message"] = "O texto da consulta não pode exceder 500 caracteres.";
                 header("Location: " . APP_URL . "/queries/create");
                 exit;
            }

            if ($this->queryModel->create($text, $category, $brandId)) {
                $_SESSION["success_message"] = "Consulta adicionada com sucesso! (Simulado)";
                // In a real app, this might trigger a background job.
                // For the prototype, we just confirm it's saved.
                header("Location: " . APP_URL . "/queries");
                exit;
            } else {
                $_SESSION["error_message"] = "Erro ao adicionar consulta.";
                header("Location: " . APP_URL . "/queries/create");
                exit;
            }
        }
        // If not POST, redirect
        header("Location: " . APP_URL . "/queries/create");
        exit;
    }

    // Other methods like show (details), edit, update, destroy could be added
    // but are not required by the initial spec for the prototype.

}

