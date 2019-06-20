<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Middleware;

use Fig\Http\Message\RequestMethodInterface;
use Juhara\ZzzStream\StringStream;
use Lmc\HttpConstants\Header;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Handler\NotFoundHandler;
use Okaruto\Space\Middleware\LanguageMandatoryMiddleware;
use Okaruto\Space\Middleware\RouteArgumentsMiddleware;
use Okaruto\Space\Router\RouteArguments;
use Okaruto\Space\Tests\ContainerTrait;
use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Zalas\Injector\PHPUnit\TestCase\ServiceContainerTestCase;

/**
 * Class LanguageMandatoryMiddlewareTest
 *
 * @package   Okaruto\Space\Tests\Unit\Middleware
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class LanguageMandatoryMiddlewareTest extends TestCase implements ServiceContainerTestCase
{

    use ContainerTrait;

    /**
     * @var AvailableLocales
     * @inject
     */
    private $availableLocales;

    /**
     * @var TranslationContainer
     * @inject
     */
    private $translationContainer;

    /**
     * @var NotFoundHandler
     * @inject
     */
    private $notFoundHandler;

    /**
     * @var LayoutVariables
     * @inject
     */
    private $layoutVariables;

    public function testLanguageArgumentExists(): void
    {
        $languageMandatoryMiddleware = new LanguageMandatoryMiddleware(
            $this->availableLocales,
            $this->translationContainer,
            $this->notFoundHandler,
            $this->layoutVariables
        );

        $response = $languageMandatoryMiddleware->__invoke(
            (new Request(
                RequestMethodInterface::METHOD_GET,
                new Uri('https', 'unit.test'),
                new Headers([]),
                [],
                [],
                new StringStream('')
            ))->withAttribute(RouteArgumentsMiddleware::ARGUMENTS, [RouteArguments::ARGUMENT_LANGUAGE => 'en']),
            new Response(),
            function (ServerRequestInterface $request, Response $response) {
                return $response;
            }
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('en', $this->layoutVariables->language());
    }

    public function testLanguageUnsupported(): void
    {
        $languageMandatoryMiddleware = new LanguageMandatoryMiddleware(
            $this->availableLocales,
            $this->translationContainer,
            $this->notFoundHandler,
            $this->layoutVariables
        );

        $response = $languageMandatoryMiddleware->__invoke(
            (new Request(
                RequestMethodInterface::METHOD_GET,
                new Uri('https', 'unit.test'),
                new Headers([Header::ACCEPT => 'application/json']),
                [],
                [],
                new StringStream('')
            ))->withAttribute(RouteArgumentsMiddleware::ARGUMENTS, [RouteArguments::ARGUMENT_LANGUAGE => 'zh']),
            new Response(),
            function (ServerRequestInterface $request, Response $response) {
                return $response;
            }
        );

        $this->assertSame(404, $response->getStatusCode());
    }
}
