<?php

namespace Pars\Api;

use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\RouteCollector;
use Pars\Core\Application\AbstractApplication;
use Pars\Core\Application\AbstractApplicationContainer;
use Pars\Core\Application\AbstractApplicationFactory;

/**
 * Class ApplicationFactory
 * @package Pars\Api
 */
class ApplicationFactory extends AbstractApplicationFactory
{
    protected function createApplication(MiddlewareFactory $factory, MiddlewarePipeInterface $pipeline, RouteCollector $routes, RequestHandlerRunner $runner): AbstractApplication
    {
        return new \Pars\Api\Application($factory, $pipeline, $routes, $runner);
    }

    protected function initPipeline(AbstractApplication $app, MiddlewareFactory $factory, AbstractApplicationContainer $container)
    {
        (require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'pipeline.php'])))($app, $factory, $container);
    }

    protected function initRoutes(AbstractApplication $app, MiddlewareFactory $factory, AbstractApplicationContainer $container)
    {
        (require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'routes.php'])))($app, $factory, $container);
    }


}
