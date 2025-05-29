<?php

namespace App\Core;

class View {
    /**
     * Render a view file
     *
     * @param string $view The view file (e.g., 'auth/login')
     * @param array $args Associative array of data to extract into variables for the view
     *
     * @return void
     */
    public static function render(string $view, array $args = []): void
    {
        extract($args, EXTR_SKIP);

        $file = ROOT_PATH . "/src/Views/{$view}.php"; // Relative to Views directory

        if (is_readable($file)) {
            require $file;
        } else {
            // Handle view not found
            throw new \Exception("View file '{$file}' not found");
        }
    }

    /**
     * Render a view template using Twig or another template engine (optional)
     * For this prototype, we'll stick to plain PHP templates.
     */
    // public static function renderTemplate(string $template, array $args = []): void { ... }
}

