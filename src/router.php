<?php

namespace CustomRouter;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $path)
    {
        $handler = $this->routes[$method][$path];

        if ($handler) {
            if (is_array($handler)) {
                [$class, $method] = $handler;
                if (class_exists($class) && method_exists($class, $method)) {
                    return (new $class)->$method();
                }
            } elseif (is_callable($handler)) {
                return call_user_func($handler);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
