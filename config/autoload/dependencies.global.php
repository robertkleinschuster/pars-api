<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
            Mezzio\Application::class => Pars\Api\Application::class,
            Mezzio\Container\ApplicationFactory::class => Pars\Api\ApplicationFactory::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
            \Pars\Api\Application::class => \Pars\Api\ApplicationFactory::class,
            \Pars\Api\ApplicationContainer::class => \Pars\Api\ApplicationContainerFactory::class,
            \Pars\Api\Base\GetApiHandler::class => \Pars\Api\Base\ApiHandlerFactory::class,
            \Pars\Api\Base\PostApiHandler::class => \Pars\Api\Base\ApiHandlerFactory::class,
            \Mezzio\Handler\NotFoundHandler::class           => \Pars\Api\Base\ApiNotFoundHandlerFactory::class,
            \Mezzio\Middleware\ErrorResponseGenerator::class => \Pars\Api\Base\ApiErrorResponseGeneratorFactory::class,
        ],
    ],
];
