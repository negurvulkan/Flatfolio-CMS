<?php

declare(strict_types=1);

namespace Flatfolio\Content;

use Symfony\Component\Yaml\Yaml;

class FrontmatterParser
{
    /**
     * Parse a raw Markdown file with optional YAML frontmatter.
     */
    public function parse(string $raw): array
    {
        if (!$this->startsWithFrontmatter($raw)) {
            return [
                'meta' => [],
                'body' => $raw,
            ];
        }

        $pattern = '/^---\s*\r?\n(.*?)\r?\n---\s*\r?\n?/s';
        if (!preg_match($pattern, $raw, $matches)) {
            return [
                'meta' => [],
                'body' => $raw,
            ];
        }

        $yamlRaw = $matches[1];
        $body = (string)substr($raw, strlen($matches[0]));

        return [
            'meta' => $this->parseYaml($yamlRaw),
            'body' => $body,
        ];
    }

    private function startsWithFrontmatter(string $raw): bool
    {
        return str_starts_with($raw, "---\n") || str_starts_with($raw, "---\r\n");
    }

    private function parseYaml(string $yamlRaw): array
    {
        if (class_exists(Yaml::class)) {
            try {
                $parsed = Yaml::parse($yamlRaw);
                return is_array($parsed) ? $parsed : [];
            } catch (\Throwable) {
                return [];
            }
        }

        $meta = [];
        $lines = preg_split('/\r?\n/', $yamlRaw) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode(':', $line, 2), 2, null);
            $key = trim((string)$key);
            if ($key === '') {
                continue;
            }

            $value = $value !== null ? trim($value) : null;
            $meta[$key] = $value;
        }

        return $meta;
    }
}
