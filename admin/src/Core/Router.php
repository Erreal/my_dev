<?php
/**
 * Lightweight router with support for method-based routing and URL parameters.
 */

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $path, string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register a POST route.
     */
    public function post(string $path, string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Register a route for multiple methods.
     */
    public function match(array $methods, string $path, string $handler): void
    {
        foreach ($methods as $method) {
            $this->addRoute(strtoupper($method), $path, $handler);
        }
    }

    /**
     * Add a route to the collection.
     */
    private function addRoute(string $method, string $path, string $handler): void
    {
        // Convert route parameters like {id} to named regex groups
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * Dispatch the current request to the appropriate handler.
     */
    public function dispatch(Request $request): void
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        // Remove /admin prefix for route matching
        $path = preg_replace('#^/admin#', '', $path) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);

                // Parse handler: Controller@action
                [$controllerClass, $action] = explode('@', $route['handler']);
                $controllerClass = 'App\\Controllers\\' . $controllerClass;

                if (!class_exists($controllerClass)) {
                    throw new \RuntimeException("Controller not found: {$controllerClass}");
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $action)) {
                    throw new \RuntimeException("Action not found: {$controllerClass}::{$action}");
                }

                // Call the controller action with params
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // No route matched — 404
        http_response_code(404);
        echo '404 Not Found';
    }
}