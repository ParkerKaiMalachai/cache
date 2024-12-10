<?php

declare(strict_types=1);

namespace src\classes;

use InvalidArgumentException;
use src\classes\exceptions\KeyNotFoundException;
use src\interfaces\CacheInterface;

final class Cache implements CacheInterface
{
    private static $instance = null;

    private static array $cache = [];

    public static function getInstance(): Cache
    {
        if (self::$instance === null) {
            self::$instance = new Cache();
        }

        return self::$instance;
    }

    public function get(string $key, string $default = null): mixed
    {
        if (!is_string($key) || empty($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        if (!array_key_exists($hashKey, self::$cache)) {

            return $default;
        }

        if (is_array(self::$cache[$hashKey])) {

            if (self::$cache[$hashKey]['expire'] > time()) {

                return self::$cache[$hashKey]['value'];
            }

            unset(self::$cache[$hashKey]);

            return $default;
        }

        return self::$cache[$hashKey];
    }

    public function set(string $key, string $value, int $ttl = null): void
    {
        if (empty($key) || !is_string($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);


        if (!isset($ttl)) {

            self::$cache[$hashKey] = $value;

            return;
        }

        self::$cache[$hashKey] = ['value' => $value, 'expire' => time() + $ttl];
    }

    public function delete(string $key): void
    {
        if (empty($key) || !is_string($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        if (!self::$cache[$hashKey]) {

            throw new KeyNotFoundException("Key $key not found in cache");
        }

        unset(self::$cache[$hashKey]);
    }

    public function clear(): void
    {
        self::$cache = [];
    }

    public function getMultiple(array $keys, string $default = null): mixed
    {
        if (count($keys) === 0 || !is_array($keys)) {

            throw new InvalidArgumentException('Empty list of keys');
        }

        foreach ($keys as $key) {

            $hashKey = md5($key);

            if (!array_key_exists($hashKey, self::$cache)) {

                $multipleValues[] = $default;

                continue;
            }

            if (is_array(self::$cache[$hashKey])) {

                if (self::$cache[$hashKey]['expire'] < time()) {

                    unset(self::$cache[$hashKey]);

                    $multipleValues[] = $default;

                    continue;
                }

                $multipleValues[] = self::$cache[$hashKey]['value'];
            }

            $multipleValues[] = self::$cache[$hashKey];
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

            if (isset($ttl)) {

                self::$cache[$hashKey] = ['value' => $value, 'expire' => time() + $ttl];

                continue;
            }

            self::$cache[$hashKey] = $value;
        }
    }

    public function deleteMultiple(array $keys): void
    {
        if (count($keys) === 0 || !is_array($keys)) {

            throw new InvalidArgumentException('Empty list of keys');
        }

        foreach ($keys as $key) {

            $hashKey = md5($key);

            if (!self::$cache[$hashKey]) {

                throw new KeyNotFoundException("Key $key not found in cache");
            }

            unset(self::$cache[$hashKey]);
        }
    }

    public function has(string $key): bool
    {
        if (empty($key) || !is_string($key)) {

            throw new InvalidArgumentException('Wrong type of a key');
        }

        $hashKey = md5($key);

        if (!array_key_exists($hashKey, self::$cache)) {

            return false;
        }

        return true;
    }
}
