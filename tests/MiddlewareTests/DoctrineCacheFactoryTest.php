<?php
/**
 * Created by PhpStorm.
 * User: mcrauwel
 * Date: 10/08/15
 * Time: 16:19
 */

namespace MiddlewareTests;


use Jgut\Slim\Middleware\DoctrineCacheFactory;

class DoctrineCacheFactoryTest extends \PHPUnit_Framework_TestCase {
    /**
     * @covers Jgut\Slim\Middleware\DoctrineCacheFactory::configureCache
     */
    public function testCacheConfiguration()
    {
        $this->assertNull(DoctrineCacheFactory::configureCache(array('type' => '')));
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ApcCache', DoctrineCacheFactory::configureCache(array('type' => 'apc')));
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\XcacheCache', DoctrineCacheFactory::configureCache(array('type' => 'xcache')));
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ArrayCache', DoctrineCacheFactory::configureCache(array('type' => 'array')));
    }

    public function testMemcacheCacheConfiguration() {
        if(!extension_loaded('memcache')) {
            $this->setExpectedException('\BadMethodCallException', 'MemcacheCache configured but module \'memcache\' not loaded.');
        }
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\MemcacheCache', DoctrineCacheFactory::configureCache(array('type' => 'memcache', 'host' => '127.0.0.1', 'port' => 11211)));

    }

    public function testRedisCacheConfiguration() {
        if(!extension_loaded('redis')) {
            $this->setExpectedException('\BadMethodCallException', 'RedisCache configured but module \'redis\' not loaded.');
        }
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\RedisCache', DoctrineCacheFactory::configureCache(array('type' => 'redis', 'host' => '127.0.0.1', 'port' => 6379)));

    }
}