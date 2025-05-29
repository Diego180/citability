<?php

namespace App\Core;

class Router {
    protected $routes = [];
    protected $params = [];

    /**
     * Add a route to the routing table
     * @param string $route The route URL
     * @param array $params Parameters (controller, action, etc.)
     * @param string $method HTTP method (GET, POST)
     */
    public function add(string $route, array $params = [], string $method = 'GET') {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace("/\//", "\\/", $route);

        // Convert variables e.g. {controller}
        $route = preg_replace("/\{([a-z]+)\}/", "(?P<\1>[a-z-]+)", $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace("/\{([a-z]+):([^\}]+)\}/", "(?P<\1>\2)", $route);

        // Add start and end delimiters, and case-insensitive flag
        $route = "/^" . $route . "\/?$/i"; // Added optional trailing slash

        $this->routes[$method][$route] = $params;
    }

    /**
     * Match the route to the routes in the routing table, setting the $params property if a route is found.
     * @param string $url The route URL
     * @param string $method The request method (GET, POST)
     * @return boolean true if a match found, false otherwise
     */
    public function match(string $url, string $method = 'GET'): bool
    {
        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Dispatch the route, creating the controller object and running the action method.
     * @param string $url The route URL
     * @param string $method The request method
     */
    public function dispatch(string $url, string $method = 'GET') {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url, $method)) {
            $controller = $this->params["controller"];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "App\\Controllers\\" . $controller . "Controller";

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params["action"];
                $action = $this->convertToCamelCase($action);

                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    // Action not found
                    $this->notFound("Action '{$action}' not found in controller '{$controller}'");
                }
            } else {
                // Controller class not found
                $this->notFound("Controller class '{$controller}' not found");
            }
        } else {
            // Route not found
            $this->notFound("No route matched for URL '{$url}' with method '{$method}'");
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps,
     * e.g. post-authors => PostAuthors
     * @param string $string The string to convert
     * @return string
     */
    protected function convertToStudlyCaps(string $string): string
    {
        return str_replace(" ", "", ucwords(str_replace("-", " ", $string)));
    }

    /**
     * Convert the string with hyphens to camelCase,
     * e.g. add-new => addNew
     * @param string $string The string to convert
     * @return string
     */
    protected function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any).
     * @param string $url The full URL
     * @return string The URL with the query string removed
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {
            $parts = explode("&", $url, 2);
            if (strpos($parts[0], "=") === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
            // Remove query string starting with ?
            $url = strtok($url, '?');
        }
        return $url;
    }

    /**
     * Get the currently matched parameters
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Handle Not Found errors (404)
     * @param string $message Optional message
     */
    protected function notFound(string $message = 'Resource not found') {
        http_response_code(404);
        // In a real app, you'd render a 404 view
        // View::render('404');
        die("404 Not Found: " . htmlspecialchars($message)); // Simple die for prototype
    }
}

