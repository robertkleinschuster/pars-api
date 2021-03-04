<?php

declare(strict_types=1);

namespace Pars\Api;

/**
 * The configuration provider for the Api module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [

            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                \Pars\Api\Application::class => \Pars\Api\ApplicationFactory::class,
                \Pars\Api\ApplicationContainer::class => \Pars\Api\ApplicationContainerFactory::class,
            ],
        ];
    }
}
