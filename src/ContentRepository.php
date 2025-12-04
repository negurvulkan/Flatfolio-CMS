<?php

declare(strict_types=1);

namespace FlatfolioCMS;

use Flatfolio\Content\FrontmatterParser;
use Flatfolio\Content\MarkdownDocument;
use Flatfolio\Content\MarkdownRenderer;
use RuntimeException;

class ContentRepository
{
    private string $contentPath;

    private FrontmatterParser $frontmatterParser;

    private MarkdownRenderer $markdownRenderer;

    public function __construct(string $contentPath, FrontmatterParser $frontmatterParser, MarkdownRenderer $markdownRenderer)
    {
        $this->contentPath = rtrim($contentPath, '/');
        $this->frontmatterParser = $frontmatterParser;
        $this->markdownRenderer = $markdownRenderer;
    }

    /**
     * Load the home page. Falls back to a simple default if no file exists.
     */
    public function loadHomePage(): array
    {
        $page = $this->findPageBySlug('home');
        if ($page !== null) {
            return $page;
        }

        return [
            'title' => 'Willkommen bei Flatfolio CMS',
            'content' => 'Dies ist die Startseite. Lege eine Datei content/pages/home.md an, um sie zu ersetzen.',
        ];
    }

    /**
     * Load a single page document as MarkdownDocument.
     */
    public function loadPage(string $slug): ?MarkdownDocument
    {
        $pageFile = $this->contentPath . '/pages/' . $slug . '.md';
        if (!is_file($pageFile)) {
            return null;
        }

        $content = file_get_contents($pageFile);
        if ($content === false) {
            throw new RuntimeException(sprintf('Konnte die Seite %s nicht lesen.', $pageFile));
        }

        $parsed = $this->frontmatterParser->parse($content);
        $bodyHtml = $this->markdownRenderer->toHtml($parsed['body']);

        return new MarkdownDocument($parsed['meta'], $parsed['body'], $bodyHtml);
    }

    /**
     * Load a single project by slug.
     */
    public function loadProject(string $slug): ?MarkdownDocument
    {
        $projectFile = $this->contentPath . '/projects/' . $slug . '.md';
        return $this->loadDocument($projectFile);
    }

    /**
     * Load all projects from the content directory.
     */
    public function loadAllProjects(): array
    {
        $directory = $this->contentPath . '/projects';
        if (!is_dir($directory)) {
            return [];
        }

        $projects = [];
        foreach (glob($directory . '/*.md') ?: [] as $file) {
            $document = $this->loadDocument($file);
            if ($document !== null) {
                $projects[] = $document;
            }
        }

        return $projects;
    }

    /**
     * Load timeline entries.
     */
    public function loadTimeline(): array
    {
        $directory = $this->contentPath . '/timeline';
        if (!is_dir($directory)) {
            return [];
        }

        $entries = [];
        foreach (glob($directory . '/*.md') ?: [] as $file) {
            $document = $this->loadDocument($file);
            if ($document !== null) {
                $entries[] = $document;
            }
        }

        return $entries;
    }

    /**
     * Load all blog posts.
     */
    public function loadPosts(): array
    {
        $directory = $this->contentPath . '/posts';
        if (!is_dir($directory)) {
            return [];
        }

        $posts = [];
        foreach (glob($directory . '/*.md') ?: [] as $file) {
            $document = $this->loadDocument($file);
            if ($document !== null) {
                $posts[] = $document;
            }
        }

        return $posts;
    }

    /**
     * Load a list of projects. Returns an empty list placeholder for now.
     */
    public function loadProjects(): array
    {
        return [
            'title' => 'Projekte',
            'projects' => [],
        ];
    }

    /**
     * Find a page by its slug (e.g. `about`).
     */
    public function findPageBySlug(string $slug): ?array
    {
        $document = $this->loadPage($slug);
        if ($document === null) {
            return null;
        }

        return [
            'title' => $document->getTitle() ?? ucfirst($slug),
            'content' => $document->getBodyHtml(),
            'meta' => $document->getMeta(),
        ];
    }

    private function loadDocument(string $file): ?MarkdownDocument
    {
        if (!is_file($file)) {
            return null;
        }

        $content = file_get_contents($file);
        if ($content === false) {
            throw new RuntimeException(sprintf('Konnte die Seite %s nicht lesen.', $file));
        }

        $parsed = $this->frontmatterParser->parse($content);
        $bodyHtml = $this->markdownRenderer->toHtml($parsed['body']);

        return new MarkdownDocument($parsed['meta'], $parsed['body'], $bodyHtml);
    }
}
