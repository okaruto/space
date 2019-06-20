<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;
use Okaruto\Space\Business\CryptoCurrency;
use Okaruto\Space\Business\Token;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Config\Config;
use Okaruto\Space\Config\CryptoCurrencyConfig;
use Okaruto\Space\Config\CryptonatorConfig;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Config\MailConfig;
use Okaruto\Space\Config\MailIdentityConfig;
use Okaruto\Space\Config\TimeoutConfig;
use Okaruto\Space\Config\TokenTypeConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class ConfigTest extends TestCase
{

    public function testValidConfig(): void
    {
        $config = new Config([
            Config::KEY_ADMIN => [
                AdminConfig::KEY_KEY => 'supersecretkey',
                AdminConfig::KEY_VERIFY_TOKENS => true,
            ],
            Config::KEY_MAIL => $mailConfig = [
                MailConfig::KEY_TRANSPORT => \Swift_Transport_SendmailTransport::class,
                MailConfig::KEY_FROM => [
                    MailIdentityConfig::KEY_NAME => '',
                    MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
                ],
                MailConfig::KEY_TO => [
                    MailIdentityConfig::KEY_NAME => '',
                    MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
                ],
            ],
            Config::KEY_TIMEOUTS => [
                TimeoutConfig::KEY_NEW => 'PT5M',
                TimeoutConfig::KEY_CANCELLED => 'PT15M',
                TimeoutConfig::KEY_PAID => 'PT30M',
            ],
            Config::KEY_CRYPTONATOR => [
                CryptonatorConfig::KEY_MERCHANT_ID => 'merchant-id',
                CryptonatorConfig::KEY_MERCHANT_SECRET => 'xxx-secret-hash-xxx',
            ],
            Config::KEY_CURRENCY => InvoiceCurrencyValue::INVOICE_CURRENCY_USD,
            Config::KEY_CRYPTO_CURRENCIES => [
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
            Config::KEY_TOKEN_TYPES => [
                Token\Type\OneWeek::class => 1.86,
                Token\Type\OneMonth::class => 6.00,
                Token\Type\ThreeMonths::class => 16.00,
                Token\Type\SixMonths::class => 28.00,
                Token\Type\OneYear::class => 52.00,
                Token\Type\TwoYears::class => 94.00,
                Token\Type\Lifetime::class => 500.00,
            ],
            Config::KEY_LAYOUT => [
                LayoutConfig::KEY_EMAIL => 'example@example.com',
                LayoutConfig::KEY_TOR_DOMAIN => 'xyz.onion',
                LayoutConfig::KEY_COMPANY => 'testcompany',
                LayoutConfig::KEY_SLOGAN => 'where testing rocks',
                LayoutConfig::KEY_YEAR => 2019,
                LayoutConfig::KEY_PUBLIC_KEY_ID => '0xAAAAAAAAAAAAAAAA',
                LayoutConfig::KEY_PUBLIC_KEY => '--- Test Public Key ---',
            ],
        ]);

        $this->assertInstanceOf(AdminConfig::class, $config->admin());
        $this->assertInstanceOf(MailConfig::class, $config->mail());
        $this->assertInstanceOf(TimeoutConfig::class, $config->timeouts());
        $this->assertInstanceOf(CryptonatorConfig::class, $config->cryptonator());
        $this->assertInstanceOf(InvoiceCurrencyValue::class, $config->currency());
        $this->assertInstanceOf(CryptoCurrencyConfig::class, $config->cryptoCurrencies());
        $this->assertInstanceOf(TokenTypeConfig::class, $config->tokenTypes());
        $this->assertInstanceOf(LayoutConfig::class, $config->layout());
    }
}
