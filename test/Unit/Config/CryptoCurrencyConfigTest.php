<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use League\Container\Container;
use Okaruto\Space\Business\CryptoCurrency;
use Okaruto\Space\Config\Config;
use Okaruto\Space\Config\CryptoCurrencyConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class CryptoCurrencyConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class CryptoCurrencyConfigTest extends TestCase
{

    public function testCryptoCurrencyConfig(): void
    {
        $cryptocCurrencyConfig = new CryptoCurrencyConfig([
            CryptoCurrency\Btc::class,
            CryptoCurrency\Bch::class,
            CryptoCurrency\Eth::class,
            CryptoCurrency\Etc::class,
            CryptoCurrency\Ltc::class,
            CryptoCurrency\Dash::class,
            CryptoCurrency\Doge::class,
            CryptoCurrency\Xmr::class,
            CryptoCurrency\Zec::class,
            CryptoCurrency\Bcn::class,
            CryptoCurrency\Ppc::class,
            CryptoCurrency\Emc::class,
        ]);

        $this->assertSame(
            [
                CryptoCurrency\Btc::class,
                CryptoCurrency\Bch::class,
                CryptoCurrency\Eth::class,
                CryptoCurrency\Etc::class,
                CryptoCurrency\Ltc::class,
                CryptoCurrency\Dash::class,
                CryptoCurrency\Doge::class,
                CryptoCurrency\Xmr::class,
                CryptoCurrency\Zec::class,
                CryptoCurrency\Bcn::class,
                CryptoCurrency\Ppc::class,
                CryptoCurrency\Emc::class,
            ],
            $cryptocCurrencyConfig->all()
        );
    }

    public function testCryptoCurrencyConfigEmptyList(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No crypto currencies configured');

        new CryptoCurrencyConfig([]);
    }

    public function testCryptoCurrencyConfigInvalidCurrencies(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Some configured crypto currencies ("Okaruto\Space\Config\Config", "League\Container\Container"'
        );

        new CryptoCurrencyConfig([
            CryptoCurrency\Btc::class,
            Config::class,
            CryptoCurrency\Bch::class,
            Container::class,
        ]);
    }
}
