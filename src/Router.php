<?php

declare(strict_types=1);

namespace FlatfolioCMS;

class Router
{
    private ContentRepository $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * Match a request path to a template and data payload.
     * Returns a RouteResult with template, data and status code.
     */
    public function match(string $path): RouteResult
    {
        $normalizedPath = $this->normalizePath($path);
        $segments = $normalizedPath === '/' ? [] : explode('/', trim($normalizedPath, '/'));

        if ($segments === []) {
            return $this->resolveHome();
        }

        $first = $segments[0];
        if ($first === 'projects') {
            return $this->resolveProjects($segments, $normalizedPath);
        }

        if ($first === 'timeline') {
            return $this->resolveTimeline($segments, $normalizedPath);
        }

        if ($first === 'skills') {
            return $this->resolveSkills($segments, $normalizedPath);
        }

        if ($first === 'blog' || $first === 'posts') {
            return $this->resolvePosts($segments, $normalizedPath);
        }

        if (count($segments) === 1 && !$this->isReservedSlug($first)) {
            return $this->resolvePage($first, 'page');
        }

        return $this->notFound($normalizedPath);
    }

    private function normalizePath(string $path): string
    {
        $pathOnly = parse_url($path, PHP_URL_PATH) ?? '/';
        $pathOnly = $pathOnly === '' ? '/' : $pathOnly;

        $trimmed = rtrim($pathOnly, '/');
        if ($trimmed === '') {
            return '/';
        }

        return $trimmed;
    }

    private function normalizeTemplateName(?string $template, string $default): string
    {
        if ($template === null || $template === '') {
            return $default;
        }
    
        // Whitespace abschneiden
        $template = trim($template);
    
        // Führende/trailing Anführungszeichen entfernen
        $template = trim($template, "\"'");
    
        // Falls danach nichts übrig bleibt, auf Default zurück
        if ($template === '') {
            return $default;
        }
    
        return $template;
    }

    private function resolveHome(): RouteResult
    {
        return $this->resolvePage('home', 'home');
    }

    private function resolveProjects(array $segments, string $path): RouteResult
    {
        if (count($segments) === 1) {
            $projects = $this->contentRepository->loadProjects();
            return new RouteResult('projects', ['projects' => $projects]);
        }

        if (count($segments) === 2) {
            $project = $this->contentRepository->loadProject($segments[1]);
            if ($project !== null) {
                $template = $this->normalizeTemplateName($project->getTemplate(), 'project-single');
                return new RouteResult($template, ['project' => $project]);
            }
        }

        return $this->notFound($path);
    }

    private function resolveTimeline(array $segments, string $path): RouteResult
    {
        if (count($segments) === 1) {
            $entries = $this->contentRepository->loadTimelineEntries();
            return new RouteResult('timeline', ['entries' => $entries]);
        }

        return $this->notFound($path);
    }

    private function resolveSkills(array $segments, string $path): RouteResult
    {
        if (count($segments) === 1) {
            $skills = $this->contentRepository->loadSkills();
            return new RouteResult('skills', ['skills' => $skills]);
        }

        return $this->notFound($path);
    }

    private function resolvePosts(array $segments, string $path): RouteResult
    {
        if (count($segments) === 1) {
            $posts = $this->contentRepository->loadPosts();
            return new RouteResult('posts', ['posts' => $posts]);
        }

        if (count($segments) === 2) {
            $post = $this->contentRepository->loadPost($segments[1]);
            if ($post !== null) {
                $template = $this->normalizeTemplateName($post->getTemplate(), 'post');
                return new RouteResult($template, ['post' => $post]);
            }
        }

        return $this->notFound($path);
    }

    private function resolvePage(string $slug, string $defaultTemplate): RouteResult
    {
        $page = $this->contentRepository->loadPage($slug);
        if ($page === null) {
            return $this->notFound($slug === 'home' ? '/' : '/' . $slug);
        }

        $template = $this->normalizeTemplateName($page->getTemplate(), $defaultTemplate);

        return new RouteResult($template, ['page' => $page]);
    }

    private function notFound(string $path): RouteResult
    {
        return new RouteResult('404', ['path' => $path], 404);
    }

    private function isReservedSlug(string $slug): bool
    {
        return in_array($slug, ['projects', 'timeline', 'skills', 'blog', 'posts'], true);
    }
}
