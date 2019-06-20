<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Middleware;

use Fig\Http\Message\RequestMethodInterface;
use Juhara\ZzzStream\StringStream;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Middleware\RouteArgumentsMiddleware;
use Okaruto\Space\Router\RouteArguments;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\Router;

/**
 * Class RouteArgumentsMiddlewareTest
 *
 * @package   Okaruto\Space\Tests\Unit\Middleware
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class RouteArgumentsMiddlewareTest extends TestCase
{

    public function testFilledRouteArguments(): void
    {
        $layoutVariables = new LayoutVariables(
            new LayoutConfig(
                [
                    LayoutConfig::KEY_EMAIL => 'example@example.com',
                    LayoutConfig::KEY_TOR_DOMAIN => 'xyz.onion',
                    LayoutConfig::KEY_COMPANY => 'testcompany',
                    LayoutConfig::KEY_SLOGAN => 'where testing rocks',
                    LayoutConfig::KEY_YEAR => 2019,
                    LayoutConfig::KEY_PUBLIC_KEY_ID => '0xAAAAAAAAAAAAAAAA',
                    LayoutConfig::KEY_PUBLIC_KEY => '--- Test Public Key ---',
                ]
            )
        );

        $routeArgumentsMiddleware = new RouteArgumentsMiddleware($layoutVariables);

        $arguments = null;

        $request = new Request(
            RequestMethodInterface::METHOD_GET,
            new Uri('https', 'unit.test', null, '/en/0000-0000-0000/page'),
            new Headers([]),
            [],
            [],
            new StringStream('')
        );

        $router = new Router();
        $router->map(
            [RequestMethodInterface::METHOD_GET],
            '/{' . RouteArguments::ARGUMENT_LANGUAGE . '}/{' . RouteArguments::ARGUMENT_ORDER_ID . '}/page',
            function (ServerRequestInterface $request, Response $response) use (&$arguments) {
                $arguments = $request->getAttribute(RouteArgumentsMiddleware::ARGUMENTS);

                return $response;
            }
        )->add($routeArgumentsMiddleware);

        $dispatchResult = $router->dispatch($request);
        $route = $router->lookupRoute($dispatchResult[1]);
        $route->prepare($request, array_map('urldecode', $dispatchResult[2]));
        $request = $request->withAttribute('route', $route);
        $route->run($request, new Response());

        $expectedRouteArguments = [
            RouteArguments::ARGUMENT_LANGUAGE => 'en',
            RouteArguments::ARGUMENT_ORDER_ID => '0000-0000-0000',
        ];

        $this->assertSame(
            $expectedRouteArguments,
            $arguments
        );

        $this->assertSame($expectedRouteArguments, $layoutVariables->routeArguments());

    }
}
