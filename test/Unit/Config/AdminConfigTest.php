<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\AdminConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class AdminConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class AdminConfigTest extends TestCase
{

    public function testValidAdminConfig(): void
    {
        $adminConfig = new AdminConfig([
            AdminConfig::KEY_KEY => '9afde33ac7413552c3968f6b77e9ef44',
            AdminConfig::KEY_VERIFY_TOKENS => true,
        ]);

        $this->assertSame('9afde33ac7413552c3968f6b77e9ef44', $adminConfig->key());
        $this->assertSame(true, $adminConfig->verifyTokens());
    }

    public function testAdminConfigKeyInteger(): void
    {
        $adminConfig = new AdminConfig([
            AdminConfig::KEY_KEY => 1234567890,
            AdminConfig::KEY_VERIFY_TOKENS => true,
        ]);

        $this->assertSame('1234567890', $adminConfig->key());
    }

    public function testAdminConfigKeyEmpty(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('AdminConfig array key "key" is empty');

        new AdminConfig([
            AdminConfig::KEY_KEY => '',
            AdminConfig::KEY_VERIFY_TOKENS => true,
        ]);
    }

    public function testAdminConfigVerifyString(): void
    {
        $adminConfigTruthy = new AdminConfig([
            AdminConfig::KEY_KEY => 1234567890,
            AdminConfig::KEY_VERIFY_TOKENS => 'truthy',
        ]);

        $this->assertSame(true, $adminConfigTruthy->verifyTokens());

        $adminConfigFalse = new AdminConfig([
            AdminConfig::KEY_KEY => 1234567890,
            AdminConfig::KEY_VERIFY_TOKENS => '0',
        ]);

        $this->assertSame(false, $adminConfigFalse->verifyTokens());
    }
}
