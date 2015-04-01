<?php
/**
 * Slim Framework Doctrine middleware (https://github.com/juliangut/slim-doctrine-middleware)
 *
 * @link https://github.com/juliangut/slim-doctrine-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-doctrine-middleware/master/LICENSE
 */

namespace Jgut\Slim\Middleware;

use Slim\Middleware;

/**
 * Doctrine handler middleware.
 */
class DoctrineMiddleware extends Middleware
{
    /**
     * {@inheritDoc}
     */
    public function call()
    {
        $this->next->call();
    }
}
