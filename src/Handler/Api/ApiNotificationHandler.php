<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler\Api;

use Fig\Http\Message\StatusCodeInterface;
use Okaruto\Cryptonator\MerchantApiInterface;
use Okaruto\Space\Business\Invoice\InvoiceManager;
use Okaruto\Space\Business\Order\OrderManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ApiNotificationHandler
 *
 * @package   Okaruto\Space\Handler\Api
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class ApiNotificationHandler
{

    /** @var MerchantApiInterface */
    private $merchantApi;

    /** @var InvoiceManager */
    private $invoiceManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var OrderManager */
    private $orderManager;

    /**
     * ApiNotificationHandler constructor.
     *
     * @param MerchantApiInterface $merchantApi
     * @param InvoiceManager       $invoiceManager
     * @param OrderManager         $orderManager
     * @param LoggerInterface      $logger
     */
    public function __construct(
        MerchantApiInterface $merchantApi,
        InvoiceManager $invoiceManager,
        OrderManager $orderManager,
        LoggerInterface $logger
    ) {
        $this->merchantApi = $merchantApi;
        $this->invoiceManager = $invoiceManager;
        $this->orderManager = $orderManager;
        $this->logger = $logger;
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

        $success = false;
        $body = $request->getParsedBody();

        if (is_array($body)) {
            $invoice = $this->merchantApi->httpNotificationInvoice($body);

            /**
             * System always returns status ok when order does not exist to prevent Cryptonator API from sending the
             * invoice request again.
             */
            $success = $this->orderManager->order($invoice->orderId())->valid()
                ? $this->invoiceManager->add($invoice)
                : true;
        }

        return $response->withStatus($success
            ? StatusCodeInterface::STATUS_OK
            : StatusCodeInterface::STATUS_BAD_REQUEST);
    }
}
