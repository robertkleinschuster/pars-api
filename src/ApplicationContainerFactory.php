<?php

declare(strict_types=1);

namespace Pars\Api;

use Pars\Core\Application\AbstractApplicationContainer;
use Pars\Core\Application\AbstractApplicationContainerFactory;

/**
 * Class ApplicationContainerFactory
 * @package Pars\Api
 */
class ApplicationContainerFactory extends AbstractApplicationContainerFactory
{
    protected function createApplicationContainer(array $dependencies): AbstractApplicationContainer
    {
        return new ApplicationContainer($dependencies);
    }


    protected function getApplicationConfig(): array
    {
        return require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'config.php']));
    }
}
