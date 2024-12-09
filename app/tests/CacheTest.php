<?php

declare(strict_types=1);

require 'app/autoload.php';
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use src\classes\Cache;

final class CacheTest extends TestCase
{
    public function testGetAndSet(): void
    {
        $cache = Cache::getInstance();

        $cache->set('key', 'value');

        $this->assertEquals('value', $cache->get('key'));
    }

    public function testDelete(): void
    {
        $cache = Cache::getInstance();

        $cache->set('key', 'value');

        $cache->delete('key');

        $this->assertEquals(false, $cache->has('key'));
    }

    public function testClear(): void
    {
        $cache = Cache::getInstance();

        $cache->set('key1', 'value1');

        $cache->set('key2', 'value2');

        $cache->set('key3', 'value3');

        $cache->clear();

        $this->assertEquals([null, null, null], $cache->getMultiple(['key1', 'key2', 'key3']));
    }

    public function testGetAndSetMultiple(): void
    {
        $cache = Cache::getInstance();

        $cache->setMultiple(['key1' => 'value1', 'key2' => 'value2']);

        $this->assertEquals(['value1', 'value2'], $cache->getMultiple(['key1', 'key2']));
    }

    public function testDeleteMultiple(): void
    {
        $cache = Cache::getInstance();

        $cache->setMultiple(['key1' => 'value1', 'key2' => 'value2']);

        $cache->deleteMultiple(['key1', 'key2']);

        $this->assertEquals([null, null], $cache->getMultiple(['key1', 'key2']));
    }

    public function testHas(): void
    {
        $cache = Cache::getInstance();

        $cache->set('key1', 'value1');

        $this->assertEquals(true, $cache->has('key1'));
    }
}
