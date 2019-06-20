<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Container;

use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Container\LayoutVariables;
use PHPUnit\Framework\TestCase;

/**
 * Class LayoutVariablesTest
 *
 * @package   Okaruto\Space\Tests\Container
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class LayoutVariablesTest extends TestCase
{

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

    public function testCompleteKeys(): void
    {

        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $this->assertSame('example@example.com', $layoutVariables->email());
        $this->assertSame('xyz.onion', $layoutVariables->torDomain());
        $this->assertSame('testcompany', $layoutVariables->company());
        $this->assertSame('where testing rocks', $layoutVariables->slogan());
        $this->assertSame('2019', $layoutVariables->year());
        $this->assertSame('0xAAAAAAAAAAAAAAAA', $layoutVariables->publicKeyId());
        $this->assertSame('--- Test Public Key ---', $layoutVariables->publicKey());

    }

    public function testDefaultLanguage(): void
    {

        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $this->assertSame('en', $layoutVariables->language());

    }

    public function testUpdateLanguage(): void
    {

        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $this->assertSame('en', $layoutVariables->language());
        $layoutVariables->updateLanguage('jp');
        $this->assertSame('jp', $layoutVariables->language());

    }

    public function testMissingTorDomain(): void
    {

        $layoutVariables = new LayoutVariables(new LayoutConfig(
            array_merge($this->defaultKeys(), [LayoutConfig::KEY_TOR_DOMAIN => false])
        ));

        $this->assertSame(false, $layoutVariables->torDomainAvailable());
        $this->expectException(\LogicException::class);
        $layoutVariables->torDomain();

    }

    public function testMissingPublicKey(): void
    {

        $layoutVariables = new LayoutVariables(new LayoutConfig(
            array_merge($this->defaultKeys(), [LayoutConfig::KEY_PUBLIC_KEY => false])
        ));

        $this->assertSame(false, $layoutVariables->publicKeyAvailable());
        $this->expectException(\LogicException::class);
        $layoutVariables->publicKey();

    }

    public function testMissingPublicKeyId(): void
    {

        $layoutVariables = new LayoutVariables(new LayoutConfig(
            array_merge($this->defaultKeys(), [LayoutConfig::KEY_PUBLIC_KEY_ID => false])
        ));

        $this->assertSame(false, $layoutVariables->publicKeyAvailable());
        $this->expectException(\LogicException::class);
        $layoutVariables->publicKeyId();

    }

    public function testRouteArgumentsSet(): void
    {
        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $layoutVariables->setRouteArguments(['language' => 'en']);

        $this->assertSame(true, $layoutVariables->hasRouteArguments());
        $this->assertSame(['language' => 'en'], $layoutVariables->routeArguments());
    }

    public function testRouteArgumentsMissing(): void
    {
        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $this->assertSame(false, $layoutVariables->hasRouteArguments());
        $this->expectException(\LogicException::class);
        $layoutVariables->routeArguments();
    }

    public function testRouteNameSet(): void
    {
        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $layoutVariables->setRouteName('unittest');

        $this->assertSame(true, $layoutVariables->hasRouteName());
        $this->assertSame('unittest', $layoutVariables->routeName());
    }

    public function testRouteNameMissing(): void
    {
        $layoutVariables = new LayoutVariables(new LayoutConfig($this->defaultKeys()));

        $this->assertSame(false, $layoutVariables->hasRouteName());
        $this->expectException(\LogicException::class);
        $layoutVariables->routeName();
    }

}
