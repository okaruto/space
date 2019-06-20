<?php

declare(strict_types=1);

namespace Okaruto\Space\Mail;

use Okaruto\Space\PostParams\ContactFields;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Contact
 *
 * @package   Okaruto\Space\Mail
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class ContactMail extends AbstractMail
{

    /**
     * @param ContactFields $contactFields
     *
     * @return bool
     */
    public function send(ContactFields $contactFields): bool
    {
        $success = false;

        if ($contactFields->valid()) {
            $this->message->setSubject($contactFields->subject());
            $this->message->setBody($contactFields->email() . PHP_EOL . PHP_EOL . $contactFields->message());
            $this->message->setReplyTo($contactFields->email());
            $success = $this->mailer->send($this->message) === 1;
        }

        return $success;
    }
}
