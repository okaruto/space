<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\CryptonatorConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class CryptonatorConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class CryptonatorConfigTest extends TestCase
{

    public function testValidCryptonatorConfig(): void
    {
        $cryptonatorConfigStrings = new CryptonatorConfig([
            CryptonatorConfig::KEY_MERCHANT_ID => 'merchant-id',
            CryptonatorConfig::KEY_MERCHANT_SECRET => 'xxx-secret-hash-xxx',
        ]);

        $this->assertSame('merchant-id', $cryptonatorConfigStrings->merchantId());
        $this->assertSame('xxx-secret-hash-xxx', $cryptonatorConfigStrings->merchantSecret());

        $cryptonatorConfigIntegers = new CryptonatorConfig([
            CryptonatorConfig::KEY_MERCHANT_ID => 12345,
            CryptonatorConfig::KEY_MERCHANT_SECRET => 678901,
        ]);

        $this->assertSame('12345', $cryptonatorConfigIntegers->merchantId());
        $this->assertSame('678901', $cryptonatorConfigIntegers->merchantSecret());
    }

    public function testCryptonatorConfigAllKeysEmpty(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('CryptonatorConfig array key "merchantId" or "merchantSecret" is empty');

        new CryptonatorConfig([
            CryptonatorConfig::KEY_MERCHANT_ID => false,
            CryptonatorConfig::KEY_MERCHANT_SECRET => 0,
        ]);
    }

    public function testCryptonatorConfigMerchantIdEmpty(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('CryptonatorConfig array key "merchantId" or "merchantSecret" is empty');

        new CryptonatorConfig([
            CryptonatorConfig::KEY_MERCHANT_ID => 0,
            CryptonatorConfig::KEY_MERCHANT_SECRET => 'xxx-secret-hash-xxx',
        ]);
    }

    public function testCryptonatorConfigMerchantSecretEmpty(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('CryptonatorConfig array key "merchantId" or "merchantSecret" is empty');

        new CryptonatorConfig([
            CryptonatorConfig::KEY_MERCHANT_ID => 'merchant-id',
            CryptonatorConfig::KEY_MERCHANT_SECRET => false,
        ]);
    }
}
