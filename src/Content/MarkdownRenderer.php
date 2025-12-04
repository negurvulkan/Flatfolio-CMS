<?php

declare(strict_types=1);

namespace Flatfolio\Content;

class MarkdownRenderer
{
    /**
     * Convert Markdown into HTML.
     */
    public function toHtml(string $markdown): string
    {
        if (class_exists('Parsedown')) {
            $parser = new \Parsedown();
            return $parser->text($markdown);
        }

        // TODO: Replace with a proper Markdown library if available.
        $chunks = preg_split('/\n{2,}/', trim($markdown)) ?: [];
        $htmlChunks = array_map(static function (string $chunk): string {
            return '<p>' . nl2br(htmlspecialchars(trim($chunk), ENT_QUOTES, 'UTF-8')) . '</p>';
        }, $chunks);

        return implode("\n", $htmlChunks);
    }
}
