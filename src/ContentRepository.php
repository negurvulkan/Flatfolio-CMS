<?php

declare(strict_types=1);

namespace FlatfolioCMS;

use RuntimeException;

class ContentRepository
{
    private string $contentPath;

    public function __construct(string $contentPath)
    {
        $this->contentPath = rtrim($contentPath, '/');
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
        $pageFile = $this->contentPath . '/pages/' . $slug . '.md';
        if (!is_file($pageFile)) {
            return null;
        }

        $content = file_get_contents($pageFile);
        if ($content === false) {
            throw new RuntimeException(sprintf('Konnte die Seite %s nicht lesen.', $pageFile));
        }

        return [
            'title' => ucfirst($slug),
            'content' => nl2br($content),
        ];
    }
}
