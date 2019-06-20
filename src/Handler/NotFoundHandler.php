<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Juhara\ZzzStream\StringStream;
use Lmc\HttpConstants\Header;
use Negotiation\Accept;
use Negotiation\Negotiator;
use Okaruto\Space\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class NotFoundHandler
 *
 * @package   Okaruto\Space\Handler
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class NotFoundHandler
{

    /** @var RendererInterface */
    private $renderer;

    /** @var Negotiator */
    private $negotiator;

    /**
     * NotFoundHandler constructor.
     *
     * @param RendererInterface $renderer
     * @param Negotiator        $negotiator
     */
    public function __construct(RendererInterface $renderer, Negotiator $negotiator)
    {
        $this->renderer = $renderer;
        $this->negotiator = $negotiator;
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

        $header = $request->getHeaderLine(Header::ACCEPT);

        /** @var Accept $contentType */
        $contentType = $this->negotiator->getBest(
            empty($header) ? 'text/html' : $header,
            ['text/html', 'application/json']
        );

        $response = $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        return strpos($contentType->getNormalizedValue(), 'application/json') === 0
            ? $response->withBody(
                new StringStream(
                    json_encode(['code' => StatusCodeInterface::STATUS_NOT_FOUND, 'message' => 'Not found'])
                )
            )
            : $this->renderer->render(
                $response,
                'error/error',
                ['status' => StatusCodeInterface::STATUS_NOT_FOUND, 'reason' => 'Not Found']
            );
    }
}
