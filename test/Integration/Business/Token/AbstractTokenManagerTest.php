<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Integration\Business\Token;

use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;
use Okaruto\Space\Business\Order\Order;
use Okaruto\Space\Business\Token;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Business\Token\TokenValidator;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Database\PDOSqliteMemory;
use Okaruto\Space\Tests\MigrateDatebaseTrait;
use Okaruto\Space\Tests\PrepareClientTrait;
use Okaruto\Space\Tests\TokenTypeCollectionTrait;
use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenManagerTest
 *
 * @package   Okaruto\Space\Tests\Integration\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractTokenManagerTest extends TestCase
{

    use MigrateDatebaseTrait;
    use PrepareClientTrait;
    use TokenTypeCollectionTrait;

    /**
     * @param array $requestContainer
     * @param array $responses
     * @param \PDO  $pdo
     * @param bool  $verify
     *
     * @return TokenManager
     */
    protected function createTokenManager(
        array $requestContainer,
        array $responses,
        \PDO $pdo,
        bool $verify
    ): TokenManager {
        return new TokenManager(
            $this->migrateDatabase($pdo),
            $this->tokenTypeCollection(),
            new TokenValidator(
                new AdminConfig([AdminConfig::KEY_KEY => 'key', AdminConfig::KEY_VERIFY_TOKENS => $verify]),
                $this->client($responses, $requestContainer))
        );
    }

    public function testRemoveToken(): void
    {
        $pdo = new PDOSqliteMemory();
        $tokenManager = $this->createTokenManagerPreseeded([], [], $pdo);

        $this->assertSame(1, $tokenManager->available(Token\Type\OneMonth::TYPE)->amount());
        $tokenManager->remove($tokenManager->randomToken($tokenManager->available(Token\Type\OneMonth::TYPE)));
        $this->assertSame(0, $tokenManager->available(Token\Type\OneMonth::TYPE)->amount());
    }

    public function testToken(): void
    {
        $tokenManager = $this->createTokenManagerPreseeded([], [], new PDOSqliteMemory());

        $validToken = $tokenManager->token(1);

        $this->assertInstanceOf(Token\Token::class, $validToken);
        $this->assertSame(true, $validToken->valid());
        $this->assertInstanceOf(Token\Type\OneWeek::class, $validToken->type());

        $invalidToken = $tokenManager->token(1024);
        $this->assertSame(false, $invalidToken->valid());
    }

    /**
     * @param array $requestContainer
     * @param array $responses
     * @param \PDO  $pdo
     *
     * @return TokenManager
     */
    abstract protected function createTokenManagerPreseeded(
        array $requestContainer,
        array $responses,
        \PDO $pdo
    ): TokenManager;
}
