<?php


namespace Pars\Api\Base;


use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class ApiKeyMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ApiKeyMiddleware( $container->get('config'), $container->get(AdapterInterface::class));
    }

}
