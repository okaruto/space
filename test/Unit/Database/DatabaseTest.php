<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Database;

use Okaruto\Space\Database\PDOSqliteFile;
use Okaruto\Space\Database\PDOSqliteMemory;
use Okaruto\Space\Database\PrepareStatementTrait;
use Okaruto\Space\Tests\MigrateDatebaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseTest
 *
 * These tests are to determine if foreign key constraints are
 * working with the installed sqlite and the cascade is correctly working
 *
 * @package   Okaruto\Space\Tests\Unit
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class DatabaseTest extends TestCase
{

    use MigrateDatebaseTrait;

    private const DB_FILE = APPLICATION . '/data/unit_test_database.sqlite';
    private const SQL_FOREIGN_KEYS = 'PRAGMA foreign_keys;';
    private const SQL_INSERT_TOKEN_1 = <<<'EOT'
INSERT INTO tokens (value, type) VALUES ('CsTok-enGvX-F4b4a-j7CEC', '1month');
EOT;
    private const SQL_INSERT_TOKEN_2 = <<<'EOT'
INSERT INTO tokens (value, type) VALUES ('CsTok-enGvX-F4b4a-j7CED', '1month');
EOT;
    private const SQL_INSERT_ORDER_1 = <<<'EOT'
INSERT INTO orders (id, status, price, token_id)
VALUES ('05167817-ddd6-410a-af10-169852fc30ed', 'new', 6.66, 1);
EOT;
    private const SQL_INSERT_ORDER_2 = <<<'EOT'
INSERT INTO orders (id, status, price, token_id)
VALUES ('fb088e52-3127-4a61-aa3d-f43495384a62', 'new', 6.66, 2);
EOT;
    private const SQL_INSERT_INVOICE_1 = [
        <<<'EOT'
INSERT INTO invoices (order_id, invoice_status) VALUES ('05167817-ddd6-410a-af10-169852fc30ed', 'unpaid'); 
EOT
        ,
        <<<'EOT'
INSERT INTO invoices (order_id, invoice_status) VALUES ('05167817-ddd6-410a-af10-169852fc30ed', 'mispaid');
EOT
        ,
    ];

    private const SQL_INSERT_INVOICE_2 = [
        <<<'EOT'
INSERT INTO invoices (order_id, invoice_status) VALUES ('fb088e52-3127-4a61-aa3d-f43495384a62', 'unpaid');
EOT
        ,
        <<<'EOT'
INSERT INTO invoices (order_id, invoice_status) VALUES ('fb088e52-3127-4a61-aa3d-f43495384a62', 'pending');
EOT
        ,
        <<<'EOT'
INSERT INTO invoices (order_id, invoice_status) VALUES ('fb088e52-3127-4a61-aa3d-f43495384a62', 'paid');
EOT
        ,
    ];
    private const SQL_COUNT_TOKENS = 'SELECT COUNT(value) AS count FROM tokens;';
    private const SQL_COUNT_ORDERS = 'SELECT COUNT(id) AS count FROM orders;';
    private const SQL_COUNT_INVOICES = 'SELECT COUNT(id) AS count FROM invoices;';
    private const SQL_DELETE_TOKEN_1 = 'DELETE FROM tokens WHERE value = \'CsTok-enGvX-F4b4a-j7CEC\';';
    private const SQL_DELETE_TOKEN_2 = 'DELETE FROM tokens WHERE value = \'CsTok-enGvX-F4b4a-j7CED\';';

    public function testMemoryDatabaseSetup(): void
    {
        $pdoMemory = new PDOSqliteMemory();

        $this->assertSame(
            ['foreign_keys' => '1'],
            $pdoMemory->query(self::SQL_FOREIGN_KEYS)->fetch(\PDO::FETCH_ASSOC)
        );

        $this->prepareDatabase($pdoMemory);
        $this->runCascade($pdoMemory);

    }

    private function prepareDatabase(\PDO $pdo): void
    {

        $this->migrateDatabase($pdo);

        $pdo->query(self::SQL_INSERT_TOKEN_1);
        $pdo->query(self::SQL_INSERT_TOKEN_2);

        $this->assertSame(['count' => '2'], $pdo->query(self::SQL_COUNT_TOKENS)->fetch(\PDO::FETCH_ASSOC));

        $pdo->query(self::SQL_INSERT_ORDER_1);
        $pdo->query(self::SQL_INSERT_ORDER_2);

        $this->assertSame(['count' => '2'], $pdo->query(self::SQL_COUNT_ORDERS)->fetch(\PDO::FETCH_ASSOC));

        foreach (self::SQL_INSERT_INVOICE_1 as $query) {
            $pdo->query($query);
        }

        foreach (self::SQL_INSERT_INVOICE_2 as $query) {
            $pdo->query($query);
        }

        $this->assertSame(['count' => '5'], $pdo->query(self::SQL_COUNT_INVOICES)->fetch(\PDO::FETCH_ASSOC));

    }

    private function runCascade(\PDO $pdo): void
    {
        $pdo->query(self::SQL_DELETE_TOKEN_1);

        $this->assertSame(['count' => '1'], $pdo->query(self::SQL_COUNT_TOKENS)->fetch(\PDO::FETCH_ASSOC));
        $this->assertSame(['count' => '1'], $pdo->query(self::SQL_COUNT_ORDERS)->fetch(\PDO::FETCH_ASSOC));
        $this->assertSame(['count' => '3'], $pdo->query(self::SQL_COUNT_INVOICES)->fetch(\PDO::FETCH_ASSOC));

        $pdo->query(self::SQL_DELETE_TOKEN_2);

        $this->assertSame(['count' => '0'], $pdo->query(self::SQL_COUNT_TOKENS)->fetch(\PDO::FETCH_ASSOC));
        $this->assertSame(['count' => '0'], $pdo->query(self::SQL_COUNT_ORDERS)->fetch(\PDO::FETCH_ASSOC));
        $this->assertSame(['count' => '0'], $pdo->query(self::SQL_COUNT_INVOICES)->fetch(\PDO::FETCH_ASSOC));
    }

    public function testFileDatabaseSetup(): void
    {
        $pdoFile = new PDOSqliteFile(self::DB_FILE);

        $this->assertSame(
            ['foreign_keys' => '1'],
            $pdoFile->query(self::SQL_FOREIGN_KEYS)->fetch(\PDO::FETCH_ASSOC)
        );

        $this->prepareDatabase($pdoFile);
        $this->runCascade($pdoFile);

        unset($pdoFile);
        unlink(self::DB_FILE);
    }

    public function testPrepareStatementTrait(): void
    {
        $pdoMemory = new PDOSqliteMemory();
        $this->prepareDatabase($pdoMemory);

        $class = new class($pdoMemory)
        {

            use PrepareStatementTrait;

            public function __construct(\PDO $pdo)
            {
                $this->pdo = $pdo;
            }

            public function statement(): \PDOStatement
            {
                return $this->prepareStatement('SELECT id, value, type FROM tokens WHERE id = :id');
            }

        };

        $statement = $class->statement();

        $this->assertInstanceOf(\PDOStatement::class, $statement);

        $this->assertSame($statement, $class->statement());

        $statement->execute(['id' => 1]);

        $this->assertSame(
            [['id' => '1', 'value' => 'CsTok-enGvX-F4b4a-j7CEC', 'type' => '1month']],
            $statement->fetchAll(\PDO::FETCH_ASSOC)
        );
    }
}
