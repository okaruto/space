<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Lmc\HttpConstants\Header;
use Negotiation\Accept;
use Negotiation\Negotiator;
use Okaruto\Space\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\PhpError;

/**
 * Class ErrorHandler
 *
 * @package   Okaruto\Space\Handler
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class ErrorHandler
{

    /** @var bool */
    private $displayErrorDetails;

    /** @var PhpError */
    private $phpError;

    /** @var Negotiator */
    private $negotiator;

    /** @var RendererInterface */
    private $renderer;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ErrorHandler constructor.
     *
     * @param bool              $displayErrorDetails
     * @param PhpError          $phpError
     * @param Negotiator        $negotiator
     * @param RendererInterface $renderer
     * @param LoggerInterface   $logger
     */
    public function __construct(
        bool $displayErrorDetails,
        PhpError $phpError,
        Negotiator $negotiator,
        RendererInterface $renderer,
        LoggerInterface $logger
    ) {
        $this->displayErrorDetails = $displayErrorDetails;
        $this->phpError = $phpError;
        $this->negotiator = $negotiator;
        $this->renderer = $renderer;
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param \Throwable             $error
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        \Throwable $error
    ): ResponseInterface {
        $header = $request->getHeaderLine(Header::ACCEPT);

        /** @var Accept $contentType */
        $contentType = $this->negotiator->getBest($header, ['text/html']);

        $this->log($error);

        return $this->handleable($contentType)
            ? $this->errorpage($response)
            : $this->phpError->__invoke($request, $response, $error);
    }

    /**
     * @param Accept|null $contentType
     *
     * @return bool
     */
    private function handleable(?Accept $contentType): bool
    {
        return !$this->displayErrorDetails
            && $contentType !== null
            && strpos($contentType->getNormalizedValue(), 'text/html') === 0;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    private function errorpage(ResponseInterface $response): ResponseInterface
    {
        return $this->renderer->render(
            $response->withStatus(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR)
                     ->withHeader('Content-type', 'text/html'),
            'error/error',
            [
                'status' => StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
                'reason' => 'Internal server error',
            ]
        );
    }

    /**
     * @param \Throwable $error
     *
     * @return void
     */
    private function log(\Throwable $error): void
    {
        $content = $this->errorArray($error);

        while ($previous = $error->getPrevious()) {
            $content['previous'][] = $this->errorArray($previous);
        }

        $this->logger->error('Application error', $content);
    }

    /**
     * @param \Throwable $error
     *
     * @return array
     */
    private function errorArray(\Throwable $error): array
    {
        return [
            'type' => get_class($error),
            'code' => $error->getCode(),
            'message' => $error->getMessage(),
            'file' => $error->getFile() . ':' . $error->getLine(),
            'trace' => $error->getTraceAsString(),
        ];
    }
}
