<?php

declare(strict_types=1);

namespace Flatfolio\Cache;

use RuntimeException;

/**
 * Very small file-based cache using PHP serialization.
 */
class FileCache implements CacheInterface
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');

        if (!is_dir($this->directory) && !mkdir($this->directory, 0777, true) && !is_dir($this->directory)) {
            throw new RuntimeException(sprintf('Failed to create cache directory "%s".', $this->directory));
        }
    }

    public function get(string $key): mixed
    {
        $path = $this->filePath($key);
        if (!is_file($path)) {
            return null;
        }

        $raw = @file_get_contents($path);
        if ($raw === false) {
            return null;
        }

        $payload = @unserialize($raw);
        if (!is_array($payload) || !array_key_exists('expires', $payload)) {
            return null;
        }

        if ($payload['expires'] !== 0 && $payload['expires'] < time()) {
            $this->delete($key);

            return null;
        }

        return $payload['value'] ?? null;
    }

    public function set(string $key, mixed $value, int $ttl = 300): void
    {
        $path = $this->filePath($key);
        $expires = $ttl > 0 ? time() + $ttl : 0;
        $payload = serialize([
            'expires' => $expires,
            'value' => $value,
        ]);

        file_put_contents($path, $payload, LOCK_EX);
    }

    public function delete(string $key): void
    {
        $path = $this->filePath($key);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function filePath(string $key): string
    {
        return $this->directory . '/' . md5($key) . '.cache';
    }
}
