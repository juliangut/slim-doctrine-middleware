<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Jgut\Slim\Middleware;

use Slim\Middleware;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\EntityManager;

/**
 * Doctrine handler middleware.
 */
class DoctrineMiddleware extends Middleware
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * Check option availavility
     *
     * @param string $option
     * @return bool
     */
    public function hasOption($option)
    {
        return isset($this->options[$option]);
    }

    /**
     * Get option value or default if none existent
     *
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function getOption($option, $default = null)
    {
        return $this->hasOption($option) ? $this->options[$option] : $default;
    }

    /**
     * Set option value
     *
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * @param array $options
     * @param array $defaults
     *
     * @return $this
     */
    public function setOptions(array $options, array $defaults = [])
    {
        $options = array_merge($defaults, $options);
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function call()
    {
        $this->setup();

        $this->next->call();
    }

    /**
     * Set up Doctrine Entity Manager Slim service
     */
    public function setup()
    {
        $app = $this->getApplication();

        $options = $app->config('doctrine');
        if (is_array($options)) {
            $this->setOptions($this->options, $options);
        }

        foreach ($this->getOption('annotation_files', []) as $file) {
            AnnotationRegistry::registerFile($file);
        }

        foreach ($this->getOption('annotation_namespaces', []) as $namespaceMapping) {
            AnnotationRegistry::registerAutoloadNamespace(reset($namespaceMapping), end($namespaceMapping));
        }

        foreach ($this->getOption('annotation_autoloaders', []) as $autoloader) {
            AnnotationRegistry::registerLoader($autoloader);
        }

        $proxyDir = null;
        if ($this->hasOption('proxy_path')) {
            $proxyDir = $this->getOption('proxy_path');
        }

        $cache = $this->configureCache($this->getOption('cache_driver'));

        $config = Setup::createConfiguration(!!$app->config('debug'), $proxyDir, $cache);
        $config->setNamingStrategy(new UnderscoreNamingStrategy(CASE_LOWER));

        $annotationPaths = $this->getOption('annotation_paths');
        if (empty($annotationPaths)) {
            throw new \BadMethodCallException('annotation_paths config should be defined');
        }

        if (!is_array($annotationPaths)) {
            $annotationPaths = array($annotationPaths);
        }
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($annotationPaths, false));

        $connection = $this->getOption('connection');

        $app->container->singleton(
            'entityManager',
            function () use ($connection, $config) {
                return EntityManager::create($connection, $config);
            }
        );
    }

    /**
     * @param array $cacheDriver
     * @return \Doctrine\Common\Cache\CacheProvider|null
     */
    public function configureCache($cacheDriver)
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
                $cache = $this->configureMemcacheCache($cacheDriver['host'], $cacheDriver['port']);
                break;
            case 'redis':
                $cache = $this->configureRedisCache($cacheDriver['host'], $cacheDriver['port']);
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
    private function configureMemcacheCache($host = '127.0.0.1', $port = 11211)
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
    private function configureRedisCache($host = '127.0.0.1', $port = 6379)
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
