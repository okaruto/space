<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\PostParams;

use Okaruto\Space\PostParams\RemoveOrder;
use PHPUnit\Framework\TestCase;

/**
 * Class RemoveOrderTest
 *
 * @package   Okaruto\Space\Tests\Unit\PostParams
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class RemoveOrderTest extends TestCase
{

    public function testRemoveOrderTrue(): void
    {
        $removeOrder = new RemoveOrder(['remove' => 'true']);

        $this->assertSame(true, $removeOrder->requested());
    }

    public function testRemoveOrderNonTrue(): void
    {
        $removeOrder = new RemoveOrder(['remove' => 'bogus']);

        $this->assertSame(false, $removeOrder->requested());
    }

    public function testRemoveOrderMissing(): void
    {
        $removeOrder = new RemoveOrder([]);

        $this->assertSame(false, $removeOrder->requested());
    }
}
