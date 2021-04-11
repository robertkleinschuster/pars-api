<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

const PARS_API_VERSION = 'dev';


// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data' , 'cache', 'config', PARS_API_VERSION, 'config.php']),
];

$aggregator = new ConfigAggregator([
    \Laminas\Serializer\ConfigProvider::class,
    \Laminas\Cache\ConfigProvider::class,
    \Laminas\I18n\ConfigProvider::class,
    \Laminas\Log\ConfigProvider::class,
    \Laminas\Hydrator\ConfigProvider::class,
    \Laminas\Db\ConfigProvider::class,
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,
    \Mezzio\Flash\ConfigProvider::class,
    \Mezzio\Session\Cache\ConfigProvider::class,
    \Mezzio\Authentication\Session\ConfigProvider::class,
    \Mezzio\Csrf\ConfigProvider::class,
    \Mezzio\Session\ConfigProvider::class,
    \Mezzio\Authentication\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,

    // Include cache configuration
    new ArrayProvider($cacheConfig),

    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,

    // Default App module config
    \Pars\Model\ConfigProvider::class,
    \Pars\Core\ConfigProvider::class,
    \Pars\Api\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),

    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
