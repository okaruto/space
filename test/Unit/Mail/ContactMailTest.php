<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Mail;

use Okaruto\Space\Mail\ContactMail;
use Okaruto\Space\PostParams\ContactFields;
use PHPUnit\Framework\TestCase;

/**
 * Class ContactMailTest
 *
 * @package   Okaruto\Space\Tests\Unit\Mail
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class ContactMailTest extends TestCase
{

    public function testSentMail(): void
    {
        $transport = new \Swift_SpoolTransport(
            new class() extends \Swift_MemorySpool
            {

                public function getSpooledMessages(): array
                {
                    return $this->messages;
                }
            }
        );

        $message = new \Swift_Message();
        $message->setFrom('unit@test.receiver');

        $contactMail = new ContactMail(
            new \Swift_Mailer($transport),
            $message
        );

        $contactMail->send(new ContactFields([
            'email' => 'unit@test.sender',
            'subject' => 'Unit Test',
            'message' => 'Just a unit test message',
        ]));

        $messages = $transport->getSpool()->getSpooledMessages();

        $this->assertSame(1, count($messages));

        /** @var \Swift_Mime_SimpleMessage $message */
        $message = reset($messages);

        $this->assertSame(
            'unit@test.sender' . PHP_EOL . PHP_EOL . 'Just a unit test message',
            $message->getBody()
        );

        $this->assertSame(
            [
                'unit@test.sender' => null,
            ],
            $message->getReplyTo()
        );

    }
}
