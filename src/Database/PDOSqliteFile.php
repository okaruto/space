<?php

declare(strict_types=1);

namespace Okaruto\Space\Database;

/**
 * Class PDOSqliteFile
 *
 * @package   Okaruto\Space\Database
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class PDOSqliteFile extends AbstractPDO
{

    /**
     * PdoSqliteFileConnection constructor.
     *
     * @param string $file
     * @param array  $options
     */
    public function __construct(string $file, array $options = [])
    {
        parent::__construct(
            'sqlite:' . $file,
            '',
            '',
            $options
        );
    }
}
