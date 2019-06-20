<?php

declare(strict_types=1);

namespace Okaruto\Space\Database;

/**
 * Trait PrepareStatementTrait
 *
 * @package   Okaruto\Space\Database
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
trait PrepareStatementTrait
{

    /** \PDO */
    protected $pdo;

    /** @var array */
    private $preparedStatements;

    /**
     * @param string $query
     *
     * @return \PDOStatement
     */
    protected function prepareStatement(string $query): \PDOStatement
    {
        if (!isset($this->preparedStatements[$query])) {
            $this->preparedStatements[$query] = $this->pdo->prepare($query);
        }

        return $this->preparedStatements[$query];
    }
}
