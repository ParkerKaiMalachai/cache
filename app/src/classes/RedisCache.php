<?php

declare(strict_types=1);

namespace src\classes;

use InvalidArgumentException;
use Redis;
use src\interfaces\CacheInterface;

final class RedisCache implements CacheInterface
{
    private static $instance = null;

    private Redis $redis;

    public static function getInstance(): RedisCache
    {
        if (self::$instance === null) {
            self::$instance = new RedisCache();
        }

        return self::$instance;
    }

    public function setHelper($redis): void
    {
        $this->redis = $redis;
    }

    public function get(string $key, string $default = null): mixed
    {
        if (!is_string($key) || empty($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        $result = $this->redis->get($hashKey);

        if (!$result) {
            return $default;
        }

        return $result;
    }

    public function set(string $key, string $value, int $ttl = null): void
    {
        if (!is_string($key) || empty($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        if (!isset($ttl)) {

            $this->redis->set($hashKey, serialize($value));

            return;
        }

        $this->redis->set($hashKey, $value, ['EX' => $ttl]);
    }

    public function delete(string $key): void
    {
        if (!is_string($key) || empty($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        $this->redis->del($hashKey);
    }

    public function clear(): void
    {
        $this->redis->flushDB();
    }

    public function getMultiple(array $keys, string $default = null): array
    {
        if (count($keys) === 0 || !is_array($keys)) {

            throw new InvalidArgumentException('Empty list of keys');
        }

        foreach ($keys as $key) {

            $hashKey = md5($key);

            $result = $this->redis->get($hashKey);

            if (!$result) {

                $multipleValues[] = $default;

                continue;
            }

            $multipleValues[] = $result;
        }

        return $multipleValues;
    }

    public function setMultiple(array $values, int $ttl = null): void
    {
        if (count($values) === 0 || !is_array($values)) {

            throw new InvalidArgumentException('Empty list of values');
        }

        foreach ($values as $key => $value) {

            $hashKey = md5($key);

            if (!isset($ttl)) {

                $this->redis->set($hashKey, $value);

                continue;
            }

            $this->redis->set($hashKey, $value, ['EX' => $ttl]);
        }
    }

    public function deleteMultiple(array $keys): void
    {
        if (count($keys) === 0 || !is_array($keys)) {

            throw new InvalidArgumentException('Empty list of keys');
        }

        foreach ($keys as $key) {

            $hashKey = md5($key);

            $this->redis->del($hashKey);
        }
    }

    public function has(string $key): bool
    {
        if (!is_string($key) || empty($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        $result = $this->redis->get($hashKey);

        if (!$result) {
            return false;
        }

        return true;
    }
}
