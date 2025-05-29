<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Models\Brand;
use App\Models\Query as QueryModel;
use App\Models\Response as ResponseModel;

class DashboardController {

    private $userId;
    private $userBrand;

    public function __construct() {
        // Ensure user is logged in
        if (!Auth::isLoggedIn()) {
            Auth::redirect("/login");
        }
        $this->userId = $_SESSION["user_id"];

        // Fetch brand info for dashboard context
        $brandModel = new Brand();
        $this->userBrand = $brandModel->findByUserId($this->userId);
    }

    /**
     * Show the application dashboard.
     */
    public function index() {
        $stats = [
            "total_queries" => 0,
            "total_mentions" => 0, // Mocked/Simulated
            "ai_coverage" => ["ChatGPT" => false, "Perplexity" => false, "Gemini" => false, "Google SGE" => false] // Mocked
        ];

        if ($this->userBrand) {
            // Simulate fetching stats
            $queryModel = new QueryModel();
            $queries = $queryModel->findByBrandId((int)$this->userBrand->id);
            $stats["total_queries"] = count($queries);

            // Simulate calculating mentions and coverage based on mock responses
            // This is a simplified simulation for the dashboard
            if ($stats["total_queries"] > 0) {
                $responseModel = new ResponseModel();
                // Get mock responses for the *first* query just for a dashboard sample
                $mockResponses = $responseModel->getMockResponsesForQuery((int)$queries[0]->id, $this->userBrand->name, $this->userBrand->domain);
                $mentions = 0;
                $coverage = [];
                foreach ($mockResponses as $resp) {
                    if ($resp->has_mention) {
                        $mentions++;
                    }
                    $coverage[$resp->ai_type] = true;
                }
                $stats["total_mentions"] = $mentions * $stats["total_queries"]; // Very rough simulation
                $stats["ai_coverage"] = array_merge($stats["ai_coverage"], $coverage);
            }
        }

        View::render("dashboard/index", [
            "title" => "Dashboard",
            "userBrand" => $this->userBrand,
            "stats" => $stats
        ]);
    }
}

