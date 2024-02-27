<?php

declare(strict_types=1);

namespace League\Route\Cache;

use Psr\SimpleCache\CacheInterface;

class FileCache implements CacheInterface
{
    /**
     * @var string
     */
    protected $cacheFilePath;

    /**
     * @var integer
     */
    protected $ttl;

    public function __construct(string $cacheFilePath, int $ttl)
    {
        $this->cacheFilePath = $cacheFilePath;
        $this->ttl = $ttl;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return ($this->has($key)) ? file_get_contents($this->cacheFilePath) : $default;
    }

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        return (bool) file_put_contents($this->cacheFilePath, $value);
    }

    public function has(string $key): bool
    {
        return file_exists($this->cacheFilePath) && time() - filemtime($this->cacheFilePath) < $this->ttl;
    }

    public function delete(string $key): bool
    {
        return unlink($this->cacheFilePath);
    }

    public function clear(): bool
    {
        return $this->delete($this->cacheFilePath);
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return [];
    }

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        return false;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return false;
    }
}
