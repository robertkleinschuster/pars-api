<?php

return [
    'psr_log' => [
        'Logger' => [
            'exceptionhandler' => true,
            'errorhandler' => true,
            'fatal_error_shutdownfunction' => true,
            'writers' => [
                'syslog' => [
                    'name' => 'syslog',
                    'priority' => 1,
                    'options' => [

                        'application' => 'pars-api',
                        'facility' => LOG_LOCAL0,
                        'formatter' => [
                            'name' => Laminas\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => [
                                'name' => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => Laminas\Log\Logger::INFO,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => Laminas\Log\Processor\RequestId::class,
                ],
            ],
        ],
    ],
];
