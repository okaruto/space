<?php

declare(strict_types=1);

namespace Okaruto\Space\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface RouterInterface
 *
 * @package   Okaruto\Space\Router
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface RouterInterface
{

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
    ): string;

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
    ): ResponseInterface;
}
