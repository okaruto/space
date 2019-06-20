<?php

declare(strict_types=1);

namespace Okaruto\Space\Mail;

/**
 * Class HouseKeepingMail
 *
 * @package   Okaruto\Space\Mail
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class HouseKeepingMail extends AbstractMail
{

    /**
     * @param string $message
     *
     * @return bool
     */
    public function send(string $message): bool
    {
        $this->message->setSubject('Housekeeping run');
        $this->message->setBody($message);

        return $this->mailer->send($this->message) === 1;
    }
}
