<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Query as QueryModel;
use App\Models\Response as ResponseModel;
use App\Core\View;
use App\Core\Auth;

class ResultController {

    private $brandModel;
    private $queryModel;
    private $responseModel;
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
        $this->responseModel = new ResponseModel();

        // Fetch the user's brand, required for context
        $this->userBrand = $this->brandModel->findByUserId($this->userId);

        // If user has no brand, they shouldn't be able to see results
        if (!$this->userBrand) {
             $_SESSION["error_message"] = "Você precisa cadastrar uma marca para ver os resultados.";
             Auth::redirect("/brand");
        }
    }

    /**
     * Display the mocked results for a specific query.
     */
    public function index() {
        if (!$this->userBrand) return; // Redirected already, but safety check

        $queryId = filter_input(INPUT_GET, "query_id", FILTER_VALIDATE_INT);

        if (!$queryId) {
            $_SESSION["error_message"] = "ID da consulta inválido ou não fornecido.";
            Auth::redirect("/queries"); // Redirect to queries list
            return; // Added return for clarity
        }

        // Verify the query belongs to the user's brand
        $query = $this->queryModel->findByIdAndBrandId($queryId, (int)$this->userBrand->id);

        if (!$query) {
            $_SESSION["error_message"] = "Consulta não encontrada ou não pertence à sua marca.";
            Auth::redirect("/queries");
            return; // Added return for clarity
        }

        // Generate mock responses using the Response model
        $mockResponses = $this->responseModel->getMockResponsesForQuery($queryId, $this->userBrand->name, $this->userBrand->domain);

        // Prepare data for charts (simple example: count mentions by AI type)
        $chartData = [
            "labels" => [],
            "mentions" => [],
            "noMentions" => [],
        ];
        $mentionCounts = [];
        foreach ($mockResponses as $response) {
            $aiType = $response->ai_type;
            if (!isset($mentionCounts[$aiType])) {
                $mentionCounts[$aiType] = ["positive" => 0, "neutral_or_none" => 0];
            }
            if ($response->has_mention) {
                $mentionCounts[$aiType]["positive"]++;
            } else {
                $mentionCounts[$aiType]["neutral_or_none"]++;
            }
        }

        foreach ($mentionCounts as $aiType => $counts) {
            $chartData["labels"][] = $aiType;
            $chartData["mentions"][] = $counts["positive"];
            $chartData["noMentions"][] = $counts["neutral_or_none"];
        }


        View::render("results/index", [
            "query" => $query,
            "responses" => $mockResponses,
            "brandName" => $this->userBrand->name,
            "chartData" => json_encode($chartData), // Pass JSON encoded data to view
            "title" => "Resultados da Consulta (Simulado)"
        ]);
    }
}

