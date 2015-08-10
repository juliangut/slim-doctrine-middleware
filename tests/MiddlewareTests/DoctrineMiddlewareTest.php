<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Jgut\Slim\MiddlewareTests;

use Jgut\Slim\Middleware\DoctrineMiddleware;
use Slim\Helper\Set;

/**
 * @covers Jgut\Slim\Middleware\DoctrineMiddleware
 */
class DoctrineMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    protected $middleware;

    protected $doctrineConfig = [
        'connection' => [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ],
        'annotation_files'       => [],
        'annotation_namespaces'  => [],
        'annotation_autoloaders' => [],
        'cache_driver'           => [
            'type'  => '',
            'host'  => '',
            'port'  => '',
        ],
        'proxy_path'             => '',
    ];

    public function setUp()
    {
        $config = $this->doctrineConfig;

        $this->middleware = new DoctrineMiddleware();

        $app = $this->getMock('Slim\\Slim', array(), array(), '', false);
        $app->expects($this->any())->method('config')->will(
            $this->returnCallback(
                function () use ($config) {
                    $args = array(
                        'debug'    => true,
                        'doctrine' => $config
                    );
                    return $args[func_get_arg(0)];
                }
            )
        );

        $this->middleware->setApplication($app);
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::getOption
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::hasOption
     */
    public function testDefaults()
    {
        $this->assertNull($this->middleware->getOption('connection'));
        $this->assertNull($this->middleware->getOption('annotation_paths'));
        $this->assertNull($this->middleware->getOption('annotation_files'));
        $this->assertNull($this->middleware->getOption('annotation_namespaces'));
        $this->assertNull($this->middleware->getOption('annotation_autoloaders'));
        $this->assertNull($this->middleware->getOption('cache_driver'));
        $this->assertNull($this->middleware->getOption('proxy_path'));
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setOption
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::getOption
     */
    public function testMutatorsAccessors()
    {
        $expected = [
            sys_get_temp_dir() . '/tmp',
        ];

        $this->middleware->setOption('annotation_paths', $expected);

        $this->assertEquals($expected, $this->middleware->getOption('annotation_paths'));

        $this->middleware->setOption('proxy_path', $expected);

        $this->assertEquals($expected, $this->middleware->getOption('proxy_path'))
;    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @expectedException \BadMethodCallException
     */
    public function testSetupNoPath()
    {
        $this->middleware->setup();
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     */
    public function testSetup()
    {
        $container = new Set();

        $app = $this->middleware->getApplication();
        $app->container = $container;
        $this->middleware->setApplication($app);

        $this->middleware->setOption('annotation_paths', 'ficticious path');
        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $container->entityManager);
    }
}
