<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler\Order;

use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use Okaruto\Cryptonator\Invoice;
use Okaruto\Cryptonator\Values\InvoiceStatusValue;
use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyCollection;
use Okaruto\Space\Business\Invoice\InvoiceManager;
use Okaruto\Space\Business\Order\Order;
use Okaruto\Space\Business\Order\OrderManager;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Handler\NotFoundHandler;
use Okaruto\Space\Interval\PaidTimeOutInterval;
use Okaruto\Space\Middleware\XhrMiddleware;
use Okaruto\Space\PostParams;
use Okaruto\Space\Renderer\RendererInterface;
use Okaruto\Space\Router\RouteArguments;
use Okaruto\Space\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class OrderViewHandler
 *
 * @package   Okaruto\Space\Handler\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class OrderViewHandler
{

    /** @var RendererInterface */
    private $renderer;

    /** @var TokenManager */
    private $tokenManager;

    /** @var OrderManager */
    private $orderManager;

    /** @var InvoiceManager */
    private $invoiceManager;

    /** @var CryptoCurrencyCollection */
    private $cryptoCurrencyCollection;

    /** @var NotFoundHandler */
    private $notFoundHandler;

    /** @var RouterInterface */
    private $router;

    /** @var PaidTimeOutInterval */
    private $paidTimeOutInterval;

    /**
     * OrderViewHandler constructor.
     *
     * @param RendererInterface        $renderer
     * @param TokenManager             $tokenManager
     * @param OrderManager             $orderManager
     * @param InvoiceManager           $invoiceManager
     * @param CryptoCurrencyCollection $cryptoCurrencyCollection
     * @param NotFoundHandler          $notFoundHandler
     * @param RouterInterface          $router
     * @param PaidTimeOutInterval      $paidTimeOutInterval
     */
    public function __construct(
        RendererInterface $renderer,
        TokenManager $tokenManager,
        OrderManager $orderManager,
        InvoiceManager $invoiceManager,
        CryptoCurrencyCollection $cryptoCurrencyCollection,
        NotFoundHandler $notFoundHandler,
        RouterInterface $router,
        PaidTimeOutInterval $paidTimeOutInterval
    ) {
        $this->renderer = $renderer;
        $this->tokenManager = $tokenManager;
        $this->orderManager = $orderManager;
        $this->invoiceManager = $invoiceManager;
        $this->cryptoCurrencyCollection = $cryptoCurrencyCollection;
        $this->notFoundHandler = $notFoundHandler;
        $this->router = $router;
        $this->paidTimeOutInterval = $paidTimeOutInterval;
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

        $order = $this->orderManager->order(
            $request->getAttribute(RouteArguments::ARGUMENT_ORDER_ID)
        );

        if (!$order->valid()) {
            $response = $this->notFoundHandler->__invoke($request, $response);
        } else {
            $invoice = $this->invoiceManager->invoice($order);

            if (!$invoice->valid()) {
                $response = $this->notFoundHandler->__invoke($request, $response);
            } else {
                $response = $request->getMethod() === RequestMethodInterface::METHOD_POST
                    ? $this->post($request, $response, $order, $invoice)
                    : $this->get($response, $order, $invoice);
            }
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @param Order             $order
     * @param Invoice           $invoice
     *
     * @return ResponseInterface
     */
    private function get(
        ResponseInterface $response,
        Order $order,
        Invoice $invoice
    ): ResponseInterface {

        $paid = $invoice->details()->status() === InvoiceStatusValue::INVOICE_STATUS_PAID;

        $timeout = (new \DateTimeImmutable())->diff(
            $paid
                ? $invoice->dates()->dateTime()->add($this->paidTimeOutInterval)
                : $invoice->dates()->expires()
        );

        $token = $this->tokenManager->token($order->tokenId());

        return $this->renderer->render(
            $response,
            'order/view/index',
            [
                'status' => $order->unionStatus($invoice),
                'data' => [
                    'name' => $token->type()->name(),
                    'order' => $order,
                    'invoice' => $invoice,
                    'token' => $paid ? $token : null,
                    'coin' => $this->cryptoCurrencyCollection->get($invoice->checkout()->currency()),
                    'timeout' => (bool)$timeout->invert ? '00:00' : $timeout->format('%I:%S'),
                ],
            ]
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @param Order                  $order
     * @param Invoice                $invoice
     *
     * @return ResponseInterface
     */
    private function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Order $order,
        Invoice $invoice
    ): ResponseInterface {

        $body = (array)$request->getParsedBody();
        $xhr = $request->getAttribute(XhrMiddleware::ATTRIBUTE, false) === true;

        if ($xhr) {
            $response = $response->withStatus(
                (new PostParams\StatusChange($body, $order->unionStatus($invoice)))->changed()
                    ? StatusCodeInterface::STATUS_OK
                    : StatusCodeInterface::STATUS_NO_CONTENT
            );
        } else {
            if ((new PostParams\RemoveOrder((array)$request->getParsedBody()))->requested()
                && $order->unionStatus($invoice) === InvoiceStatusValue::INVOICE_STATUS_PAID
            ) {
                $this->orderManager->clear($order);

                $response = $this->router->redirect(
                    $response,
                    'order/view',
                    StatusCodeInterface::STATUS_SEE_OTHER,
                    [],
                    [],
                    $request
                );
            } else {
                $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
            }
        }

        return $response;
    }
}
