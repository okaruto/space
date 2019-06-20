<?php

declare(strict_types=1);

return [
    'settings' => [
        'displayErrorDetails' => true,
        'routerCacheFile' => false,
        'logger' => [ // Monolog settings
            'level' => \Monolog\Logger::DEBUG,
        ],
        'opcache' => null,
    ],
];
