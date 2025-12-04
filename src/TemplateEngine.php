<?php

declare(strict_types=1);

namespace FlatfolioCMS;

use RuntimeException;

class TemplateEngine
{
    private string $baseTemplatePath;

    private ?string $themeName;

    private ?string $themeBasePath;

    private ?CacheInterface $cache;

    public function __construct(
        string $baseTemplatePath,
        ?string $themeName = null,
        ?string $themeBasePath = null,
        ?CacheInterface $cache = null
    ) {
        $this->baseTemplatePath = rtrim($baseTemplatePath, '/');
        $this->themeName = $themeName !== '' ? $themeName : null;
        $this->themeBasePath = $themeBasePath !== null ? rtrim($themeBasePath, '/') : null;
        $this->cache = $cache;
    }

    /**
     * Render a view with optional layout support.
     */
    public function render(string $view, array $data = [], ?string $layout = 'layout'): string
    {
        $templateFile = $this->resolveTemplatePath($view);
        if ($templateFile === null) {
            throw new RuntimeException(sprintf('Template "%s" not found.', $view));
        }

        $content = $this->includeWithVariables($templateFile, $data);

        if ($layout === null) {
            return $content;
        }

        $layoutName = $layout === '' ? 'layout' : $layout;
        $layoutFile = $this->resolveTemplatePath($layoutName);
        if ($layoutFile === null) {
            throw new RuntimeException(sprintf('Layout "%s" not found.', $layoutName));
        }

        return $this->includeWithVariables($layoutFile, array_merge($data, ['content' => $content]));
    }

    /**
     * Render a view without wrapping it in a layout.
     */
    public function renderPartial(string $view, array $data = []): string
    {
        $templateFile = $this->resolveTemplatePath($view);
        if ($templateFile === null) {
            throw new RuntimeException(sprintf('Partial "%s" not found.', $view));
        }

        return $this->includeWithVariables($templateFile, $data);
    }

    /**
     * Resolve a view name to a physical template path.
     */
    public function resolveTemplatePath(string $view): ?string
    {
        $cacheKey = 'template:' . $view;
        if ($this->cache !== null) {
            $cached = $this->cache->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
        }

        $relative = str_replace('.', '/', $view) . '.php';
        $paths = [];

        if ($this->themeName !== null && $this->themeBasePath !== null) {
            $paths[] = $this->themeBasePath . '/' . $this->themeName . '/views/' . $relative;
        }

        $paths[] = $this->baseTemplatePath . '/' . $relative;

        foreach ($paths as $path) {
            if (is_file($path)) {
                if ($this->cache !== null) {
                    $this->cache->set($cacheKey, $path);
                }

                return $path;
            }
        }

        return null;
    }

    private function includeWithVariables(string $file, array $variables): string
    {
        extract($variables, EXTR_SKIP);

        ob_start();
        include $file;

        return (string) ob_get_clean();
    }
}
