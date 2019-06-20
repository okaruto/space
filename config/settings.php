<?php

declare(strict_types=1);

use Okaruto\Space\Command;

return array_replace_recursive(
    [
        'settings' => [
            'displayErrorDetails' => false,
            'addContentLengthHeader' => false,
            'routerCacheFile' => APPLICATION . '/data/routercache.php',
            'determineRouteBeforeAppMiddleware' => true,
            'renderer' => [ // Renderer settings
                'template_path' => APPLICATION . '/ui/html',
                'template_extension' => 'phtml',
            ],
            'logger' => [ // Monolog settings
                'name' => 'space',
                'path' => APPLICATION . '/data/logs/space.log',
                'level' => \Monolog\Logger::NOTICE,
            ],
            'translation' => [
                'path' => APPLICATION . '/languages',
                'fallback' => 'en',

            ],
            'database' => [
                'file' => APPLICATION . '/data/database.sqlite',
            ],
            'opcache' => APPLICATION . '/data/cache',
        ],
        'commands' => [
            Command\HousekeepingCommand::class,
            Command\DispenseTokenCommand::class,
            Command\VerifyTokenCommand::class,
        ],
        'space' => require(APPLICATION . '/config/space.php'),
    ], file_exists(APPLICATION . '/data/settings.dev.php')
    ? require(APPLICATION . '/data/settings.dev.php')
    : []
);
