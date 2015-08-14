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
    protected $container;

    protected $middleware;

    protected $doctrineConfig = [
        'connection' => [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ],
        'proxy_path'             => '',
        'cache_driver'           => [
            'type'  => '',
            'host'  => '',
            'port'  => '',
        ],
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

        $this->container = new Set();
        $app->container = $this->container;

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
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @expectedException \RuntimeException
     */
    public function testSetupNoMetadataDriver()
    {
        $this->middleware->setup();
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setupAnnotationMetadata
     */
    public function testAnnotationFilesSetup()
    {
        $this->middleware->setOption('annotation_files', dirname(__DIR__) . '/files/fakeAnnotationFile.php');
        $this->middleware->setOption('annotation_paths', 'ficticious path');

        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->container->entityManager);
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setupAnnotationMetadata
     */
    public function testAnnotationNamespacesSetup()
    {
        $this->middleware->setOption('annotation_namespaces', 'Fake\\Namespace');
        $this->middleware->setOption('annotation_paths', 'ficticious path');

        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->container->entityManager);
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setupAnnotationMetadata
     */
    public function testAnnotationAutoloadersSetup()
    {
        $this->middleware->setOption(
            'annotation_autoloaders',
            function () {
            }
        );
        $this->middleware->setOption('annotation_paths', 'ficticious path');

        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->container->entityManager);
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setupMetadataDriver
     */
    public function testAnnotationPathsSetup()
    {
        $this->middleware->setOption('annotation_paths', 'ficticious path');

        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->container->entityManager);
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setupMetadataDriver
     */
    public function testXmlPathsSetup()
    {
        $this->middleware->setOption('xml_paths', dirname(__DIR__) . '/files/fakeAnnotationFile.php');

        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->container->entityManager);
    }

    /**
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setup
     * @covers Jgut\Slim\Middleware\DoctrineMiddleware::setupMetadataDriver
     */
    public function testYamlPathsSetup()
    {
        $this->middleware->setOption('yaml_paths', dirname(__DIR__) . '/files/fakeAnnotationFile.php');

        $this->middleware->setup();

        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->container->entityManager);
    }
}
