<?php

declare(strict_types=1);

namespace Pars\Api;

use Pars\Core\Deployment\CacheClearer;

/**
 * Class ApplicationContainerFactory
 * @package Pars\Api
 */
class ApplicationContainerFactory
{
    public function __invoke()
    {
        $config = $this->getApplicationConfig();
        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;
        return new ApplicationContainer($dependencies);
    }

    protected function getApplicationConfig(): array
    {
        return require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'config.php']));
    }
}
