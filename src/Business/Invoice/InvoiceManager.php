<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Invoice;

use Okaruto\Cryptonator\Invoice;
use Okaruto\Cryptonator\MerchantApiInterface;
use Okaruto\Cryptonator\Values\InvoiceStatusValue;
use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyInterface;
use Okaruto\Space\Business\Order\Order;
use Okaruto\Space\Business\Token\AvailableTokenInterface;
use Okaruto\Space\Database\PrepareStatementTrait;

/**
 * Class InvoiceManager
 *
 * @package   Okaruto\Space\Business\Invoice
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class InvoiceManager
{

    use PrepareStatementTrait;

    private const SQL_INSERT_INVOICE = <<<'EOT'
INSERT INTO invoices (
   order_id,
   invoice_id,
   invoice_created,
   invoice_expires,
   invoice_amount,
   invoice_currency,
   invoice_status,
   invoice_url,
   checkout_address,
   checkout_amount,
   checkout_currency,
   date_time
) VALUES (
   :orderId,
   :invoiceId,
   :invoiceCreated,
   :invoiceExpires,
   :invoiceAmount,
   :invoiceCurrency,
   :invoiceStatus,
   :invoiceUrl,
   :checkoutAddress,
   :checkoutAmount,
   :checkoutCurrency,
   :dateTime
);
EOT;

    private const SQL_SELECT_CURRENT_INVOICE_BY_ORDER_ID = <<<'EOT'
SELECT
   order_id,
   invoice_id,
   invoice_created,
   invoice_expires,
   invoice_amount,
   invoice_currency,
   invoice_status,
   invoice_url,
   checkout_address,
   checkout_amount,
   checkout_currency,
   date_time 
FROM invoices_current WHERE order_id = :orderId;
EOT;

    private const SQL_SELECT_CURRENT_INVOICES_BY_STATUS = <<<'EOT'
SELECT
   order_id,
   invoice_id,
   invoice_created,
   invoice_expires,
   invoice_amount,
   invoice_currency,
   invoice_status,
   invoice_url,
   checkout_address,
   checkout_amount,
   checkout_currency,
   date_time 
FROM invoices_current WHERE invoice_status = :status;
EOT;

    /** @var MerchantApiInterface */
    private $merchantApi;

    public function __construct(\PDO $pdo, MerchantApiInterface $merchantApi)
    {
        $this->pdo = $pdo;
        $this->merchantApi = $merchantApi;
    }

    /**
     * @param AvailableTokenInterface $availableToken
     * @param CryptoCurrencyInterface $coin
     * @param Order                   $order
     *
     * @return Invoice
     */
    public function create(
        AvailableTokenInterface $availableToken,
        CryptoCurrencyInterface $coin,
        Order $order
    ) {

        $invoice = $this->merchantApi->createInvoice(
            $availableToken->name(),
            $coin->code(),
            $availableToken->price(),
            $availableToken->currency(),
            $order->id()
        );

        if ($invoice->valid() && $invoice->full()) {
            $this->add($invoice);
        }

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     *
     * @return bool
     */
    public function add(Invoice $invoice): bool
    {
        $success = false;

        if ($invoice->valid() && $invoice->full()) {
            $details = $invoice->details();
            $dates = $invoice->dates();
            $checkout = $invoice->checkout();

            $statement = $this->prepareStatement(self::SQL_INSERT_INVOICE);

            $success = $statement->execute(
                [
                    'orderId' => $invoice->orderId(),
                    'invoiceId' => $details->identifier(),
                    'invoiceCreated' => $dates->created()->getTimestamp(),
                    'invoiceExpires' => $dates->expires()->getTimestamp(),
                    'invoiceAmount' => $details->amount(),
                    'invoiceCurrency' => $details->currency(),
                    'invoiceStatus' => $details->status(),
                    'invoiceUrl' => $details->url(),
                    'checkoutAddress' => $checkout->address(),
                    'checkoutAmount' => $checkout->amount(),
                    'checkoutCurrency' => $checkout->currency(),
                    'dateTime' => $dates->dateTime()->getTimestamp(),
                ]
            );
        }

        return $success;
    }

    /**
     * @param Order $order
     *
     * @return Invoice
     */
    public function invoice(Order $order): Invoice
    {
        $statement = $this->prepareStatement(self::SQL_SELECT_CURRENT_INVOICE_BY_ORDER_ID);

        $data = [];

        if ($statement->execute(['orderId' => $order->id()])) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($result)) {
                $data = reset($result);
            }
        }

        return new Invoice($data);
    }

    /**
     * @return Invoice[]
     */
    public function statusCancelled(): array
    {
        return $this->byStatus(new InvoiceStatusValue(InvoiceStatusValue::INVOICE_STATUS_CANCELLED));
    }

    /**
     * @return Invoice[]
     */
    public function statusPaid(): array
    {
        return $this->byStatus(new InvoiceStatusValue(InvoiceStatusValue::INVOICE_STATUS_PAID));
    }

    /**
     * @param InvoiceStatusValue $status
     *
     * @return Invoice[]
     */
    private function byStatus(InvoiceStatusValue $status): array
    {
        $statement = $this->prepareStatement(self::SQL_SELECT_CURRENT_INVOICES_BY_STATUS);

        $data = [];

        if ($statement->execute(['status' => $status->value()])) {
            foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $invoiceRow) {
                $data[] = new Invoice($invoiceRow);
            }
        }

        return $data;
    }
}
