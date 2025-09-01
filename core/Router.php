<?php

namespace Veloxia\Core;

class Router {
    protected array $routes = [];

    public function get(string $url, callable|array $action): void
    {
        $this->routes['GET'][$url] = $action;
    }

    public function post(string $url, callable|array $action): void
    {
        $this->routes['POST'][$url] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $url = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];

            if (is_callable($action)) {
                $response = call_user_func($action);
            }
            elseif (is_array($action) && count($action) === 2) {
                [$controller, $methodName] = $action;
                $instance = new $controller();
                $response = $instance->$methodName();
            }

            if (is_array($response)) {
                header('Content-Type: application/json');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                echo $response;
            }
            return;
        }

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 404,
            'error' => 'Not Found'
        ], JSON_UNESCAPED_UNICODE);
    }

}