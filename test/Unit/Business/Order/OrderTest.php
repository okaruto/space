<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Order;

use Okaruto\Cryptonator\Invoice;
use Okaruto\Space\Business\Order\Order;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class OrderTest extends TestCase
{

    public function testValidOrder(): void
    {
        $order = new Order(
            'unit-test-order',
            '2018-12-03 12:13:14',
            '2018-12-03 15:16:17',
            'new',
            6.66,
            'usd',
            1
        );

        $this->assertSame(true, $order->valid());
        $this->assertSame('unit-test-order', $order->id());
        $this->assertSame(6.66, $order->price());
        $this->assertSame('usd', $order->currency());
        $this->assertSame('new', $order->status());
        $this->assertSame(1, $order->tokenId());
        $this->assertEquals(new \DateTimeImmutable('2018-12-03 12:13:14'), $order->created());
        $this->assertEquals(new \DateTimeImmutable('2018-12-03 15:16:17'), $order->updated());

        $invoice = new Invoice(
            [
                'invoice_id' => 'ffffff',
                'invoice_created' => '1543839194',
                'invoice_expires' => '1543850177',
                'invoice_amount' => '6.66',
                'invoice_currency' => 'usd',
                'invoice_status' => 'unpaid',
                'invoice_url' => 'https://unit.test/url',
                'order_id' => 'unit-test-order',
                'checkout_address' => 'unit-test-checkout-address',
                'checkout_amount' => '0.002082833526',
                'checkout_currency' => 'bitcoin',
                'date_time' => '1543839194',
            ]
        );

        $this->assertSame('unpaid', $order->unionStatus($invoice));
    }

    public function testInvalidOrder(): void
    {
        $order = new Order(
            '',
            '2018-12-03 12:13:14',
            '2018-12-03 12:13:14',
            'invalid',
            0.00,
            'yen',
            0
        );

        $this->assertSame(false, $order->valid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to use invalid order');
        $order->valid(true);
    }

    public function testOrderStatusDifferingInvoice(): void
    {
        $order = new Order(
            'unit-test-order',
            '2018-12-03 12:13:14',
            '2018-12-03 15:16:17',
            'new',
            6.66,
            'usd',
            1
        );

        $this->assertSame(true, $order->valid());
        $this->assertSame('unit-test-order', $order->id());
        $this->assertSame(6.66, $order->price());
        $this->assertSame('usd', $order->currency());
        $this->assertSame('new', $order->status());
        $this->assertSame(1, $order->tokenId());
        $this->assertEquals(new \DateTimeImmutable('2018-12-03 12:13:14'), $order->created());
        $this->assertEquals(new \DateTimeImmutable('2018-12-03 15:16:17'), $order->updated());

        $invoice = new Invoice(
            [
                'invoice_id' => 'ffffff',
                'invoice_created' => '1543839194',
                'invoice_expires' => '1543850177',
                'invoice_amount' => '6.66',
                'invoice_currency' => 'usd',
                'invoice_status' => 'unpaid',
                'invoice_url' => 'https://unit.test/url',
                'order_id' => 'unit-test-another-order',
                'checkout_address' => 'unit-test-checkout-address',
                'checkout_amount' => '0.002082833526',
                'checkout_currency' => 'bitcoin',
                'date_time' => '1543839194',
            ]
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Trying to use an invoice with different order id: "unit-test-order" vs. "unit-test-another-order"'
        );
        $order->unionStatus($invoice);
    }
}
