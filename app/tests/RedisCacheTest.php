<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use src\classes\RedisCache;

final class RedisCacheTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $mockRedis = Mockery::mock(Redis::class);

        $mockRedis->shouldReceive('connect')->with('redis', 6379);

        $mockRedis->shouldReceive('set')->andReturn(true);

        $mockRedis->shouldReceive('get')->andReturn('value1');

        $redisManager = RedisCache::getInstance();

        $redisManager->setHelper($mockRedis);

        $redisManager->set('key1', 'value1');

        $this->assertEquals('value1', $redisManager->get('key1'));
    }

    public function testDelete(): void
    {
        $mockRedis = Mockery::mock(Redis::class);

        $mockRedis->shouldReceive('connect')->with('redis', 6379);

        $mockRedis->shouldReceive('set')->andReturn(true);

        $mockRedis->shouldReceive('get')->andReturn(false);

        $mockRedis->shouldReceive('del')->andReturn(1);

        $redisManager = RedisCache::getInstance();

        $redisManager->setHelper($mockRedis);

        $redisManager->set('key1', 'value1');

        $redisManager->delete('key1');

        $this->assertEquals(null, $redisManager->get('key1'));
    }

    public function testClear(): void
    {
        $mockRedis = Mockery::mock(Redis::class);

        $mockRedis->shouldReceive('connect')->with('redis', 6379);

        $mockRedis->shouldReceive('set')->andReturn(true);

        $mockRedis->shouldReceive('flushDB')->andReturn(true);

        $mockRedis->shouldReceive('get')->andReturn(false);

        $redisManager = RedisCache::getInstance();

        $redisManager->setHelper($mockRedis);

        $redisManager->set('key1', 'value1');

        $redisManager->clear();

        $this->assertEquals(null, $redisManager->get('key1'));
    }

    public function testSetAndGetMultiple(): void
    {
        $mockRedis = Mockery::mock(Redis::class);

        $mockRedis->shouldReceive('connect')->with('redis', 6379);

        $mockRedis->shouldReceive('set')->andReturn(true);

        $mockRedis->shouldReceive('get')->andReturn('value1', 'value2');

        $redisManager = RedisCache::getInstance();

        $redisManager->setHelper($mockRedis);

        $redisManager->setMultiple(['key1' => 'value1', 'key2' => 'value2']);

        $this->assertEquals(['value1', 'value2'], $redisManager->getMultiple(['key1', 'key2']));
    }

    public function testDeleteMultiple(): void
    {
        $mockRedis = Mockery::mock(Redis::class);

        $mockRedis->shouldReceive('connect')->with('redis', 6379);

        $mockRedis->shouldReceive('set')->andReturn(true);

        $mockRedis->shouldReceive('del')->andReturn(1);

        $mockRedis->shouldReceive('get')->andReturn(false);

        $redisManager = RedisCache::getInstance();

        $redisManager->setHelper($mockRedis);

        $redisManager->setMultiple(['key1' => 'value1', 'key2' => 'value2']);

        $redisManager->deleteMultiple(['key1', 'key2']);

        $this->assertEquals([null, null], $redisManager->getMultiple(['key1', 'key2']));
    }

    public function testHas()
    {
        $mockRedis = Mockery::mock(Redis::class);

        $mockRedis->shouldReceive('connect')->with('redis', 6379);

        $mockRedis->shouldReceive('set')->andReturn(true);

        $mockRedis->shouldReceive('get')->andReturn('value1');

        $redisManager = RedisCache::getInstance();

        $redisManager->setHelper($mockRedis);

        $redisManager->set('key1', 'value1');

        $this->assertEquals('value1', $redisManager->get('key1'));
    }
}
