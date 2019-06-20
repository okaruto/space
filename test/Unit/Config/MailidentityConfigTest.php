<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\MailIdentityConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class MailidentityConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class MailidentityConfigTest extends TestCase
{

    public function testMailIdentityConfig(): void
    {
        $mailIdentityConfig = new MailIdentityConfig(
            [
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
                MailIdentityConfig::KEY_NAME => 'Unit Test',
            ]
        );

        $this->assertSame('unit@test.com', $mailIdentityConfig->email());
        $this->assertSame('Unit Test', $mailIdentityConfig->name());
    }
}
