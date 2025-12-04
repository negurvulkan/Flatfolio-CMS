<?php

declare(strict_types=1);

namespace Flatfolio\Cache;

/**
 * Minimal cache interface for simple key/value caching.
 */
interface CacheInterface
{
    /**
     * Retrieve an item from cache or return null when missing.
     */
    public function get(string $key): mixed;

    /**
     * Store an item in cache for the given time-to-live in seconds.
     */
    public function set(string $key, mixed $value, int $ttl = 300): void;

    /**
     * Remove an item from cache.
     */
    public function delete(string $key): void;
}
