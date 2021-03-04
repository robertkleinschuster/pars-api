<?php

namespace Pars\Api\Base;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ApiHandlerFactory
 * @package Pars\Api\Base
 */
class ApiHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $requestedName)
    {
        return new $requestedName($container->get(AdapterInterface::class), $container->get('config'));
    }

}
