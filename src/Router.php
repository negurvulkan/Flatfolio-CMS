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
     * Returns an associative array with keys `template` and `data`.
     */
    public function match(string $path): array
    {
        $trimmedPath = trim($path, '/');

        if ($trimmedPath === '') {
            return [
                'template' => 'home',
                'data' => $this->contentRepository->loadHomePage(),
            ];
        }

        if ($trimmedPath === 'projects') {
            return [
                'template' => 'projects',
                'data' => $this->contentRepository->loadProjects(),
            ];
        }

        $page = $this->contentRepository->findPageBySlug($trimmedPath);
        if ($page !== null) {
            return [
                'template' => 'page',
                'data' => $page,
            ];
        }

        return [
            'template' => '404',
            'data' => ['title' => 'Seite nicht gefunden', 'content' => "Die angeforderte Seite wurde nicht gefunden."],
        ];
    }
}
