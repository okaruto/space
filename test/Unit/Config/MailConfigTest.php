<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\MailConfig;
use Okaruto\Space\Config\MailIdentityConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class MailConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class MailConfigTest extends TestCase
{

    public function testValidMailConfig(): void
    {
        $mailConfig = new MailConfig([
            MailConfig::KEY_TRANSPORT => \Swift_Transport_SendmailTransport::class,
            MailConfig::KEY_FROM => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_TO => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_SERVER => 'mail.unit.test',
            MailConfig::KEY_PORT => 25,
            MailConfig::KEY_USERNAME => 'user',
            MailConfig::KEY_PASSWORD => 'p4$$w0rd',
        ]);

        $this->assertSame(\Swift_Transport_SendmailTransport::class, $mailConfig->transport());
        $this->assertInstanceOf(\Swift_Message::class, $mailConfig->from(new \Swift_Message()));
        $this->assertInstanceOf(\Swift_Message::class, $mailConfig->to(new \Swift_Message()));
        $this->assertSame('mail.unit.test', $mailConfig->server());
        $this->assertSame(25, $mailConfig->port());
        $this->assertSame('user', $mailConfig->username());
        $this->assertSame('p4$$w0rd', $mailConfig->password());

    }

    public function testMinimalMailconfig(): void
    {
        $mailConfig = new MailConfig([
            MailConfig::KEY_TRANSPORT => \Swift_Transport_SendmailTransport::class,
            MailConfig::KEY_FROM => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_TO => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
        ]);

        $this->assertSame(25, $mailConfig->port());
        $this->assertSame(false, $mailConfig->usernameAndPasswordAvailable());
    }

    public function testMailConfigMissingUsername(): void
    {
        $mailConfig = new MailConfig([
            MailConfig::KEY_TRANSPORT => \Swift_Transport_SendmailTransport::class,
            MailConfig::KEY_FROM => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_TO => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_PASSWORD => 'supersecret',
        ]);

        $this->assertSame(false, $mailConfig->usernameAndPasswordAvailable());
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to access unavailable mail username');

        $mailConfig->username();
    }

    public function testMailConfigMissingPassword(): void
    {
        $mailConfig = new MailConfig([
            MailConfig::KEY_TRANSPORT => \Swift_Transport_SendmailTransport::class,
            MailConfig::KEY_FROM => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_TO => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_USERNAME => 'username',
        ]);

        $this->assertSame(false, $mailConfig->usernameAndPasswordAvailable());
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to access unavailable mail password');

        $mailConfig->password();
    }

    public function testMaiConfigWrongTransport(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'MailConfig array key "transport" class "StdClass" does not implement interface Swift_Transport'
        );

        new MailConfig([
            MailConfig::KEY_TRANSPORT => \StdClass::class,
            MailConfig::KEY_FROM => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
            MailConfig::KEY_TO => [
                MailIdentityConfig::KEY_NAME => '',
                MailIdentityConfig::KEY_EMAIL => 'unit@test.com',
            ],
        ]);
    }
}
