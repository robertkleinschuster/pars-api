<?php

return [
    'api' => [
        'finder' => [
            'page' => \Pars\Model\Cms\Page\CmsPageBeanFinder::class,
            'post' => \Pars\Model\Cms\Post\CmsPostBeanFinder::class,
            'block' => \Pars\Model\Cms\Block\CmsBlockBeanFinder::class,
            'menu' => \Pars\Model\Cms\Menu\CmsMenuBeanFinder::class,
            'config' => \Pars\Model\Config\ConfigBeanFinder::class,
            'locale' => \Pars\Model\Localization\Locale\LocaleBeanFinder::class,
        ],
        'key' => [
          'enabled' => true
        ],
    ],
];
