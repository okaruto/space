<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Order;

use Okaruto\Cryptonator\Invoice;
use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;
use Symfony\Component\Console\Exception\LogicException;

/**
 * Class Order
 *
 * @package   Okaruto\Space\Business\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class Order
{

    public const STATUS_NEW = 'new';
    public const STATUS_CLEAR = 'clear';

    /** @var string */
    private $id;

    /** @var string */
    private $created;

    /** @var string */
    private $updated;

    /** @var string */
    private $status;

    /** @var float */
    private $price;

    /** @var InvoiceCurrencyValue */
    private $currency;

    /** @var int */
    private $tokenId;

    /** @var null|bool */
    private $valid;

    /**
     * Order constructor.
     *
     * @param string $id
     * @param string $created
     * @param string $updated
     * @param string $status
     * @param float  $price
     * @param string $currency
     * @param int    $tokenId
     */
    public function __construct(
        string $id,
        string $created,
        string $updated,
        string $status,
        float $price,
        string $currency,
        int $tokenId
    ) {
        $this->id = $id;
        $this->created = $created;
        $this->updated = $updated;
        $this->status = $status;
        $this->price = $price;
        $this->currency = new InvoiceCurrencyValue($currency);
        $this->tokenId = $tokenId;
    }

    /**
     * @param bool $throw
     *
     * @return bool
     */
    public function valid(bool $throw = false): bool
    {
        if ($this->valid === null) {
            $this->valid = !empty($this->id)
                && !empty($this->created)
                && !empty($this->price)
                && $this->currency->valid()
                && !empty($this->tokenId);
        }

        if ($throw && !$this->valid) {
            throw new \LogicException('Trying to use invalid order');
        }

        return $this->valid;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        $this->valid(true);

        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function created(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->created);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function updated(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->updated);
    }

    /**
     * @return string
     */
    public function status(): string
    {
        $this->valid(true);

        return $this->status;
    }

    /**
     * @return float
     */
    public function price(): float
    {
        $this->valid(true);

        return $this->price;
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        $this->valid(true);

        return $this->currency->value();
    }

    /**
     * @return int
     */
    public function tokenId(): int
    {
        $this->valid(true);

        return $this->tokenId;
    }

    /**
     * @param Invoice $invoice
     *
     * @return string
     */
    public function unionStatus(Invoice $invoice): string
    {
        if ($this->id() !== $invoice->orderId()) {
            throw new LogicException(
                sprintf(
                    'Trying to use an invoice with different order id: "%s" vs. "%s"',
                    $this->id(),
                    $invoice->orderId()
                )
            );
        }

        return ($invoice->valid() && $this->status() === self::STATUS_NEW)
            ? $invoice->details()->status()
            : $this->status();
    }
}
