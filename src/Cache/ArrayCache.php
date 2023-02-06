<?php

declare(strict_types=1);

namespace Epifrin\DisposableEmailChecker\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * PSR-16 Array cache implementation.
 * It uses if the developer doesn't assign his PSR-16 compatible cache object.
 *
 * @codeCoverageIgnore
 */
final class ArrayCache implements CacheInterface
{
    /** @var array<string, mixed> */
    private array $cache = [];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache[$key] ?? $default;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $this->cache[$key] = $value;
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->cache[$key]);
        return true;
    }

    public function clear(): bool
    {
        $this->cache = [];
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return [];
    }

    /**
     * @param iterable<mixed> $values
     * @param \DateInterval|int|null $ttl
     * @return bool
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return true;
    }

    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }
}
