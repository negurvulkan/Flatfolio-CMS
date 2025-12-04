<?php

declare(strict_types=1);

namespace FlatfolioCMS;

use DateInterval;

interface CacheInterface
{
    /**
     * Retrieve an item from cache or return default value.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Store an item in cache for an optional time-to-live.
     */
    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool;
}
