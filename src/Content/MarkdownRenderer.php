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
        $normalized = str_replace(["\r\n", "\r"], "\n", trim($markdown));
        if ($normalized === '') {
            return '';
        }

        $lines = explode("\n", $normalized);
        $html = [];

        $inCodeBlock = false;
        $codeBuffer = [];
        $listType = null;
        $listItems = [];
        $paragraph = [];

        foreach ($lines as $line) {
            if (str_starts_with($line, '```')) {
                if ($inCodeBlock) {
                    $html[] = $this->renderCodeBlock($codeBuffer);
                    $codeBuffer = [];
                    $inCodeBlock = false;
                } else {
                    $this->flushParagraph($paragraph, $html);
                    $this->flushList($listItems, $listType, $html);
                    $inCodeBlock = true;
                }
                continue;
            }

            if ($inCodeBlock) {
                $codeBuffer[] = $line;
                continue;
            }

            if (trim($line) === '') {
                $this->flushParagraph($paragraph, $html);
                $this->flushList($listItems, $listType, $html);
                $listType = null;
                continue;
            }

            if (preg_match('/^(#{1,6})\s+(.*)$/', $line, $matches)) {
                $this->flushParagraph($paragraph, $html);
                $this->flushList($listItems, $listType, $html);
                $level = strlen($matches[1]);
                $text = $this->renderInline($matches[2]);
                $html[] = sprintf('<h%d>%s</h%d>', $level, $text, $level);
                continue;
            }

            if (preg_match('/^[-*]\s+(.*)$/', $line, $matches)) {
                if ($listType !== 'ul') {
                    $this->flushParagraph($paragraph, $html);
                    $this->flushList($listItems, $listType, $html);
                    $listType = 'ul';
                }
                $listItems[] = $this->renderInline($matches[1]);
                continue;
            }

            if (preg_match('/^\d+\.\s+(.*)$/', $line, $matches)) {
                if ($listType !== 'ol') {
                    $this->flushParagraph($paragraph, $html);
                    $this->flushList($listItems, $listType, $html);
                    $listType = 'ol';
                }
                $listItems[] = $this->renderInline($matches[1]);
                continue;
            }

            $paragraph[] = trim($line);
        }

        if ($inCodeBlock) {
            $html[] = $this->renderCodeBlock($codeBuffer);
        }

        $this->flushParagraph($paragraph, $html);
        $this->flushList($listItems, $listType, $html);

        return implode("\n", $html);
    }

    /**
     * Render a list of lines as a code block.
     */
    private function renderCodeBlock(array $lines): string
    {
        $escaped = htmlspecialchars(implode("\n", $lines), ENT_QUOTES, 'UTF-8');

        return '<pre><code>' . $escaped . '</code></pre>';
    }

    /**
     * Render inline Markdown features (links, emphasis, code).
     */
    private function renderInline(string $text): string
    {
        $escaped = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        $escaped = preg_replace('~`([^`]+)`~', '<code>$1</code>', $escaped) ?? $escaped;
        $escaped = preg_replace('~\*\*(.+?)\*\*~s', '<strong>$1</strong>', $escaped) ?? $escaped;
        $escaped = preg_replace('~\*(.+?)\*~s', '<em>$1</em>', $escaped) ?? $escaped;
        $escaped = preg_replace('~\[(.+?)\]\(([^)]+)\)~', '<a href="$2">$1</a>', $escaped) ?? $escaped;

        return $escaped;
    }

    /**
     * Flush the current paragraph buffer into the HTML output.
     */
    private function flushParagraph(array &$paragraph, array &$html): void
    {
        if ($paragraph === []) {
            return;
        }

        $text = $this->renderInline(implode(' ', $paragraph));
        $html[] = '<p>' . $text . '</p>';
        $paragraph = [];
    }

    /**
     * Flush an open list buffer into the HTML output.
     */
    private function flushList(array &$items, ?string $type, array &$html): void
    {
        if ($items === [] || $type === null) {
            $items = [];
            return;
        }

        $tag = $type === 'ol' ? 'ol' : 'ul';
        $html[] = sprintf('<%1$s><li>%2$s</li></%1$s>', $tag, implode('</li><li>', $items));
        $items = [];
    }
}
