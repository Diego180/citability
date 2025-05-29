<?php

// Citability Prototype - Entry Point (public/index.php)

declare(strict_types=1);

// Define project root for easier path management
define("ROOT_PATH", dirname(__DIR__));

// Autoloader (PSR-4)
spl_autoload_register(function ($class) {
    // Base directory for the namespace prefix
    $prefix = "App\\";
    $base_dir = ROOT_PATH . "/src/";

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace("\\", "/", $relative_class) . ".php";

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Load Configuration
require_once ROOT_PATH . "/config/config.php";

// Load Helper functions (if any)
// require_once ROOT_PATH . 
"/src/helpers.php";

// Start Session
if (session_status() == PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Routing
$router = new App\Core\Router();

// --- Define Routes ---

// Authentication Routes
$router->add("login", ["controller" => "Auth", "action" => "showLoginForm"], "GET");
$router->add("login", ["controller" => "Auth", "action" => "login"], "POST");
$router->add("register", ["controller" => "Auth", "action" => "showRegisterForm"], "GET");
$router->add("register", ["controller" => "Auth", "action" => "register"], "POST");
$router->add("logout", ["controller" => "Auth", "action" => "logout"], "GET");

// Dashboard Route (Placeholder - requires login)
$router->add("dashboard", ["controller" => "Dashboard", "action" => "index"], "GET");

// Brand Routes (Placeholder - requires login)
$router->add("brand", ["controller" => "Brand", "action" => "show"], "GET");
$router->add("brand/edit", ["controller" => "Brand", "action" => "edit"], "GET");
$router->add("brand/update", ["controller" => "Brand", "action" => "update"], "POST"); // Assuming POST for update
$router->add("brand/create", ["controller" => "Brand", "action" => "create"], "GET"); // Show create form
$router->add("brand/store", ["controller" => "Brand", "action" => "store"], "POST"); // Store new brand

// Query Routes (Placeholder - requires login)
$router->add("queries", ["controller" => "Query", "action" => "index"], "GET");
$router->add("queries/create", ["controller" => "Query", "action" => "create"], "GET");
$router->add("queries/store", ["controller" => "Query", "action" => "store"], "POST");

// Results Route (Placeholder - requires login)
$router->add("results", ["controller" => "Result", "action" => "index"], "GET");

// Profile Route (Placeholder - requires login)
$router->add("profile", ["controller" => "Profile", "action" => "show"], "GET");
$router->add("profile/edit", ["controller" => "Profile", "action" => "edit"], "GET");
$router->add("profile/update-password", ["controller" => "Profile", "action" => "updatePassword"], "POST");

// Default route (e.g., redirect to login or dashboard)
$router->add("", ["controller" => "Home", "action" => "index"], "GET");

// --- Dispatch the Router ---

// Get requested URL from query string (e.g., index.php?url=login)
$url = $_GET["url"] ?? ";
$method = $_SERVER["REQUEST_METHOD"];

$router->dispatch($url, $method);


