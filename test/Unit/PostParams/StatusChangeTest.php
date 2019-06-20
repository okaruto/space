<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\PostParams;

use Okaruto\Space\PostParams\StatusChange;
use PHPUnit\Framework\TestCase;

/**
 * Class StatusChangeTest
 *
 * @package   Okaruto\Space\Tests\Unit\PostParams
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class StatusChangeTest extends TestCase
{

    public function testStatusChangeDifferent(): void
    {
        $statusChange = new StatusChange(['status' => 'unpaid'], 'paid');

        $this->assertSame(true, $statusChange->changed());
    }

    public function testStatusChangeSame(): void
    {
        $statusChange = new StatusChange(['status' => 'unpaid'], 'unpaid');

        $this->assertSame(false, $statusChange->changed());
    }

    public function testStatusChangeMissing(): void
    {
        $statusChange = new StatusChange([], 'unpaid');

        $this->assertSame(false, $statusChange->changed());
    }
}
