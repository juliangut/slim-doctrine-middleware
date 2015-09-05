[![Latest Version](https://img.shields.io/packagist/vpre/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-doctrine-middleware)
[![License](https://img.shields.io/packagist/l/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://github.com/juliangut/slim-doctrine-middleware/blob/master/LICENSE)

[![Build status](https://img.shields.io/travis/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://travis-ci.org/juliangut/slim-doctrine-middleware)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware)
[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/slim-doctrine-middleware.svg?style=flat-square)](https://packagist.org/packages/juliangut/slim-doctrine-middleware)

# Juliangut Slim Framework Doctrine handler middleware

Doctrine handler middleware for Slim Framework.

## Installation

Best way to install is using [Composer](https://getcomposer.org/):

```
php composer.phar require juliangut/slim-doctrine-middleware
```

Then require_once the autoload file:

```php
require_once './vendor/autoload.php';
```

## Usage

Just add as any other middleware.

```php
use Slim\Slim;
use Jgut\Slim\Middleware\DoctrineMiddleware;

$app = new Slim();

...

$app->add(new DoctrineMiddleware());
```

### Configuration

There are two ways to configure Doctrine Middleware

First by using `doctrine` key in Slim application configuration

```php
// Minimun configuration
$config = [
    'doctrine' => [
        'connection' => [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ],
        'annotation_paths' => ['path_to_entities_files'],
    ],
];

$app = new Slim($config);
$app->add(new DoctrineMiddleware());
```

Second way is assigning options directly to Doctrine Middleware

```php
$app = new Slim();

$doctrineMiddleware = new DoctrineMiddleware();
$doctrineMiddleware->setOption(
    'connection',
    ['driver' => 'pdo_sqlite', 'memory' => true]
);
$doctrineMiddleware->setOption('annotation_paths', ['path_to_entities_files']);
$app->add($doctrineMiddleware);
```

### Available configurations

* `connection` array of PDO configurations
* `cache_driver` array with Doctrine cache configurations
    * `type` string representing cache type, `apc`, `xcache`, `memcache`, `redis` or `array`
    * `host` string representing caching daemon host, needed for `memcache` and `redis`, defaults to '127.0.0.1'
    * `port` string representing caching daemon port, optionally available for `memcache` (defaults to 11211) and `redis` (defaults to 6379)
* `proxy_path` path were Doctrine creates its proxy classes, defaults to /tmp
* `annotation_files` array of Doctrine annotations files
* `annotation_namespaces` array of Doctrine annotations namespaces
* `annotation_autoloaders` array of Doctrine annotations autoloader callables
* `annotation_paths` array of paths where to find annotated entity files
* `xml_paths` array of paths where to find XML entity mapping files
* `yaml_paths` array of paths where to find YAML entity mapping files
* `auto_generate_proxies` bool indicating whether Doctrine should auto-generate missing proxies (default: *true*)

#### Note:

`annotation_paths`, `xml_paths` or `yaml_paths` is needed by Doctrine to include a Metadata Driver

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/slim-doctrine-middleware/issues). Have a look at existing issues before

See file [CONTRIBUTING.md](https://github.com/juliangut/slim-doctrine-middleware/blob/master/CONTRIBUTING.md)

### Contributors

* [@fousheezy (John Foushee)](https://github.com/fousheezy)
* [@mcrauwel (Matthias Crauwels)](https://github.com/mcrauwel)
* [@mgersten (Micah Gersten)](https://github.com/mgersten)

## License

### Release under BSD-3-Clause License.

See file [LICENSE](https://github.com/juliangut/slim-doctrine-middleware/blob/master/LICENSE) included with the source code for a copy of the license terms

