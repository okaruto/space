<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Order;

use Okaruto\Space\Business\Order\OrderId;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderIdTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class OrderIdTest extends TestCase
{

    public function testOrderIdGeneration(): void
    {
        $orderId = new OrderId();

        $this->assertRegExp(
            '/^[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}$/',
            $orderId->id()
        );
    }

    public function testValidOrderId(): void
    {
        $this->assertSame(true, (new OrderId())->valid('rFWobvdx-ZpW0-wsoP-BuVV-SV8uLQTSfK1x'));
    }

    public function testInvalidOrderId(): void
    {
        $this->assertSame(false, (new OrderId())->valid('some-invalid-stuff'));
    }
}
