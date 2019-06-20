<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use League\Plates\Engine;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Renderer\Extensions\LayoutVariablesExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class LayoutVariablesExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class LayoutVariablesExtensionTest extends TestCase
{

    public function testExtensionRegister(): void
    {
        $extension = new LayoutVariablesExtension(
            new LayoutVariables(
                new LayoutConfig(
                    [
                        LayoutConfig::KEY_EMAIL => '',
                        LayoutConfig::KEY_TOR_DOMAIN => '',
                        LayoutConfig::KEY_COMPANY => '',
                        LayoutConfig::KEY_SLOGAN => '',
                        LayoutConfig::KEY_YEAR => 2019,
                        LayoutConfig::KEY_PUBLIC_KEY_ID => '',
                        LayoutConfig::KEY_PUBLIC_KEY => '',
                    ]
                )
            )
        );

        $function = (new Engine())->loadExtension($extension)->getFunction('lv');

        $this->assertSame(
            [$extension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testLayoutVariableExtensionInvocation(): void
    {
        $extension = new LayoutVariablesExtension(
            new LayoutVariables(
                new LayoutConfig(
                    [
                        LayoutConfig::KEY_EMAIL => '',
                        LayoutConfig::KEY_TOR_DOMAIN => '',
                        LayoutConfig::KEY_COMPANY => '',
                        LayoutConfig::KEY_SLOGAN => '',
                        LayoutConfig::KEY_YEAR => 2019,
                        LayoutConfig::KEY_PUBLIC_KEY_ID => '',
                        LayoutConfig::KEY_PUBLIC_KEY => '',
                    ]
                )
            )
        );

        $this->assertInstanceOf(LayoutVariables::class, $extension->__invoke());
    }

}
