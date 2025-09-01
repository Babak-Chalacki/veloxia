<?php

namespace Veloxia\Core;

class Router {
    protected array $routes = [];

    public function get(string $url, callable $action): void
    {
        $this->routes['GET'][$url] = $action;
    }

    public function post(string $url, callable $action): void
    {
        $this->routes['POST'][$url] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $url = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$url])) {
            $response = call_user_func($this->routes[$method][$url]);

            if (is_array($response)) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                echo $response;
            }
            return;
        }

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 404,
            'error'  => 'Not Found'
        ], JSON_UNESCAPED_UNICODE);
    }

}