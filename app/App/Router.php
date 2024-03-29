<?php

namespace RidwanHidayat\Absen\API\App;

class Router
{
    private static array $routes = [];

    public static function add(
        string $method,
        string $path,
        string $controller,
        string $function,
        array $middlewares = []
    ): void {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middleware' => $middlewares
        ];
    }

    public static function run(): void
    {
        $path = '/';
        if (isset($_SERVER['REQUEST_URI'])) {
            $path = strtok($_SERVER['REQUEST_URI'], '?');
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'OPTIONS') {
            die;
        }

        foreach (self::$routes as $route) {

            $pattern = '#^' . $route['path'] . '$#';

            if (preg_match($pattern, $path, $variables) && $method == $route['method']) {

                // call middleware
                foreach ($route['middleware'] as $middleware) {
                    $instance = new $middleware;
                    $instance->before();
                }

                // call controller
                $controller = new $route['controller'];
                $function = $route['function'];

                array_shift($variables);
                call_user_func_array([$controller, $function], $variables);

                return;
            }
        }

        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'message' => 'Invalid address'
        ]);
    }
}
