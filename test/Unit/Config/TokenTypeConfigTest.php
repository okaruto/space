<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use League\Container\Container;
use Okaruto\Space\Business\Token;
use Okaruto\Space\Config\Config;
use Okaruto\Space\Config\TokenTypeConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenTypeConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenTypeConfigTest extends TestCase
{

    public function testTokenTypeConfig(): void
    {
        $tokenTypeConfig = new TokenTypeConfig([
            Token\Type\OneWeek::class => 1.86,
            Token\Type\OneMonth::class => 6.00,
            Token\Type\ThreeMonths::class => 16.00,
            Token\Type\SixMonths::class => 28.00,
            Token\Type\OneYear::class => 52.00,
            Token\Type\TwoYears::class => 94.00,
            Token\Type\Lifetime::class => 500.00,
        ]);

        $this->assertSame(
            [
                Token\Type\OneWeek::class => 1.86,
                Token\Type\OneMonth::class => 6.00,
                Token\Type\ThreeMonths::class => 16.00,
                Token\Type\SixMonths::class => 28.00,
                Token\Type\OneYear::class => 52.00,
                Token\Type\TwoYears::class => 94.00,
                Token\Type\Lifetime::class => 500.00,
            ],
            $tokenTypeConfig->all()
        );
    }

    public function testTokenTypeConfigEmptyList(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No token types configured');

        new TokenTypeConfig([]);
    }

    public function testTokenTypeConfigInvalidTokenTypes(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Some configured tokens ("Okaruto\Space\Config\Config", "League\Container\Container") do not implement ' .
            'the interface Okaruto\Space\Business\Token\Type\TokenTypeInterface'
        );

        new TokenTypeConfig([
            Config::class => 6.66,
            Token\Type\OneWeek::class => 1.86,
            Container::class => 66.6,
        ]);
    }
}
