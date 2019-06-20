<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler\Order;

use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use Juhara\ZzzStream\StringStream;
use Lmc\HttpConstants\Header;
use Okaruto\Cryptonator\Invoice;
use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyCollection;
use Okaruto\Space\Business\Invoice\InvoiceManager;
use Okaruto\Space\Business\Order\OrderManager;
use Okaruto\Space\Business\Token\AvailableToken;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Handler\NotFoundHandler;
use Okaruto\Space\Middleware\RouteArgumentsMiddleware;
use Okaruto\Space\Middleware\XhrMiddleware;
use Okaruto\Space\Renderer\RendererInterface;
use Okaruto\Space\Router\RouteArguments;
use Okaruto\Space\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class OrderCreateHandler
 *
 * @package   Okaruto\Space\Handler\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class OrderCreateHandler
{

    /** @var RendererInterface */
    private $renderer;

    /** @var RouterInterface */
    private $router;

    /** @var TokenTypeCollection */
    private $tokenTypeCollection;

    /** @var TokenManager */
    private $tokenManager;

    /** @var CryptoCurrencyCollection */
    private $cryptoCurrencyCollection;

    /** @var OrderManager */
    private $orderManager;

    /** @var InvoiceManager */
    private $invoiceManager;

    /** @var NotFoundHandler */
    private $notFoundHandler;

    /**
     * OrderCreateHandler constructor.
     *
     * @param RendererInterface        $renderer
     * @param RouterInterface          $router
     * @param TokenTypeCollection      $tokenTypeCollection
     * @param TokenManager             $tokenManager
     * @param CryptoCurrencyCollection $cryptoCurrencyCollection
     * @param OrderManager             $orderManager
     * @param InvoiceManager           $invoiceManager
     */
    public function __construct(
        RendererInterface $renderer,
        RouterInterface $router,
        TokenTypeCollection $tokenTypeCollection,
        TokenManager $tokenManager,
        CryptoCurrencyCollection $cryptoCurrencyCollection,
        OrderManager $orderManager,
        InvoiceManager $invoiceManager
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->tokenTypeCollection = $tokenTypeCollection;
        $this->tokenManager = $tokenManager;
        $this->cryptoCurrencyCollection = $cryptoCurrencyCollection;
        $this->orderManager = $orderManager;
        $this->invoiceManager = $invoiceManager;
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

        $tokenType = $request->getAttribute(RouteArgumentsMiddleware::ARGUMENTS)[RouteArguments::ARGUMENT_TOKEN_TYPE];

        return $this->tokenTypeCollection->has($tokenType)
            ? ($request->getMethod() === RequestMethodInterface::METHOD_POST
                ? $this->post($request, $response, $this->baseContent($tokenType))
                : $this->renderer->render($response, 'order/create/index', $this->baseContent($tokenType))
            )
            : $this->notFoundHandler->__invoke($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $content
     *
     * @return ResponseInterface
     */
    private function post(ServerRequestInterface $request, ResponseInterface $response, array $content
    ): ResponseInterface
    {

        $body = (array)$request->getParsedBody();
        $coin = $body['coin'] ?? '';

        /** @var AvailableToken|null $availableToken */
        $availableToken = $content['availableToken'];

        if ($this->cryptoCurrencyCollection->has($coin)
            && $availableToken !== null
            && $availableToken->amount() > 0
        ) {
            $order = $this->orderManager->create($availableToken);

            if ($order->valid()) {
                $invoice = $this->invoiceManager->create(
                    $availableToken,
                    $this->cryptoCurrencyCollection->get($coin),
                    $order
                );

                $response = $invoice->valid()
                    ? $this->successResponse($request, $response, $invoice)
                    : $this->failureResponse($request, $response, $content);
            }
        } else {
            $response = $this->failureResponse($request, $response, $content);
        }

        return $response;
    }

    /**
     * @param string $tokenType
     *
     * @return array
     */
    private function baseContent(string $tokenType): array
    {
        return [
            'availableToken' => $this->tokenManager->available($tokenType),
            'currencies' => $this->cryptoCurrencyCollection->available(),
        ];
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param Invoice                $invoice
     *
     * @return ResponseInterface
     */
    private function successResponse(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Invoice $invoice
    ): ResponseInterface {

        return $request->getAttribute(XhrMiddleware::ATTRIBUTE, false)
            ? $response->withHeader(Header::CONTENT_TYPE, 'application/json')
                       ->withBody(new StringStream(json_encode(['redirect' => $this->redirectUri($request, $invoice)])))
            : $this->router->redirect(
                $response,
                'order/view',
                303,
                $this->redirectRouteArguments($request, $invoice)
            );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $content
     *
     * @return ResponseInterface
     */
    private function failureResponse(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $content
    ): ResponseInterface {

        return $request->getAttribute(XhrMiddleware::ATTRIBUTE, false)
            ? $response->withStatus(StatusCodeInterface::STATUS_NOT_IMPLEMENTED)
                       ->withHeader(Header::CONTENT_TYPE, 'application/json')
            : $this->renderer->render(
                $response,
                'order/create/index',
                array_merge($content, ['currencyUnavailable' => 'form__message--show'])
            );
    }

    /**
     * @param Invoice                $invoice
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    private function redirectUri(ServerRequestInterface $request, Invoice $invoice): string
    {
        return $this->router->path(
            'order/view',
            $this->redirectRouteArguments($request, $invoice)
        );
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param Invoice                $invoice
     *
     * @return array
     */
    private function redirectRouteArguments(ServerRequestInterface $request, Invoice $invoice): array
    {
        return [
            RouteArguments::ARGUMENT_ORDER_ID => $invoice->orderId(),
            RouteArguments::ARGUMENT_LANGUAGE => $request
                ->getAttribute(RouteArgumentsMiddleware::ARGUMENTS)[RouteArguments::ARGUMENT_LANGUAGE],
        ];
    }
}
