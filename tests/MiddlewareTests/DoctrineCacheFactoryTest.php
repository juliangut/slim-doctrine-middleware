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

        if(extension_loaded('memcache')) {
            $this->assertInstanceOf('Doctrine\\Common\\Cache\\MemcacheCache', DoctrineCacheFactory::configureCache(array('type' => 'memcache', 'host' => '127.0.0.1', 'port' => 11211)));
        }

        if(extension_loaded('redis')) {
            $this->assertInstanceOf('Doctrine\\Common\\Cache\\RedisCache', DoctrineCacheFactory::configureCache(array('type' => 'redis', 'host' => '127.0.0.1', 'port' => 6379)));
        }

        $this->assertInstanceOf('Doctrine\\Common\\Cache\\ArrayCache', DoctrineCacheFactory::configureCache(array('type' => 'array')));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testMemcacheCacheConfigurationException()
    {
        if (!extension_loaded('memcache')) {
            $this->assertInstanceOf('Doctrine\\Common\\Cache\\MemcacheCache', DoctrineCacheFactory::configureCache(array('type' => 'memcache', 'host' => '127.0.0.1', 'port' => 11211)));
        }
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testRedisCacheConfigurationException()
    {
        if(!extension_loaded('redis')) {
            $this->assertInstanceOf('Doctrine\\Common\\Cache\\RedisCache', DoctrineCacheFactory::configureCache(array('type' => 'redis', 'host' => '127.0.0.1', 'port' => 6379)));
        }
    }

}