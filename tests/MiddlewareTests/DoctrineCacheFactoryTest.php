<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace MiddlewareTests;

use Jgut\Slim\Middleware\DoctrineCacheFactory;

class DoctrineCacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Jgut\Slim\Middleware\DoctrineCacheFactory::configureCache
     */
    public function testCacheConfiguration()
    {
        $this->assertNull(DoctrineCacheFactory::configureCache(['type' => '']));
        $this->assertInstanceOf(
            'Doctrine\\Common\\Cache\\ApcCache',
            DoctrineCacheFactory::configureCache(['type' => 'apc'])
        );
        $this->assertInstanceOf(
            'Doctrine\\Common\\Cache\\XcacheCache',
            DoctrineCacheFactory::configureCache(['type' => 'xcache'])
        );
        $this->assertInstanceOf(
            'Doctrine\\Common\\Cache\\ArrayCache',
            DoctrineCacheFactory::configureCache(['type' => 'array'])
        );
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineCacheFactory::configureCache
     * @covers Jgut\Slim\Middleware\DoctrineCacheFactory::configureMemcacheCache
     */
    public function testMemcacheCacheConfiguration()
    {
        if (!extension_loaded('memcache')) {
            $this->setExpectedException(
                '\BadMethodCallException',
                'MemcacheCache configured but module \'memcache\' not loaded.'
            );
        }

        $this->assertInstanceOf(
            'Doctrine\\Common\\Cache\\MemcacheCache',
            DoctrineCacheFactory::configureCache(['type' => 'memcache', 'host' => '127.0.0.1', 'port' => 11211])
        );

    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineCacheFactory::configureCache
     * @covers Jgut\Slim\Middleware\DoctrineCacheFactory::configureRedisCache
     */
    public function testRedisCacheConfiguration()
    {
        if (!extension_loaded('redis')) {
            $this->setExpectedException(
                '\BadMethodCallException',
                'RedisCache configured but module \'redis\' not loaded.'
            );
        }

        $this->assertInstanceOf(
            'Doctrine\\Common\\Cache\\RedisCache',
            DoctrineCacheFactory::configureCache(['type' => 'redis', 'host' => '127.0.0.1', 'port' => 6379])
        );
    }
}
