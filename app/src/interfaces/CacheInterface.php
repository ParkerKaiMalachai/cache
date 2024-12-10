<?php

declare(strict_types=1);

namespace src\interfaces;

interface CacheInterface
{
    public function get(string $key, string $default = null);

    public function set(string $key, string $value, int $ttl = null);

    public function delete(string $key);

    public function clear();

    public function getMultiple(array $keys, string $default = null);

    public function setMultiple(array $values, int $ttl = null);

    public function deleteMultiple(array $keys);

    public function has(string $key);
}
