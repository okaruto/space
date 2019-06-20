<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\CryptoCurrency;

use Okaruto\Space\Business\CryptoCurrency;
use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyCollection;
use Okaruto\Space\Config\CryptoCurrencyConfig;
use Okaruto\Space\Image\Svg;
use Okaruto\Space\Opcache\Opcache;
use PHPUnit\Framework\TestCase;

/**
 * Class CryptoCurrencyCollectionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\CryptoCurrency
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class CryptoCurrencyCollectionTest extends TestCase
{

    private const BASEPATH = APPLICATION . '/ui';
    private const CRYPTOCURRENCY_IMAGES = 'images/cryptocoins';

    public function testCollectionHas(): void
    {
        $image = new Svg(self::CRYPTOCURRENCY_IMAGES, new Opcache(null, self::BASEPATH));

        $collection = new CryptoCurrencyCollection(
            $image,
            new CryptoCurrencyConfig([
                CryptoCurrency\Btc::class,
                CryptoCurrency\Doge::class,
            ])
        );

        $this->assertSame(true, $collection->has('bitcoin'));
        $this->assertSame(true, $collection->has('dogecoin'));
        $this->assertSame(false, $collection->has('whatevercoin'));
    }

    public function testCollectionAvailable(): void
    {
        $image = new Svg(self::CRYPTOCURRENCY_IMAGES, new Opcache(null, self::BASEPATH));

        $collection = new CryptoCurrencyCollection(
            $image,
            new CryptoCurrencyConfig([
                CryptoCurrency\Xmr::class,
                CryptoCurrency\Ltc::class,
            ])
        );

        $available = $collection->available();
        $this->assertInstanceOf(CryptoCurrency\Xmr::class, $available[0]);
        $this->assertInstanceOf(CryptoCurrency\Ltc::class, $available[1]);
    }

    public function testCollectionGet(): void
    {
        $image = new Svg(self::CRYPTOCURRENCY_IMAGES, new Opcache(null, self::BASEPATH));

        $collection = new CryptoCurrencyCollection(
            $image,
            new CryptoCurrencyConfig([
                CryptoCurrency\Bcn::class,
                CryptoCurrency\Dash::class,
            ])
        );

        $this->assertInstanceOf(CryptoCurrency\Bcn::class, $collection->get('bytecoin'));
        $this->assertInstanceOf(CryptoCurrency\Dash::class, $collection->get('dash'));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cryptocurrency whatevercoin not set up');
        $collection->get('whatevercoin');
    }
}
