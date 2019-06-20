<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Mail;

use Okaruto\Space\Mail\HouseKeepingMail;
use PHPUnit\Framework\TestCase;

/**
 * Class HouseKeepingMailTest
 *
 * @package   Okaruto\Space\Tests\Unit\Mail
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class HouseKeepingMailTest extends TestCase
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

        $houseKeepingMail = new HouseKeepingMail(
            new \Swift_Mailer($transport),
            $message
        );

        $houseKeepingMail->send('test message');

        $messages = $transport->getSpool()->getSpooledMessages();

        $this->assertSame(1, count($messages));

        /** @var \Swift_Mime_SimpleMessage $message */
        $message = reset($messages);

        $this->assertSame('test message', $message->getBody());
        $this->assertSame('Housekeeping run', $message->getSubject());
    }
}
