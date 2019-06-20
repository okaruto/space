<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests;

/**
 * Trait MigrateDatebaseTrait
 *
 * @package   Okaruto\Space\Tests
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
trait MigrateDatebaseTrait
{

    /**
     * @param \PDO $pdo
     *
     * @return \PDO
     */
    protected function migrateDatabase(\PDO $pdo): \PDO
    {
        $config = new \Phinx\Config\Config(
            [
                'paths' => [
                    'migrations' => APPLICATION . '/db/migrations',
                    'seeds' => APPLICATION . '/db/seeds',
                ],
                'environments' => [
                    'default_migration_table' => 'phinxlog',
                    'default_database' => 'space',
                    'unit_test' => [
                        'adapter' => 'sqlite',
                        'name' => 'database',
                        'connection' => $pdo,
                    ],
                ],
                'version_order' => 'creation',
            ]
        );

        $manager = new \Phinx\Migration\Manager(
            $config,
            new \Symfony\Component\Console\Input\StringInput(' '),
            new \Symfony\Component\Console\Output\NullOutput()
        );

        $manager->migrate('unit_test');

        return $pdo;
    }
}
