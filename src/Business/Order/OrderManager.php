<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Order;

use Okaruto\Cryptonator\Invoice;
use Okaruto\Space\Business\Token\AvailableTokenInterface;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Database\PrepareStatementTrait;

/**
 * Class OrderManager
 *
 * @package   Okaruto\Space\Business\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class OrderManager
{

    use PrepareStatementTrait;

    private const SQL_INSERT_NEW_ORDER = <<<'EOT'
INSERT INTO orders (id, price, currency, token_id)
VALUES (:id, :price, :currency, :tokenId);
EOT;

    private const SQL_SELECT_ORDER_BY_ID = <<<'EOT'
SELECT id, created, updated, status, price, currency, token_id
FROM orders
WHERE id = :id;
EOT;

    private const SQL_SELECT_ORDER_BY_STATUS = <<<'EOT'
SELECT id, created, updated, status, price, currency, token_id
FROM orders
WHERE status = :status
EOT;

    private const SQL_UPDATE_ORDER_STATUS_TO_CLEAR = <<<'EOT'
UPDATE orders
SET status = 'clear'
WHERE id = :id;
EOT;

    private const SQL_DELETE_ORDER_BY_ID = <<<'EOT'
DELETE FROM orders WHERE id = :id;
EOT;

    /** @var TokenManager */
    private $tokenManager;

    /** @var OrderId */
    private $orderId;

    /**
     * OrderManager constructor.
     *
     * @param \PDO         $pdo
     * @param TokenManager $tokenManager
     * @param OrderId      $orderId
     */
    public function __construct(\PDO $pdo, TokenManager $tokenManager, OrderId $orderId)
    {
        $this->pdo = $pdo;
        $this->tokenManager = $tokenManager;
        $this->orderId = $orderId;
    }

    /**
     * @param AvailableTokenInterface $availableToken
     *
     * @return Order
     */
    public function create(AvailableTokenInterface $availableToken): Order
    {
        $order = null;

        $token = $this->tokenManager->randomToken($availableToken);

        if ($token->valid()) {
            $statement = $this->prepareStatement(self::SQL_INSERT_NEW_ORDER);

            $orderId = $this->orderId->id();

            $order = $statement->execute([
                'id' => $orderId,
                'price' => $availableToken->price(),
                'currency' => $availableToken->currency(),
                'tokenId' => $token->id(),
            ]) ? $this->order($orderId) : null;
        }

        return $order !== null
            ? $order
            : $this->fromRow([]);
    }

    /**
     * @param string $id
     *
     * @return Order
     */
    public function order(string $id): Order
    {
        $orderRow = [];
        $statement = $this->prepareStatement(self::SQL_SELECT_ORDER_BY_ID);

        if ($statement->execute(['id' => $id])) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                $orderRow = reset($result);
            }
        }

        return $this->fromRow($orderRow);
    }

    /**
     * @param Invoice $invoice
     *
     * @return Order
     */
    public function orderByInvoice(Invoice $invoice): Order
    {
        return $this->order($invoice->orderId());
    }

    /**
     * @param Order $order
     *
     * @return void
     * @throws \RuntimeException
     */
    public function clear(Order $order): void
    {
        $statement = $this->prepareStatement(self::SQL_UPDATE_ORDER_STATUS_TO_CLEAR);

        $existingOrder = $this->order($order->id());

        if (!$existingOrder->valid() || !$statement->execute(['id' => $existingOrder->id()])) {
            throw new \RuntimeException(
                sprintf(
                    'Could not update status of order "%s" to "clear"',
                    $order->id()
                )
            );
        }
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function remove(Order $order): bool
    {
        return $this->prepareStatement(self::SQL_DELETE_ORDER_BY_ID)->execute(['id' => $order->id()]);
    }

    /**
     * @return Order[]
     */
    public function statusNew(): array
    {
        return $this->byStatus(Order::STATUS_NEW);
    }

    /**
     * @return Order[]
     */
    public function statusClear(): array
    {
        return $this->byStatus(Order::STATUS_CLEAR);
    }

    /**
     * @param string $status
     *
     * @return Order[]
     */
    private function byStatus(string $status): array
    {
        $statement = $this->prepareStatement(self::SQL_SELECT_ORDER_BY_STATUS);

        $data = [];

        if ($statement->execute(['status' => $status])) {
            $data = array_map([$this, 'fromRow'], $statement->fetchAll(\PDO::FETCH_ASSOC));
        }

        return $data;
    }

    /**
     * @param array $row
     *
     * @return Order
     */
    private function fromRow(array $row): Order
    {
        return new Order(
            (string)($row['id'] ?? ''),
            (string)($row['created'] ?? ''),
            (string)($row['updated'] ?? ''),
            (string)($row['status'] ?? ''),
            (float)($row['price'] ?? 0.00),
            (string)($row['currency'] ?? ''),
            (int)($row['token_id'] ?? 0)
        );
    }
}
