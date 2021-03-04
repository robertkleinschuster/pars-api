<?php

declare(strict_types=1);

namespace Pars\Api;

/**
 * Class ApplicationContainerFactory
 * @package Pars\Api
 */
class ApplicationContainerFactory
{
    public function __invoke()
    {
        $config = $this->getApplicationConfig();
        register_shutdown_function(function () use ($config) {
            $error = error_get_last();
            if (isset($error['type']) && $error['type'] === E_ERROR) {
                if (isset($config['config_cache_path']) && file_exists($config['config_cache_path'])) {
                    unlink($config['config_cache_path']);
                }
            }
        });
        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;
        return new ApplicationContainer($dependencies);
    }

    protected function getApplicationConfig(): array
    {
        return require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'config.php']));
    }
}
