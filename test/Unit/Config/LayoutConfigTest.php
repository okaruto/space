<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Container\LayoutVariables;
use PHPUnit\Framework\TestCase;

/**
 * Class LayoutConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class LayoutConfigTest  extends TestCase
{
    public function testCompleteKeys(): void
    {

        $layoutConfig = new LayoutConfig($this->defaultKeys());

        $this->assertSame('example@example.com', $layoutConfig->email());
        $this->assertSame('xyz.onion', $layoutConfig->torDomain());
        $this->assertSame('testcompany', $layoutConfig->company());
        $this->assertSame('where testing rocks', $layoutConfig->slogan());
        $this->assertSame(2019, $layoutConfig->year());
        $this->assertSame('0xAAAAAAAAAAAAAAAA', $layoutConfig->publicKeyId());
        $this->assertSame('--- Test Public Key ---', $layoutConfig->publicKey());

    }

    public function testMissingTorDomain(): void
    {

        $layoutConfig = new LayoutConfig(
            array_merge($this->defaultKeys(), [LayoutConfig::KEY_TOR_DOMAIN => false])
        );

        $this->assertSame(false, $layoutConfig->torDomainAvailable());
        $this->expectException(\LogicException::class);
        $layoutConfig->torDomain();

    }

    public function testMissingPublicKey(): void
    {

        $layoutConfig = new LayoutConfig(
            array_merge($this->defaultKeys(), [LayoutConfig::KEY_PUBLIC_KEY => false])
        );

        $this->assertSame(false, $layoutConfig->publicKeyAvailable());
        $this->expectException(\LogicException::class);
        $layoutConfig->publicKey();

    }

    public function testMissingPublicKeyId(): void
    {

        $layoutConfig = new LayoutConfig(
            array_merge($this->defaultKeys(), [LayoutConfig::KEY_PUBLIC_KEY_ID => false])
        );

        $this->assertSame(false, $layoutConfig->publicKeyAvailable());
        $this->expectException(\LogicException::class);
        $layoutConfig->publicKeyId();

    }

    /**
     * @return array
     */
    private function defaultKeys(): array
    {
        return [
            LayoutConfig::KEY_EMAIL => 'example@example.com',
            LayoutConfig::KEY_TOR_DOMAIN => 'xyz.onion',
            LayoutConfig::KEY_COMPANY => 'testcompany',
            LayoutConfig::KEY_SLOGAN => 'where testing rocks',
            LayoutConfig::KEY_YEAR => 2019,
            LayoutConfig::KEY_PUBLIC_KEY_ID => '0xAAAAAAAAAAAAAAAA',
            LayoutConfig::KEY_PUBLIC_KEY => '--- Test Public Key ---',
        ];
    }
}
