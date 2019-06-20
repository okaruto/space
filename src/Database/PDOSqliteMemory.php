<?php

declare(strict_types=1);

namespace Okaruto\Space\Database;

/**
 * Class PDOSqliteMemory
 *
 * @package   Okaruto\Space\Database
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class PDOSqliteMemory extends AbstractPDO
{

    /**
     * SqliteMemoryConnection constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct(
            'sqlite::memory:',
            '',
            '',
            $options
        );
    }
}
