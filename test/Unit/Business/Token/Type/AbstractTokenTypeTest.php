<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Token\Type;

use Okaruto\Space\Business\Token\Type\AbstractTokenType;
use Okaruto\Space\Business\Token\Type\OneMonth;
use Okaruto\Space\Tests\ContainerTrait;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;
use Zalas\Injector\PHPUnit\TestCase\ServiceContainerTestCase;

/**
 * Class AbstractTokenTypeTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class AbstractTokenTypeTest extends TestCase implements ServiceContainerTestCase
{

    use ContainerTrait;

    /**
     * @var TranslationContainer
     * @inject
     */
    private $translationContainer;

    public function testValidTokenType(): void
    {
        $tokenType = new OneMonth(
            $this->translationContainer,
            6.66,
            'usd'
        );

        $this->assertSame('1month', $tokenType->type());
        $this->assertSame('usd', $tokenType->currency());
        $this->assertSame(6.66, $tokenType->price());
        $this->assertSame(1, $tokenType->connections());
        $this->assertSame('1 month', $tokenType->name());
    }

    public function testCompleteTokenType(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('has not all mandatory constants set!');

        new class($this->translationContainer, 6.66, 'usd') extends AbstractTokenType
        {

            public const I18N = 'token:name:onemonth';
            public const CONNECTIONS = 256;
        };
    }
}
