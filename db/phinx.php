<?php

declare(strict_types=1);

define('APPLICATION', __DIR__ . '/..');

require(APPLICATION . '/vendor/autoload.php');

$container = require(APPLICATION . '/config/container.php');

use \Okaruto\Space\Database;

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'production',
        'production' => [
            'adapter' => 'sqlite',
            'name' => 'database',
            'connection' => $container->get(Database\PDOSqliteFile::class),
        ],
    ],
    'version_order' => 'creation',
];
