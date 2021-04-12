<?php

return [
    'psr_log' => [
        'Logger' => [
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
