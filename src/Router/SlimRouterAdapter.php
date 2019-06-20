<?php

declare(strict_types=1);

namespace Okaruto\Space\Router;

use Lmc\HttpConstants\Header;
use Okaruto\Space\Middleware\RouteArgumentsMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 *
 * @package   Okaruto\Space\Router
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class SlimRouterAdapter implements RouterInterface
{

    /** @var \Slim\Interfaces\RouterInterface */
    private $router;

    /**
     * SlimRouterProxy constructor.
     *
     * @param \Slim\Interfaces\RouterInterface $router
     */
    public function __construct(\Slim\Interfaces\RouterInterface $router)
    {

        $this->router = $router;
    }

    /**
     * @param string                      $route
     * @param array                       $routeArgs
     * @param array                       $queryParams
     * @param ServerRequestInterface|null $request
     *
     * @return string
     */
    public function path(
        string $route,
        array $routeArgs = [],
        array $queryParams = [],
        ?ServerRequestInterface $request = null
    ): string {
        return $this->router->pathFor(
            $route,
            $request !== null
                ? array_merge($request->getAttribute(RouteArgumentsMiddleware::ARGUMENTS, []), $routeArgs)
                : $routeArgs,
            $queryParams
        );
    }

    /**
     * @param ResponseInterface           $response
     * @param string                      $route
     * @param int                         $status
     * @param array                       $routeArgs
     * @param array                       $queryParams
     * @param ServerRequestInterface|null $request
     *
     * @return ResponseInterface
     */
    public function redirect(
        ResponseInterface $response,
        string $route,
        int $status = 307,
        array $routeArgs = [],
        array $queryParams = [],
        ?ServerRequestInterface $request = null
    ): ResponseInterface {

        return $response->withStatus($status)
                        ->withHeader(Header::LOCATION, $this->path($route, $routeArgs, $queryParams, $request));
    }
}
