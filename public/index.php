<?php

declare(strict_types=1);

use FlatfolioCMS\ContentRepository;
use FlatfolioCMS\Router;
use FlatfolioCMS\TemplateEngine;

$basePath = dirname(__DIR__);

spl_autoload_register(function (string $class) use ($basePath): void {
    $prefix = 'FlatfolioCMS\\';
    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $basePath . '/src/' . str_replace('\\', '/', $relative) . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    }
});

$router = new Router(new ContentRepository($basePath . '/content'));
$templateEngine = new TemplateEngine($basePath . '/templates');

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$route = $router->match($path);

if (($route['template'] ?? '') === '404') {
    http_response_code(404);
}

echo $templateEngine->render($route['template'], $route['data'] ?? []);
