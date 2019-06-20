<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use League\Plates\Engine;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Renderer\Extensions\UrlExtension;
use Okaruto\Space\Router\SlimRouterAdapter;
use PHPUnit\Framework\TestCase;
use Slim\Router;

/**
 * Class UrlExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class UrlExtensionTest extends TestCase
{

    public function testExtensionRegister(): void
    {
        $extension = new UrlExtension(
            new SlimRouterAdapter(new Router()),
            new LayoutVariables(
                new LayoutConfig(
                    [
                        LayoutConfig::KEY_EMAIL => '',
                        LayoutConfig::KEY_TOR_DOMAIN => '',
                        LayoutConfig::KEY_COMPANY => '',
                        LayoutConfig::KEY_SLOGAN => '',
                        LayoutConfig::KEY_YEAR => 0,
                        LayoutConfig::KEY_PUBLIC_KEY_ID => '',
                        LayoutConfig::KEY_PUBLIC_KEY => '',
                    ]
                )
            )
        );
        $function = (new Engine())->loadExtension($extension)->getFunction('url');

        $this->assertSame(
            [$extension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testUrlForValidRoute(): void
    {
        $router = new Router();

        $router->map(
            ['GET'],
            '/testPath/{routeArgument}/',
            function () {
            }
        )->setName('test_path_route');

        $urlExtension = new UrlExtension(
            new SlimRouterAdapter($router),
            new LayoutVariables(
                new LayoutConfig(
                    [
                        LayoutConfig::KEY_EMAIL => '',
                        LayoutConfig::KEY_TOR_DOMAIN => '',
                        LayoutConfig::KEY_COMPANY => '',
                        LayoutConfig::KEY_SLOGAN => '',
                        LayoutConfig::KEY_YEAR => 0,
                        LayoutConfig::KEY_PUBLIC_KEY_ID => '',
                        LayoutConfig::KEY_PUBLIC_KEY => '',
                    ]
                )
            )
        );

        $this->assertSame(
            '/testPath/example/?query=parameter',
            $urlExtension->__invoke(
                'test_path_route',
                ['routeArgument' => 'example'],
                ['query' => 'parameter']
            )
        );
    }

    public function testUrlForValidRouteAutoLanguage(): void
    {
        $router = new Router();

        $router->map(
            ['GET'],
            '/testPath/{language}/{routeArgument}/',
            function () {
            }
        )->setName('test_path_route_auto_language');

        $layoutVariables = new LayoutVariables(
            new LayoutConfig(
                [
                    LayoutConfig::KEY_EMAIL => '',
                    LayoutConfig::KEY_TOR_DOMAIN => '',
                    LayoutConfig::KEY_COMPANY => '',
                    LayoutConfig::KEY_SLOGAN => '',
                    LayoutConfig::KEY_YEAR => 0,
                    LayoutConfig::KEY_PUBLIC_KEY_ID => '',
                    LayoutConfig::KEY_PUBLIC_KEY => '',
                ]
            )
        );

        $layoutVariables->updateLanguage('es');

        $urlExtension = new UrlExtension(
            new SlimRouterAdapter($router),
            $layoutVariables
        );

        $this->assertSame(
            '/testPath/es/example/?query=parameter',
            $urlExtension->__invoke(
                'test_path_route_auto_language',
                ['routeArgument' => 'example'],
                ['query' => 'parameter']
            )
        );


    }
}
