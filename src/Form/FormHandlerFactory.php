<?php


namespace Pars\Api\Form;


use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class FormHandlerFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName)
    {
        return new $requestedName($container->get('config'), $container->get(AdapterInterface::class));
    }

}
