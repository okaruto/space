<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Integration\Business\Token;

use GuzzleHttp\Psr7\Response;
use Okaruto\Space\Business\Token;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Database\PDOSqliteMemory;

/**
 * Class TokenManagerWithAutoTypeTest
 *
 * @package   Okaruto\Space\Tests\Integration\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenManagerWithAutoTypeTest extends AbstractTokenManagerTest
{

    private const VALID_HTML = <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
That token is VALID and has not yet been used. It will expire %s days after first use.
</div></body></html>
EOT;

    public function testAddTokensWithAutoTypes(): void
    {
        $pdo = new PDOSqliteMemory();

        $tokenManager = $this->createTokenManager(
            [],
            [
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\OneWeek::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\OneMonth::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\ThreeMonths::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\SixMonths::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\OneYear::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\TwoYears::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\Lifetime::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\Lifetime::DAYS)),
            ],
            $pdo,
            true
        );

        $result = $tokenManager->addTokens([
            'unit0-test0-one00-week0',
            'unit0-test0-one00-month',
            'unit0-test0-three-month',
            'unit0-test0-six0m-onths',
            'unit0-test0-one00-year0',
            'unit0-test0-two00-years',
            'unit0-test0-life0-time0',
        ]);

        $this->assertSame(
            [0 => ['items' => '7']],
            $pdo->query('SELECT COUNT(id) as items FROM tokens;', \PDO::FETCH_ASSOC)->fetchAll()
        );

        $this->assertSame(
            [
                'added' => [
                    'unit0-test0-one00-week0' . ';' . Token\Type\OneWeek::TYPE,
                    'unit0-test0-one00-month' . ';' . Token\Type\OneMonth::TYPE,
                    'unit0-test0-three-month' . ';' . Token\Type\ThreeMonths::TYPE,
                    'unit0-test0-six0m-onths' . ';' . Token\Type\SixMonths::TYPE,
                    'unit0-test0-one00-year0' . ';' . Token\Type\OneYear::TYPE,
                    'unit0-test0-two00-years' . ';' . Token\Type\TwoYears::TYPE,
                    'unit0-test0-life0-time0' . ';' . Token\Type\Lifetime::TYPE,
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
                    ],
                ],
            ],
            $tokenManager->addTokens(['unit0-test0-life0-time0'])
        );
    }

    public function testAddInvalidTokens(): void
    {
        $pdo = new PDOSqliteMemory();

        $tokenManager = $this->createTokenManager(
            [],
            [
                new Response(200, [], sprintf('%s', '')),
                new Response(
                    200,
                    [],
                    <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
token is VALID and will expire in 666 days
</div></body></html>
EOT
                ),
                new Response(
                200,
                [],
                    <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
Invalid token.
</div></body></html>
EOT
                ),
                new Response(200, [], sprintf('%s', '')),
            ],
            $pdo,
            true
        );

        $this->assertSame(
            [
                'added' => [],
                'errors' => [
                    'format' => ['unit0-test0-one00-week0x'],
                    'spent' => ['unit0-test0-one00-month'],
                    'invalid' => ['unit0-test0-three-month'],
                    'unknown' => ['unit0-test0-six0m-onths'],
                ],
            ],
            $tokenManager->addTokens(
                [
                    'unit0-test0-one00-week0x',
                    'unit0-test0-one00-month',
                    'unit0-test0-three-month',
                    'unit0-test0-six0m-onths',
                ]
            )
        );
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

        $responses = array_merge(
            [
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\OneWeek::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\OneMonth::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\ThreeMonths::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\SixMonths::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\OneYear::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\TwoYears::DAYS)),
                new Response(200, [], sprintf(self::VALID_HTML, Token\Type\Lifetime::DAYS)),
            ],
            $responses
        );

        $tokenManager = $this->createTokenManager($requestContainer, $responses, $pdo, true);

        $tokenManager->addTokens([
            'unit0-test0-one00-week0',
            'unit0-test0-one00-month',
            'unit0-test0-three-month',
            'unit0-test0-six0m-onths',
            'unit0-test0-one00-year0',
            'unit0-test0-two00-years',
            'unit0-test0-life0-time0',
        ]);

        return $tokenManager;
    }
}
