<?php

return [
    'psr_log' => [
        \Psr\Log\LoggerInterface::class => [
            'writers' => [
                'syslog' => [
                    'options' => [
                        'application' => 'pars-api',
                    ],
                ],
            ],
        ],
    ],
];
