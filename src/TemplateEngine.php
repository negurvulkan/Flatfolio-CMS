<?php

declare(strict_types=1);

namespace FlatfolioCMS;

use RuntimeException;

class TemplateEngine
{
    private string $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = rtrim($templatePath, '/');
    }

    /**
     * Render a template within the optional layout.
     */
    public function render(string $template, array $data = [], ?string $layout = 'layout'): string
    {
        $content = $this->renderTemplate($template, $data);

        if ($layout === null) {
            return $content;
        }

        $layoutFile = $this->templatePath . '/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            throw new RuntimeException(sprintf('Layout %s wurde nicht gefunden.', $layoutFile));
        }

        return $this->includeWithVariables($layoutFile, array_merge($data, ['content' => $content]));
    }

    private function renderTemplate(string $template, array $data): string
    {
        $templateFile = $this->templatePath . '/' . $template . '.php';
        if (!is_file($templateFile)) {
            throw new RuntimeException(sprintf('Template %s wurde nicht gefunden.', $templateFile));
        }

        return $this->includeWithVariables($templateFile, $data);
    }

    private function includeWithVariables(string $file, array $variables): string
    {
        extract($variables, EXTR_SKIP);

        ob_start();
        include $file;

        return (string) ob_get_clean();
    }
}
