<?php
/**
 * Created by PhpStorm.
 * User: mcrauwel
 * Date: 10/08/15
 * Time: 16:06
 */

namespace Jgut\Slim\Middleware;

class DoctrineCacheFactory
{
    /**
     * @param array $cacheDriver
     * @return \Doctrine\Common\Cache\CacheProvider|null
     */
    public static function configureCache($cacheDriver)
    {
        $cache = null;
        switch (strtolower($cacheDriver['type'])) {
            case 'apc':
                $cache = new \Doctrine\Common\Cache\ApcCache();
                break;
            case 'xcache':
                $cache = new \Doctrine\Common\Cache\XcacheCache();
                break;
            case 'memcache':
                $cache = self::configureMemcacheCache($cacheDriver['host'], $cacheDriver['port']);
                break;
            case 'redis':
                $cache = self::configureRedisCache($cacheDriver['host'], $cacheDriver['port']);
                break;
            case 'array':
                $cache = new \Doctrine\Common\Cache\ArrayCache();
                break;
        }

        return $cache;
    }

    /**
     * @param string $host
     * @param int $port
     * @return \Doctrine\Common\Cache\MemcacheCache
     */
    private static function configureMemcacheCache($host = '127.0.0.1', $port = 11211)
    {
        if (extension_loaded('memcache')) {
            $memcache = new \Memcache();
            $memcache->addserver($host, $port);

            $cache = new \Doctrine\Common\Cache\MemcacheCache();
            $cache->setMemcache($memcache);
            return $cache;
        } else {
            throw new \BadMethodCallException('MemcacheCache configured but module \'memcache\' not loaded.');
        }
    }

    /**
     * @param string $host
     * @param int $port
     * @return \Doctrine\Common\Cache\RedisCache
     */
    private static function configureRedisCache($host = '127.0.0.1', $port = 6379)
    {
        if (extension_loaded('redis')) {
            $redis = new \Redis();
            $redis->connect($host, $port);

            $cache = new \Doctrine\Common\Cache\RedisCache();
            $cache->setRedis($redis);
            return $cache;
        } else {
            throw new \BadMethodCallException('RedisCache configured but module \'redis\' not loaded.');
        }
    }
}
