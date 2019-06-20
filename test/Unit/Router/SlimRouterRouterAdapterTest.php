<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Router;

use Fig\Http\Message\RequestMethodInterface;
use Juhara\ZzzStream\StringStream;
use Lmc\HttpConstants\Header;
use Okaruto\Space\Router\SlimRouterAdapter;
use PHPUnit\Framework\TestCase;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Uri;
use Slim\Router;

/**
 * Class SlimRouterRouterAdapterTest
 *
 * @package   Okaruto\Space\Tests\Unit\Router
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class SlimRouterRouterAdapterTest extends TestCase
{

    public function testPath(): void
    {
        $router = new Router();

        $router->map(
            ['GET'],
            '/testPath/{routeArgument}/',
            function () {
            }
        )->setName('test_path_route');

        $routerAdapter = new SlimRouterAdapter($router);

        $this->assertSame(
            $routerAdapter->path(
                'test_path_route',
                ['routeArgument' => 'testArgument'],
                ['query' => 'parameter']
            ),
            '/testPath/testArgument/?query=parameter'
        );
    }

    public function testPathWithRequest(): void
    {
        $router = new Router();

        $router->map(
            ['GET'],
            '/testPath/{routeArgument}/',
            function () {
            }
        )->setName('test_path_route');

        $routerAdapter = new SlimRouterAdapter($router);

        $request = new Request(
            RequestMethodInterface::METHOD_GET,
            new Uri('https', 'unit.test'),
            new Headers([]),
            [],
            [],
            new StringStream('')
        );

        $this->assertSame(
            $routerAdapter->path(
                'test_path_route',
                ['routeArgument' => 'testArgument'],
                ['query' => 'parameter'],
                $request
            ),
            '/testPath/testArgument/?query=parameter'
        );
    }

    public function testRedirect(): void
    {
        $router = new Router();

        $router->map(
            ['GET'],
            '/testRedirect/{routeArgument}/',
            function () {
            }
        )->setName('test_redirect_route');

        $routerAdapter = new SlimRouterAdapter($router);
        $response = new \Slim\Http\Response();

        $response = $routerAdapter->redirect(
            $response,
            'test_redirect_route',
            303,
            ['routeArgument' => 'testArgument'],
            ['query' => 'parameter']
        );

        $this->assertSame(303, $response->getStatusCode());
        $this->assertSame(
            '/testRedirect/testArgument/?query=parameter',
            $response->getHeaderLine(Header::LOCATION)
        );

    }
}
