<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\CryptoCurrency;

use Okaruto\Space\Business\CryptoCurrency\AbstractCryptoCurrency;
use Okaruto\Space\Image\Svg;
use Okaruto\Space\Opcache\Opcache;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractCryptoCurrencyTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\CryptoCurrency
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class AbstractCryptoCurrencyTest extends TestCase
{

    private const BASEPATH = APPLICATION . '/ui';
    private const CRYPTOCURRENCY_IMAGES = 'images/cryptocoins';

    public function testValidCryptoCurrency(): void
    {
        $image = new Svg(self::CRYPTOCURRENCY_IMAGES, new Opcache(null, self::BASEPATH));

        $cryptoCurrency = new class($image) extends AbstractCryptoCurrency
        {

            const NAME = 'Bitcoin';
            const SHORTCODE = 'btc';
            const CODE = 'bitcoin';
        };

        $this->assertSame('(BTC) Bitcoin', $cryptoCurrency->name());
        $this->assertSame('btc', $cryptoCurrency->shortCode());
        $this->assertSame('bitcoin', $cryptoCurrency->code());
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $cryptoCurrency->image());
    }

    public function testInvalidCryptoCurrency(): void
    {
        $image = new Svg(self::CRYPTOCURRENCY_IMAGES, new Opcache(null, self::BASEPATH));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('has not all needed constants set!');

        new class($image) extends AbstractCryptoCurrency
        {

            const NAME = 'Bitcoin';
        };
    }
}
