<?php

declare(strict_types=1);

namespace Okaruto\Space\Middleware;

use Lmc\HttpConstants\Header;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class XhrMiddleware
 *
 * @package   Okaruto\Space\Middleware
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class XhrMiddleware
{

    public const ATTRIBUTE = 'isXhr';

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
        return $next($request->withAttribute(
            self::ATTRIBUTE,
            $request->getHeaderLine(Header::X_REQUESTED_WITH) === 'XMLHttpRequest'
        ), $response);
    }
}
