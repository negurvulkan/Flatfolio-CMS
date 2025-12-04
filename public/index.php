<?php

declare(strict_types=1);

use Flatfolio\Cache\FileCache;
use Flatfolio\Content\FrontmatterParser;
use Flatfolio\Content\MarkdownRenderer;
use FlatfolioCMS\ContentRepository;
use FlatfolioCMS\Router;
use FlatfolioCMS\TemplateEngine;

$basePath = dirname(__DIR__);

$prefixes = [
    'Flatfolio\\Cache\\' => $basePath . '/src/Cache/',
    'FlatfolioCMS\\' => $basePath . '/src/',
    'Flatfolio\\Content\\' => $basePath . '/src/Content/',
];

spl_autoload_register(function (string $class) use ($prefixes): void {
    foreach ($prefixes as $prefix => $directory) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }

        $relative = substr($class, strlen($prefix));
        $file = $directory . str_replace('\\', '/', $relative) . '.php';

        if (is_file($file)) {
            require_once $file;
        }
    }
});

function loadConfigTheme(string $configPath): ?string
{
    if (!is_file($configPath)) {
        return null;
    }

    $raw = file_get_contents($configPath);
    if ($raw === false) {
        return null;
    }

    if (preg_match('/^theme:\s*["\']?(?P<theme>[A-Za-z0-9_-]+)/mi', $raw, $matches)) {
        return $matches['theme'] !== '' ? $matches['theme'] : null;
    }

    return null;
}

function loadCacheEnabled(string $configPath): bool
{
    if (!is_file($configPath)) {
        return false;
    }

    $raw = file_get_contents($configPath);
    if ($raw === false) {
        return false;
    }

    if (preg_match('/cache:\s*\n\s*enabled:\s*(true|false)/mi', $raw, $matches)) {
        return strtolower($matches[1]) === 'true';
    }

    return false;
}

$configTheme = loadConfigTheme($basePath . '/config/config.yml');
$cacheEnabled = loadCacheEnabled($basePath . '/config/config.yml');

$frontmatterParser = new FrontmatterParser();
$markdownRenderer = new MarkdownRenderer();

$router = new Router(new ContentRepository($basePath . '/content', $frontmatterParser, $markdownRenderer));
$cache = $cacheEnabled ? new FileCache($basePath . '/cache/views') : null;
$templateEngine = new TemplateEngine($basePath . '/templates', $configTheme, $basePath . '/themes', $cache, $cacheEnabled);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$route = $router->match($path);

http_response_code($route->getStatusCode());

echo $templateEngine->render($route->getTemplate(), $route->getData());
