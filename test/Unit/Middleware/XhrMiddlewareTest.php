<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Middleware;

use Fig\Http\Message\RequestMethodInterface;
use Juhara\ZzzStream\StringStream;
use Lmc\HttpConstants\Header;
use Okaruto\Space\Middleware\XhrMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

/**
 * Class XhrMiddlewareTest
 *
 * @package   Okaruto\Space\Tests\Unit\Middleware
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class XhrMiddlewareTest extends TestCase
{

    public function testXhrHeaderSet(): void
    {
        $xhrMiddleware = new XhrMiddleware();
        $xhrRequest = null;

        $xhrMiddleware->__invoke(
            new Request(
                RequestMethodInterface::METHOD_GET,
                new Uri('https', 'unit.test'),
                new Headers([Header::X_REQUESTED_WITH => 'XMLHttpRequest']),
                [],
                [],
                new StringStream('')
            ),
            new Response(),
            function (ServerRequestInterface $request, Response $response) use (&$xhrRequest) {
                $xhrRequest = $request->getAttribute(XhrMiddleware::ATTRIBUTE);

                return $response;
            }
        );

        $this->assertSame(true, $xhrRequest);
    }

    public function testXhrHeaderMissing(): void
    {
        $xhrMiddleware = new XhrMiddleware();
        $xhrRequest = null;

        $xhrMiddleware->__invoke(
            new Request(
                RequestMethodInterface::METHOD_GET,
                new Uri('https', 'unit.test'),
                new Headers([]),
                [],
                [],
                new StringStream('')
            ),
            new Response(),
            function (ServerRequestInterface $request, Response $response) use (&$xhrRequest) {
                $xhrRequest = $request->getAttribute(XhrMiddleware::ATTRIBUTE);

                return $response;
            }
        );

        $this->assertSame(false, $xhrRequest);
    }
}
