[![Build Status](https://travis-ci.org/juliangut/slim-doctrine-middleware.svg?branch=master)](https://travis-ci.org/juliangut/slim-doctrine-middleware)
[![Code Climate](https://codeclimate.com/github/juliangut/slim-doctrine-middleware/badges/gpa.svg)](https://codeclimate.com/github/juliangut/slim-doctrine-middleware)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/juliangut/slim-doctrine-middleware/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/juliangut/slim-doctrine-middleware/v/stable.svg)](https://packagist.org/packages/juliangut/slim-doctrine-middleware)
[![Total Downloads](https://poser.pugx.org/juliangut/slim-doctrine-middleware/downloads.svg)](https://packagist.org/packages/juliangut/slim-doctrine-middleware)

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
* `annotation_paths` array of paths where to find entities files
* `annotation_files` array of Doctrine annotations files
* `annotation_namespaces` array of Doctrine annotations namespaces
* `annotation_autoloaders` array of Doctrine annotations utoloaders
* `proxy_path` string, path were Doctrine creates it's proxy classes, defaults to /tmp

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/slim-doctrine-middleware/issues). Have a look at existing issues before

See file [CONTRIBUTING.md](https://github.com/juliangut/slim-doctrine-middleware/blob/master/CONTRIBUTING.md)

## License

### Release under BSD-3-Clause License.

See file [LICENSE](https://github.com/juliangut/slim-doctrine-middleware/blob/master/LICENSE) included with the source code for a copy of the license terms

