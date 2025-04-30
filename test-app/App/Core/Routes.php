<?php

namespace App\Core;

use Exception;

class Routes
{
    public function __construct(string $url)
    {
        $definedRoutes = $this->fetchRoutes();

        if (!array_key_exists($url, $definedRoutes)) {
            $this->return404();
        }

        $class = $definedRoutes[$url];

        if (! class_exists($class)) {
            $this->return404();
        }

        try {
            new $class()->index();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function fetchRoutes(): array
    {
        return require(__DIR__ . '/../../routes.php');
    }

    public function return404(): void
    {
        http_response_code(404);
        die('<h1>404 Not Found</h1>');
    }
}