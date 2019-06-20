<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use Fig\Http\Message\RequestMethodInterface;
use Juhara\ZzzStream\StringStream;
use League\Plates\Engine;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Image\Svg;
use Okaruto\Space\Middleware\RouteArgumentsMiddleware;
use Okaruto\Space\Middleware\RouteNameMiddleware;
use Okaruto\Space\Opcache\Opcache;
use Okaruto\Space\Renderer\Extensions\LanguageSelectExtension;
use Okaruto\Space\Router\RouteArguments;
use Okaruto\Space\Router\SlimRouterAdapter;
use Okaruto\Space\Translation\AvailableLocales;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\Router;

/**
 * Class LanguageSelectExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class LanguageSelectExtensionTest extends TestCase
{

    private const IMAGE_DIR = APPLICATION . '/data/unit_extension_flags';
    private const IMAGE_FILE_TEMPLATE = self::IMAGE_DIR . '/#LOCALE#.svg';

    private const LANGUAGE_DIR = APPLICATION . '/data/unit_languages';
    private const LANGUAGE_FILE_EN = self::LANGUAGE_DIR . '/en.php';
    private const LANGUAGE_FILE_RU = self::LANGUAGE_DIR . '/ru.php';
    private const LANGUAGE_FILE_DE = self::LANGUAGE_DIR . '/de.php';

    /** @var AvailableLocales */
    private $availableLocales;

    /** @var LanguageSelectExtension */
    private $languageSelectExtension;

    public function setUp()
    {
        mkdir(self::LANGUAGE_DIR, 0777, true);

        file_put_contents(
            self::LANGUAGE_FILE_EN,
            <<<'EOT'
<?php 
return [];
EOT
        );

        file_put_contents(
            self::LANGUAGE_FILE_RU,
            <<<'EOT'
<?php 
return [];
EOT
        );

        file_put_contents(
            self::LANGUAGE_FILE_DE,
            <<<'EOT'
<?php 
return [];
EOT
        );

        $availableLocales = new AvailableLocales(self::LANGUAGE_DIR, 'en');

        mkdir(self::IMAGE_DIR, 0777, true);

        file_put_contents(
            str_replace('#LOCALE#', 'en', self::IMAGE_FILE_TEMPLATE),
            <<<'EOT'
<svg width="100" height="100"><circle cx="50" cy="50" r="50" fill="black"></circle></svg>
EOT
        );

        file_put_contents(
            str_replace('#LOCALE#', 'ru', self::IMAGE_FILE_TEMPLATE),
            <<<'EOT'
<svg width="50" height="50"><circle cx="50" cy="25" r="25" fill="black"></circle></svg>
EOT
        );

        file_put_contents(
            str_replace('#LOCALE#', 'de', self::IMAGE_FILE_TEMPLATE),
            <<<'EOT'
<svg width="25" height="25"><circle cx="50" cy="12.5" r="12.5" fill="black"></circle></svg>
EOT
        );

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

        $this->languageSelectExtension = new LanguageSelectExtension(
            new Svg('', new Opcache(null, self::IMAGE_DIR)),
            $availableLocales,
            $this->createRouter($layoutVariables),
            $layoutVariables
        );
    }

    public function tearDown()
    {
        unlink(self::LANGUAGE_FILE_EN);
        unlink(self::LANGUAGE_FILE_RU);
        unlink(self::LANGUAGE_FILE_DE);
        rmdir(self::LANGUAGE_DIR);

        unlink(str_replace('#LOCALE#', 'en', self::IMAGE_FILE_TEMPLATE));
        unlink(str_replace('#LOCALE#', 'ru', self::IMAGE_FILE_TEMPLATE));
        unlink(str_replace('#LOCALE#', 'de', self::IMAGE_FILE_TEMPLATE));
        rmdir(self::IMAGE_DIR);
    }

    private function createRouter(LayoutVariables $layoutVariables): SlimRouterAdapter
    {
        $routeArgumentsMiddleware = new RouteArgumentsMiddleware($layoutVariables);
        $routeNameMiddleware = new RouteNameMiddleware($layoutVariables);

        $request = new Request(
            RequestMethodInterface::METHOD_GET,
            new Uri('https', 'unit.test', null, '/ru/0000-0000-0000/page'),
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
        )->setName('routeunittest')
               ->add($routeNameMiddleware)
               ->add($routeArgumentsMiddleware);

        $dispatchResult = $router->dispatch($request);
        $route = $router->lookupRoute($dispatchResult[1]);
        $route->prepare($request, array_map('urldecode', $dispatchResult[2]));
        $request = $request->withAttribute('route', $route);
        $route->run($request, new Response());

        return new SlimRouterAdapter($router);
    }

    public function testExtensionRegister(): void
    {
        $function = (new Engine())->loadExtension($this->languageSelectExtension)->getFunction('languageSelect');

        $this->assertSame(
            [$this->languageSelectExtension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testLanguageSelectorExtension(): void
    {
        $languageSelectExtension = $this->languageSelectExtension;

        $this->assertSame(
            [
                [
                    'shorthand' => 'en',
                    'url' => '/en/0000-0000-0000/page',
                    'image' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9ImJsYWNrIj48L2NpcmNsZT48L3N2Zz4='
                ],
                [
                    'shorthand' => 'de',
                    'url' => '/de/0000-0000-0000/page',
                    'image' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUiIGhlaWdodD0iMjUiPjxjaXJjbGUgY3g9IjUwIiBjeT0iMTIuNSIgcj0iMTIuNSIgZmlsbD0iYmxhY2siPjwvY2lyY2xlPjwvc3ZnPg==',
                ],
                [
                    'shorthand' => 'ru',
                    'url' => '/ru/0000-0000-0000/page',
                    'image' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiPjxjaXJjbGUgY3g9IjUwIiBjeT0iMjUiIHI9IjI1IiBmaWxsPSJibGFjayI+PC9jaXJjbGU+PC9zdmc+',
                ],
            ],
            $languageSelectExtension()
        );
    }
}
