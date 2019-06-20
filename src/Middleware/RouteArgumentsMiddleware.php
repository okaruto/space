<?php

declare(strict_types=1);

namespace Okaruto\Space\Middleware;

use Okaruto\Space\Container\LayoutVariables;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Route;

/**
 * Class RouteArgumentsMiddleware
 *
 * @package   Okaruto\Space\Middleware
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class RouteArgumentsMiddleware
{

    public const ARGUMENTS = 'routeArguments';

    /** @var LayoutVariables */
    private $layoutVariables;

    /**
     * RouteArgumentsMiddleware constructor.
     *
     * @param LayoutVariables $layoutVariables
     */
    public function __construct(LayoutVariables $layoutVariables)
    {
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

        /** @var Route|null $route */
        $route = $request->getAttribute('route', null);

        $routeArguments = [];

        if ($route !== null) {
            $routeArguments = $route->getArguments();
            $this->layoutVariables->setRouteArguments($routeArguments);
        }

        return $next(
            $request->withAttribute(self::ARGUMENTS, $routeArguments),
            $response
        );
    }
}
