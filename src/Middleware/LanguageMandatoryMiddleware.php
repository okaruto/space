<?php

declare(strict_types=1);

namespace Okaruto\Space\Middleware;

use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Handler\NotFoundHandler;
use Okaruto\Space\Router\RouteArguments;
use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LanguageMandatoryMiddleware
 *
 * @package   Okaruto\Space\Middleware
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class LanguageMandatoryMiddleware
{

    public const ATTRIBUTE = 'language';

    /** @var AvailableLocales */
    private $availableLocales;

    /** @var TranslationContainer */
    private $translationContainer;

    /** @var NotFoundHandler */
    private $notFoundHandler;

    /** @var LayoutVariables */
    private $layoutVariables;

    /**
     * LanguageDetectionMiddleware constructor.
     *
     * @param AvailableLocales     $availableLocales
     * @param TranslationContainer $translationContainer
     * @param NotFoundHandler      $notFoundHandler
     * @param LayoutVariables      $layoutVariables
     */
    public function __construct(
        AvailableLocales $availableLocales,
        TranslationContainer $translationContainer,
        NotFoundHandler $notFoundHandler,
        LayoutVariables $layoutVariables
    ) {
        $this->availableLocales = $availableLocales;
        $this->translationContainer = $translationContainer;
        $this->notFoundHandler = $notFoundHandler;
        $this->layoutVariables = $layoutVariables;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {

        $language = $this->language($request);

        if (empty($language)) {
            return $this->notFoundHandler->__invoke($request, $response);
        }

        $this->layoutVariables->updateLanguage($language);
        $this->translationContainer->setTranslation($language);

        return $next($request->withAttribute(self::ATTRIBUTE, $language), $response);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    private function language(ServerRequestInterface $request): string
    {
        $language = '';

        $routeLanguage =
            $request->getAttribute(RouteArgumentsMiddleware::ARGUMENTS)[RouteArguments::ARGUMENT_LANGUAGE] ?? null;

        if ($routeLanguage !== null && $this->availableLocales->available($routeLanguage)) {
            $language = $routeLanguage;
        }

        return $language;
    }
}
