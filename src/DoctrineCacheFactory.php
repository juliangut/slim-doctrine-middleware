<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Jgut\Slim\Middleware;

class DoctrineCacheFactory
{
    /**
     * @param array $cacheDriver
     *
     * @return \Doctrine\Common\Cache\CacheProvider
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
     * @throws BadMethodCallException
     *
     * @return \Doctrine\Common\Cache\MemcacheCache
     */
    private static function configureMemcacheCache($host = '127.0.0.1', $port = 11211)
    {
        if (!extension_loaded('memcache')) {
            throw new \BadMethodCallException('MemcacheCache configured but module \'memcache\' not loaded.');
        }

        $memcache = new \Memcache();
        $memcache->addserver($host, $port);

        $cache = new \Doctrine\Common\Cache\MemcacheCache();
        $cache->setMemcache($memcache);

        return $cache;
    }

    /**
     * @param string $host
     * @param int $port
     * @throws BadMethodCallException
     *
     * @return \Doctrine\Common\Cache\RedisCache
     */
    private static function configureRedisCache($host = '127.0.0.1', $port = 6379)
    {
        if (!extension_loaded('redis')) {
            throw new \BadMethodCallException('RedisCache configured but module \'redis\' not loaded.');
        }

        $redis = new \Redis();
        $redis->connect($host, $port);

        $cache = new \Doctrine\Common\Cache\RedisCache();
        $cache->setRedis($redis);

        return $cache;
    }
}
