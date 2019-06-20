<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Router\RouterInterface;

/**
 * Class UrlExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class UrlExtension implements ExtensionInterface
{

    /** @var RouterInterface */
    private $router;

    /** @var LayoutVariables */
    private $layoutVariables;

    /**
     * UrlExtension constructor.
     *
     * @param RouterInterface $router
     * @param LayoutVariables $layoutVariables
     */
    public function __construct(RouterInterface $router, LayoutVariables $layoutVariables)
    {
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
        $engine->registerFunction('url', [$this, '__invoke']);
    }

    /**
     * @param string $routeName
     * @param        $routeArgs
     * @param        $queryParams
     *
     * @return string
     */
    public function __invoke(string $routeName, $routeArgs = [], $queryParams = []): string
    {
        return $this->router->path(
            $routeName,
            array_merge(['language' => $this->layoutVariables->language()], $routeArgs),
            $queryParams
        );
    }
}
