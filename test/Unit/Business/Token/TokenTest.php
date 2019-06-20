<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Token;

use Okaruto\Space\Business\Token\Token;
use Okaruto\Space\Business\Token\Type\OneMonth;
use Okaruto\Space\Tests\ContainerTrait;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;
use Zalas\Injector\PHPUnit\TestCase\ServiceContainerTestCase;

/**
 * Class TokenTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenTest extends TestCase implements ServiceContainerTestCase
{

    use ContainerTrait;

    /**
     * @var TranslationContainer
     * @inject
     */
    private $translationContainer;

    public function testValidToken(): void
    {
        $token = new Token(
            1,
            'unit0-test0-token-00000',
            new OneMonth($this->translationContainer, 6.66, 'usd')
        );

        $this->assertSame(true, $token->valid());
        $this->assertSame(1, $token->id());
        $this->assertSame('unit0-test0-token-00000', $token->value());
        $this->assertInstanceOf(OneMonth::class, $token->type());
    }

    public function testInvalidTokenId(): void
    {
        $token = new Token(
            0,
            'unit0-test0-token-00000',
            new OneMonth($this->translationContainer, 6.66, 'usd')
        );

        $this->assertSame(false, $token->valid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Token not valid');
        $token->valid(true);
    }

    public function testInvalidTokenValue(): void
    {
        $token = new Token(
            1,
            '',
            new OneMonth($this->translationContainer, 6.66, 'usd')
        );

        $this->assertSame(false, $token->valid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Token not valid');
        $token->valid(true);
    }

    public function testInvalidTokenType(): void
    {
        $token = new Token(
            1,
            'unit0-test0-token-00000',
            null
        );

        $this->assertSame(false, $token->valid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Token not valid');
        $token->valid(true);
    }
}
