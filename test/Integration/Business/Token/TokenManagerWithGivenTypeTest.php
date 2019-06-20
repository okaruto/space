<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Integration\Business\Token;

use Okaruto\Space\Business\Token;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Database\PDOSqliteMemory;

/**
 * Class TokenManagerWithGivenTypeTest
 *
 * @package   Okaruto\Space\Tests\Integration\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenManagerWithGivenTypeTest extends AbstractTokenManagerTest
{

    public function testAddTokensWithGivenTypes(): void
    {
        $pdo = new PDOSqliteMemory();

        $tokenManager = $this->createTokenManager([], [], $pdo, false);

        $result = $tokenManager->addTokens([
            'unit0-test0-one00-week0' . ';' . Token\Type\OneWeek::TYPE,
            'unit0-test0-one00-month' . ';' . Token\Type\OneMonth::TYPE,
            'unit0-test0-three-month' . ';' . Token\Type\ThreeMonths::TYPE,
            'unit0-test0-six0m-onths' . ';' . Token\Type\SixMonths::TYPE,
            'unit0-test0-one00-year0' . ';' . Token\Type\OneYear::TYPE,
            'unit0-test0-two00-years' . ';' . Token\Type\TwoYears::TYPE,
            'unit0-test0-life0-time0' . ';' . Token\Type\Lifetime::TYPE,
        ]);

        $this->assertSame(
            [0 => ['items' => '7']],
            $pdo->query('SELECT COUNT(id) as items FROM tokens;', \PDO::FETCH_ASSOC)->fetchAll()
        );

        $this->assertSame(
            [
                'added' => [
                    'unit0-test0-one00-week0;1week',
                    'unit0-test0-one00-month;1month',
                    'unit0-test0-three-month;3months',
                    'unit0-test0-six0m-onths;6months',
                    'unit0-test0-one00-year0;1year',
                    'unit0-test0-two00-years;2years',
                    'unit0-test0-life0-time0;lifetime',
                ],
                'errors' => [],
            ],
            $result
        );

        $this->assertSame(
            [
                'added' => [],
                'errors' => [
                    'duplicates' => [
                        'unit0-test0-life0-time0',
                    ]
                ]
            ],
            $tokenManager->addTokens(['unit0-test0-life0-time0;lifetime'])
        );
    }

    public function testAddTokensWithMissingType(): void
    {
        $pdo = new PDOSqliteMemory();

        $tokenManager = $this->createTokenManager([], [], $pdo, false);

        $result = $tokenManager->addTokens(['unit0-test0-one00-week0']);

        $this->assertSame(
            [0 => ['items' => '0']],
            $pdo->query('SELECT COUNT(id) as items FROM tokens;', \PDO::FETCH_ASSOC)->fetchAll()
        );

        $this->assertSame(
            [
                'added' => [],
                'errors' => [
                    'mangled' => ['unit0-test0-one00-week0'],
                ],
            ],
            $result
        );
    }

    public function testAddInvalidTokens(): void
    {
        $pdo = new PDOSqliteMemory();

        $tokenManager = $this->createTokenManager([], [], $pdo, false);

        $result = $tokenManager->addTokens([
            'unit0-test0-invalid-format;1month',
            'invalid-format-00000-00000;1month',
        ]);

        $this->assertSame(
            [0 => ['items' => '0']],
            $pdo->query('SELECT COUNT(id) as items FROM tokens;', \PDO::FETCH_ASSOC)->fetchAll()
        );

        $this->assertSame(
            [
                'added' => [],
                'errors' => [
                    'format' => [
                        'unit0-test0-invalid-format;1month',
                        'invalid-format-00000-00000;1month',
                    ],
                ],
            ],
            $result
        );
    }

    public function testAddTokenUnavailable(): void
    {
        $pdo = new PDOSqliteMemory();
        $tokenManager = $this->createTokenManager([], [], $pdo, false);

        $result = $tokenManager->addTokens([
            'unit0-test0-seven-weeks;7weeks',
        ]);

        $this->assertSame(
            [0 => ['items' => '0']],
            $pdo->query('SELECT COUNT(id) as items FROM tokens;', \PDO::FETCH_ASSOC)->fetchAll()
        );

        $this->assertSame(
            [
                'added' => [],
                'errors' => [
                    'unavailable' => ['unit0-test0-seven-weeks -> 7weeks'],
                ],
            ],
            $result
        );
    }

    public function testAllAvailable(): void
    {
        $pdo = new PDOSqliteMemory();
        $allAvailable = $this->createTokenManagerPreseeded([], [], $pdo)->allAvailable();

        $this->assertSame(7, count($allAvailable));
        $this->assertContainsOnlyInstancesOf(Token\AvailableTokenInterface::class, $allAvailable);
    }

    public function testRandomToken(): void
    {
        $tokenManager = $this->createTokenManager([], [], new PDOSqliteMemory(), false);

        $tokens = [
            'unit0-test0-00one-month;1month',
            'unit1-test1-11one-month;1month',
            'unit2-test2-22one-month;1month',
        ];

        $tokenManager->addTokens($tokens);

        $randomToken = $tokenManager->randomToken($tokenManager->available(Token\Type\OneMonth::TYPE));
        $this->assertSame(true, in_array($randomToken->value() . ';1month', $tokens, true));
    }

    /**
     * @param array $requestContainer
     * @param array $responses
     * @param \PDO  $pdo
     *
     * @return TokenManager
     */
    protected function createTokenManagerPreseeded(
        array $requestContainer,
        array $responses,
        \PDO $pdo
    ): TokenManager {
        $tokenManager = $this->createTokenManager($requestContainer, $responses, $pdo, false);

        $tokenManager->addTokens([
            'unit0-test0-one00-week0' . ';' . Token\Type\OneWeek::TYPE,
            'unit0-test0-one00-month' . ';' . Token\Type\OneMonth::TYPE,
            'unit0-test0-three-month' . ';' . Token\Type\ThreeMonths::TYPE,
            'unit0-test0-six0m-onths' . ';' . Token\Type\SixMonths::TYPE,
            'unit0-test0-one00-year0' . ';' . Token\Type\OneYear::TYPE,
            'unit0-test0-two00-years' . ';' . Token\Type\TwoYears::TYPE,
            'unit0-test0-life0-time0' . ';' . Token\Type\Lifetime::TYPE,
        ]);

        return $tokenManager;
    }
}
