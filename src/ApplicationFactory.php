<?php

namespace Pars\Api;

use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\RouteCollector;
use Psr\Container\ContainerInterface;

/**
 * Class ApplicationFactory
 * @package Pars\Api
 */
class ApplicationFactory extends \Mezzio\Container\ApplicationFactory
{
    public function __invoke(ContainerInterface $container): Application
    {
        $app = new Application(
            $container->get(MiddlewareFactory::class),
            $container->get('Mezzio\ApplicationPipeline'),
            $container->get(RouteCollector::class),
            $container->get(RequestHandlerRunner::class)
        );
        $factory = $container->get(\Mezzio\MiddlewareFactory::class);
        (require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'pipeline.php'])))($app, $factory, $container);
        (require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'routes.php'])))($app, $factory, $container);
        return $app;
    }
}
