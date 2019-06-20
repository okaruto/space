<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Token;

use Okaruto\Space\Business\Token\AvailableToken;
use Okaruto\Space\Business\Token\Type\OneMonth;
use Okaruto\Space\Tests\ContainerTrait;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;
use Zalas\Injector\PHPUnit\TestCase\ServiceContainerTestCase;

/**
 * Class AvailableTokenTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class AvailableTokenTest extends TestCase implements ServiceContainerTestCase
{

    use ContainerTrait;

    /**
     * @var TranslationContainer
     * @inject
     */
    private $translationContainer;

    public function testValidAvailableToken(): void
    {
        $tokenType = new OneMonth($this->translationContainer, 6.66, 'usd');

        $availableToken = new AvailableToken(5, $tokenType);

        $this->assertSame('usd', $availableToken->currency());
        $this->assertSame(6.66, $availableToken->price());
        $this->assertSame(OneMonth::TYPE, $availableToken->type());
        $this->assertSame(5, $availableToken->amount());
        $this->assertSame($tokenType, $availableToken->typeObject());
        $this->assertSame(OneMonth::CONNECTIONS, $availableToken->connections());
        $this->assertSame('1 month', $availableToken->name());
    }
}
