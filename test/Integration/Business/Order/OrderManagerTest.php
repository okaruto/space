<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Integration\Business\Order;

use Okaruto\Cryptonator\Invoice;
use Okaruto\Space\Business\Order\OrderId;
use Okaruto\Space\Business\Order\OrderManager;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Business\Token\TokenValidator;
use Okaruto\Space\Business\Token\Type\OneMonth;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Database\PDOSqliteMemory;
use Okaruto\Space\Tests\MigrateDatebaseTrait;
use Okaruto\Space\Tests\PrepareClientTrait;
use Okaruto\Space\Tests\TokenTypeCollectionTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderManagerTest
 *
 * @package   Okaruto\Space\Tests\Integration\Business\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class OrderManagerTest extends TestCase
{

    use MigrateDatebaseTrait;
    use PrepareClientTrait;
    use TokenTypeCollectionTrait;

    public function testOrderManager(): void
    {
        $pdo = $this->migrateDatabase(new PDOSqliteMemory());
        $tokenManager = $this->createTokenManager([], $pdo);
        $tokenManager->addTokens(['unit0-test0-token-00000;1month']);

        $orderManager = new OrderManager(
            $pdo,
            $tokenManager,
            new OrderId()
        );

        $order = $orderManager->create($tokenManager->available(OneMonth::TYPE));
        $this->assertSame(1, $order->tokenId());

        $order->valid();
        $newOrders = $orderManager->statusNew();
        $newOrder = reset($newOrders);
        $newOrder->valid();
        $this->assertEquals($newOrder, $order);

        $orderManager->clear($newOrder);

        $clearOrders = $orderManager->statusClear();
        $clearOrder = reset($clearOrders);

        $this->assertSame($order->id(), $clearOrder->id());

        $removed = $orderManager->remove($order);
        $this->assertSame(true, $removed);
        $this->assertSame(0, count($orderManager->statusClear()));
    }

    public function testOrderManagerClearFailed(): void
    {
        $pdo = $this->migrateDatabase(new PDOSqliteMemory());
        $tokenManager = $this->createTokenManager([], $pdo);
        $tokenManager->addTokens(['unit0-test0-token-00000;1month']);

        $orderManager = new OrderManager(
            $pdo,
            $tokenManager,
            new OrderId()
        );

        $order = $orderManager->create($tokenManager->available(OneMonth::TYPE));
        $orderManager->remove($order);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageRegExp('/^Could not update status of order/');

        $orderManager->clear($order);

    }

    public function testOrderManagerCreateFailed(): void
    {
        $pdo = $this->migrateDatabase(new PDOSqliteMemory());
        $tokenManager = $this->createTokenManager([], $pdo);
        $tokenManager->addTokens(['unit0-test0-token-00000;1month']);

        $orderManager = new OrderManager(
            $pdo,
            $tokenManager,
            new OrderId()
        );

        $availableToken = $tokenManager->available(OneMonth::TYPE);
        $pdo->exec("DELETE FROM tokens WHERE value = 'unit0-test0-token-00000'");
        $this->assertSame(false, $orderManager->create($availableToken)->valid());
    }

    public function testOrderManagerOrderByInvoice(): void
    {
        $pdo = $this->migrateDatabase(new PDOSqliteMemory());
        $tokenManager = $this->createTokenManager([], $pdo);
        $tokenManager->addTokens(['unit0-test0-token-00000;1month']);

        $orderManager = new OrderManager(
            $pdo,
            $tokenManager,
            new OrderId()
        );

        $order = $orderManager->create($tokenManager->available(OneMonth::TYPE));

        $orderByInvoice = $orderManager->orderByInvoice(new Invoice(
            [
                'invoice_id' => 'ffffff',
                'invoice_created' => '1543839194',
                'invoice_expires' => '1543850177',
                'invoice_amount' => '6.66',
                'invoice_currency' => 'usd',
                'invoice_status' => 'unpaid',
                'invoice_url' => 'https://unit.test/url',
                'order_id' => $order->id(),
                'checkout_address' => 'unit-test-checkout-address',
                'checkout_amount' => '0.002082833526',
                'checkout_currency' => 'bitcoin',
                'date_time' => '1543839194',
            ]
        ));

        $orderByInvoice->valid();

        $this->assertEquals($order, $orderByInvoice);
    }

    /**
     * @param array $requestContainer
     * @param \PDO  $pdo
     *
     * @return TokenManager
     */
    protected function createTokenManager(
        array $requestContainer,
        \PDO $pdo
    ): TokenManager {
        return new TokenManager(
            $this->migrateDatabase($pdo),
            $this->tokenTypeCollection(),
            new TokenValidator(
                new AdminConfig([AdminConfig::KEY_KEY => 'key', AdminConfig::KEY_VERIFY_TOKENS => false]),
                $this->client([], $requestContainer))
        );
    }
}
