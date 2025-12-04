<?php

declare(strict_types=1);

namespace FlatfolioCMS;

use Flatfolio\Content\FrontmatterParser;
use Flatfolio\Content\MarkdownDocument;
use Flatfolio\Content\MarkdownRenderer;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

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

        return $this->loadDocument($pageFile, $slug);
    }

    /**
     * Load a single project by slug.
     */
    public function loadProject(string $slug): ?MarkdownDocument
    {
        $projectFile = $this->contentPath . '/projects/' . $slug . '.md';

        return $this->loadDocument($projectFile, $slug);
    }

    /**
     * Load all projects from the content directory.
     */
    public function loadAllProjects(): array
    {
        return $this->loadProjects();
    }

    /**
     * Load timeline entries.
     */
    public function loadTimeline(): array
    {
        return $this->loadTimelineEntries();
    }

    /**
     * Load all blog posts.
     */
    public function loadPosts(): array
    {
        $posts = $this->loadMarkdownDocumentsFromDirectory('posts');

        usort($posts, function (MarkdownDocument $a, MarkdownDocument $b): int {
            $dateA = strtotime((string)($a->getMeta()['date'] ?? '')) ?: 0;
            $dateB = strtotime((string)($b->getMeta()['date'] ?? '')) ?: 0;

            return $dateB <=> $dateA;
        });

        return $posts;
    }

    /**
     * Load a single blog post by slug.
     */
    public function loadPost(string $slug): ?MarkdownDocument
    {
        $postFile = $this->contentPath . '/posts/' . $slug . '.md';

        return $this->loadDocument($postFile, $slug);
    }

    /**
     * Load all projects sorted by sort value and date.
     */
    public function loadProjects(): array
    {
        $projects = $this->loadMarkdownDocumentsFromDirectory('projects');

        usort($projects, function (MarkdownDocument $a, MarkdownDocument $b): int {
            $sortA = (int)($a->getMeta()['sort'] ?? 0);
            $sortB = (int)($b->getMeta()['sort'] ?? 0);
            if ($sortA !== $sortB) {
                return $sortB <=> $sortA;
            }

            $dateA = strtotime((string)($a->getMeta()['date'] ?? '')) ?: 0;
            $dateB = strtotime((string)($b->getMeta()['date'] ?? '')) ?: 0;

            return $dateB <=> $dateA;
        });

        return $projects;
    }

    /**
     * Load timeline entries sorted by sort and from date.
     */
    public function loadTimelineEntries(): array
    {
        $entries = $this->loadMarkdownDocumentsFromDirectory('timeline');

        usort($entries, function (MarkdownDocument $a, MarkdownDocument $b): int {
            $sortA = (int)($a->getMeta()['sort'] ?? 0);
            $sortB = (int)($b->getMeta()['sort'] ?? 0);
            if ($sortA !== $sortB) {
                return $sortB <=> $sortA;
            }

            $fromA = strtotime((string)($a->getMeta()['from'] ?? '')) ?: 0;
            $fromB = strtotime((string)($b->getMeta()['from'] ?? '')) ?: 0;

            return $fromB <=> $fromA;
        });

        return $entries;
    }

    /**
     * Load the skills grouped by category.
     */
    public function loadSkills(): array
    {
        $file = $this->contentPath . '/skills.yml';
        $defaults = ['tech' => [], 'ux' => [], 'tools' => [], 'soft' => []];

        if (!is_file($file)) {
            return $defaults;
        }

        $raw = file_get_contents($file);
        if ($raw === false) {
            throw new RuntimeException(sprintf('Konnte die Datei %s nicht lesen.', $file));
        }

        $parsed = $this->parseSkillsYaml($raw);

        return array_merge($defaults, array_intersect_key($parsed, $defaults));
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

    private function loadDocument(string $file, ?string $slug = null): ?MarkdownDocument
    {
        if (!is_file($file)) {
            return null;
        }

        $content = file_get_contents($file);
        if ($content === false) {
            throw new RuntimeException(sprintf('Konnte die Seite %s nicht lesen.', $file));
        }

        $parsed = $this->frontmatterParser->parse($content);
        if ($slug !== null && !isset($parsed['meta']['slug'])) {
            $parsed['meta']['slug'] = $slug;
        }

        $bodyHtml = $this->markdownRenderer->toHtml($parsed['body']);

        return new MarkdownDocument($parsed['meta'], $parsed['body'], $bodyHtml);
    }

    private function loadMarkdownDocumentsFromDirectory(string $relativePath): array
    {
        $directory = $this->contentPath . '/' . trim($relativePath, '/');
        if (!is_dir($directory)) {
            return [];
        }

        $documents = [];
        foreach (glob($directory . '/*.md') ?: [] as $file) {
            $slug = basename($file, '.md');
            $document = $this->loadDocument($file, $slug);
            if ($document !== null) {
                $documents[] = $document;
            }
        }

        return $documents;
    }

    private function parseSkillsYaml(string $raw): array
    {
        if (class_exists(Yaml::class)) {
            try {
                $parsed = Yaml::parse($raw);
                return is_array($parsed) ? $parsed : [];
            } catch (\Throwable) {
                return [];
            }
        }

        $result = [];
        $current = null;
        foreach (preg_split('/\r?\n/', $raw) ?: [] as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            if (str_ends_with($trimmed, ':')) {
                $current = rtrim($trimmed, ':');
                $result[$current] = $result[$current] ?? [];
                continue;
            }

            if ($current !== null && str_starts_with($trimmed, '- ')) {
                $result[$current][] = ltrim(substr($trimmed, 1));
            }
        }

        return $result;
    }
}
