<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use League\Plates\Engine;
use Okaruto\Space\Renderer\Extensions\CurrencyExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class CurrencyExtensionTest extends TestCase
{

    public function testExtensionRegister(): void
    {
        $extension = new CurrencyExtension();
        $function = (new Engine())->loadExtension($extension)->getFunction('currency');

        $this->assertSame(
            [$extension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testValidCurrencies(): void
    {
        $extension = new CurrencyExtension();

        $this->assertSame('$ 6.66', $extension('usd', 6.66));
        $this->assertSame('₽ 6.66', $extension('rur', 6.66));
        $this->assertSame('€ 6.66', $extension('eur', 6.66));
    }

    public function testInvalidCurrency(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Currency mapping not set for yen');

        (new CurrencyExtension())('yen', 6.66);
    }
}
