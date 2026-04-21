<?php

namespace App\Shared\Http;

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): Response
    {
        // Handle CORS preflight OPTIONS requests
        if ($method === 'OPTIONS') {
            foreach ($this->routes as $methodRoutes) {
                foreach ($methodRoutes as $pattern => $route) {
                    if ($this->matchRoute($pattern, $uri) !== null) {
                        return Response::json([], 200);
                    }
                }
            }
            return Response::json(['message' => 'Not Found'], 404);
        }

        $routesForMethod = $this->routes[$method] ?? [];

        foreach ($routesForMethod as $pattern => $route) {
            $params = $this->matchRoute($pattern, $uri);
            if ($params !== null) {
                [$controller, $action] = $route;
                if (!class_exists($controller) || !method_exists($controller, $action)) {
                    return Response::json(['message' => 'Route target not available'], 500);
                }

                $instance = new $controller();
                return $instance->$action(...$params);
            }
        }

        return Response::json(['message' => 'Not Found'], 404);
    }

    private function matchRoute(string $pattern, string $uri): ?array
    {
        if ($pattern === $uri) {
            return [];
        }

        $regex = preg_quote($pattern, '#');
        $regex = preg_replace_callback('/\\\{([a-zA-Z0-9_]+)\\\}/', static function ($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $regex);

        if (!preg_match('#^' . $regex . '$#', $uri, $matches)) {
            return null;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[] = $value;
            }
        }

        return $params;
    }
}
