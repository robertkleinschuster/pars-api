<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {

    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';
    $adapter = $container->get(\Laminas\Db\Adapter\AdapterInterface::class);
    $translator = $container->get(\Laminas\I18n\Translator\TranslatorInterface::class);

    $cache = new \Pars\Core\Deployment\Cache($container->get('config'), $adapter);
    $cache->setTranslator($translator);
    $now = new DateTime();
    $cache->removeOption(\Pars\Core\Deployment\Cache::OPTION_RESET_OPCACHE);
    $cache->removeOption(\Pars\Core\Deployment\Cache::OPTION_CLEAR_IMAGES);
    $cache->removeOption(\Pars\Core\Deployment\Cache::OPTION_CLEAR_ASSETS);
    $cache->removeOption(\Pars\Core\Deployment\Cache::OPTION_CLEAR_CACHE_POOL);
    $cache->removeOption(\Pars\Core\Deployment\Cache::OPTION_CLEAR_CONFIG);
    if ($now->format('H:i') != '00:00') {
        $cache->removeOption(\Pars\Core\Deployment\Cache::OPTION_CLEAR_BUNDLES);
    }
    $cache->clear();
    echo 'Cache Cleared';
})();
