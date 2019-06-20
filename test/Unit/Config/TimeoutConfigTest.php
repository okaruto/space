<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\TimeoutConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeoutConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TimeoutConfigTest extends TestCase
{

    public function testValidTimeouts(): void
    {
        $timeoutConfig = new TimeoutConfig([
            TimeoutConfig::KEY_NEW => 'PT5M',
            TimeoutConfig::KEY_CANCELLED => 'PT15M',
            TimeoutConfig::KEY_PAID => 'PT30M',
        ]);

        $newTimeout = $timeoutConfig->new();
        $this->assertSame(5, $newTimeout->minutes());

        $cancelledTimeout = $timeoutConfig->cancelled();
        $this->assertSame(15, $cancelledTimeout->minutes());

        $paidTimeout = $timeoutConfig->paid();
        $this->assertSame(30, $paidTimeout->minutes());

    }
}
