<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Lmc\HttpConstants\Header;
use Okaruto\Space\Router\RouteArguments;
use Okaruto\Space\Router\RouterInterface;
use Okaruto\Space\Translation\AvailableLocales;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LanguageRedirectHandler
 *
 * @package   Okaruto\Space\Handler
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class LanguageRedirectHandler
{

    /** @var RouterInterface */
    private $router;

    /** @var AvailableLocales */
    private $availableLocales;

    /**
     * LanguageRedirectHandler constructor.
     *
     * @param RouterInterface  $router
     * @param AvailableLocales $availableLocales
     */
    public function __construct(RouterInterface $router, AvailableLocales $availableLocales)
    {
        $this->router = $router;
        $this->availableLocales = $availableLocales;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        return $this->router->redirect(
            $response,
            'index',
            StatusCodeInterface::STATUS_FOUND,
            [RouteArguments::ARGUMENT_LANGUAGE => $this->detect($request)]
        );
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    private function detect(ServerRequestInterface $request): string
    {
        $language = $this->availableLocales->fallbackLocale();
        $languageHeader = $request->getHeaderLine(Header::ACCEPT_LANGUAGE);

        if (!empty($languageHeader)) {
            foreach (explode(',', $languageHeader) as $requestedLanguageDefinition) {
                $requestedLanguageDefinitionParts = explode(';', $requestedLanguageDefinition, 2);
                $languageParts = explode('-', reset($requestedLanguageDefinitionParts), 2);
                $requestedLanguage = reset($languageParts);

                if ($this->availableLocales->available($requestedLanguage ?? null)) {
                    $language = $requestedLanguage;
                    break;
                }
            }
        }

        return $language;
    }
}
