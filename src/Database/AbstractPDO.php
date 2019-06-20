<?php

declare(strict_types=1);

namespace Okaruto\Space\Database;

/**
 * Class AbstractPDO
 *
 * @package   Okaruto\Space\Database
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractPDO extends \PDO
{

    /**
     * AbstractPDO constructor.
     *
     * @param string $dsn
     * @param string $username
     * @param string $passwd
     * @param array  $options
     */
    public function __construct(string $dsn, string $username, string $passwd, array $options)
    {
        parent::__construct($dsn, $username, $passwd, $options);

        $this->query('PRAGMA foreign_keys = ON;');
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
