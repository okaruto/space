<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Image\ImageInterface;
use Okaruto\Space\Router\RouterInterface;
use Okaruto\Space\Translation\AvailableLocales;

/**
 * Class LanguageSelectExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class LanguageSelectExtension implements ExtensionInterface
{

    /** @var ImageInterface */
    private $flags;

    /** @var AvailableLocales */
    private $availableLocales;

    /** @var RouterInterface */
    private $router;

    /** @var LayoutVariables */
    private $layoutVariables;

    /**
     * SvgExtension constructor.
     *
     * @param ImageInterface   $flags
     * @param AvailableLocales $availableLocales
     * @param RouterInterface  $router
     * @param LayoutVariables  $layoutVariables
     */
    public function __construct(
        ImageInterface $flags,
        AvailableLocales $availableLocales,
        RouterInterface $router,
        LayoutVariables $layoutVariables
    ) {
        $this->flags = $flags;
        $this->availableLocales = $availableLocales;
        $this->router = $router;
        $this->layoutVariables = $layoutVariables;
    }

    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('languageSelect', [$this, '__invoke']);
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        $languages = [];

        if ($this->layoutVariables->hasRouteName() && $this->layoutVariables->hasRouteArguments()) {
            $currentLocale = $this->layoutVariables->language();
            $currentLanguage = null;

            foreach ($this->availableLocales->locales() as $locale) {
                $language = [
                    'shorthand' => $locale,
                    'url' => $this->router->path(
                        $this->layoutVariables->routeName(),
                        array_merge(
                            $this->layoutVariables->routeArguments(),
                            ['language' => $locale]
                        )
                    ),
                    'image' => $this->flags->inline($locale),
                ];

                if ($locale === $currentLocale) {
                    array_unshift($languages, $language);
                } else {
                    array_push($languages, $language);
                }
            }
        }

        return $languages;
    }
}
