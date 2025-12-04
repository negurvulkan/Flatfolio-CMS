<?php

declare(strict_types=1);

namespace Flatfolio\Content;

final class MarkdownDocument
{
    private readonly array $meta;

    private readonly string $bodyMarkdown;

    private readonly string $bodyHtml;

    public function __construct(array $meta, string $bodyMarkdown, string $bodyHtml)
    {
        $this->meta = $meta;
        $this->bodyMarkdown = $bodyMarkdown;
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * Get the parsed metadata from the Markdown frontmatter.
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Get the raw Markdown body.
     */
    public function getBodyMarkdown(): string
    {
        return $this->bodyMarkdown;
    }

    /**
     * Get the rendered HTML body.
     */
    public function getBodyHtml(): string
    {
        return $this->bodyHtml;
    }

    /**
     * Convenience getter for the document title.
     */
    public function getTitle(): ?string
    {
        return $this->meta['title'] ?? null;
    }

    /**
     * Convenience getter for the template name.
     */
    public function getTemplate(): ?string
    {
        return $this->meta['template'] ?? null;
    }

    /**
     * Convenience getter for the slug.
     */
    public function getSlug(): ?string
    {
        return $this->meta['slug'] ?? null;
    }
}
