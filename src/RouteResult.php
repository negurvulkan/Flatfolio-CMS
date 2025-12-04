<?php

declare(strict_types=1);

namespace FlatfolioCMS;

final class RouteResult
{
    private string $template;

    private array $data;

    private int $statusCode;

    public function __construct(string $template, array $data = [], int $statusCode = 200)
    {
        $this->template = $template;
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
