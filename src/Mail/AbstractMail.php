<?php

declare(strict_types=1);

namespace Okaruto\Space\Mail;

/**
 * Class AbstractMail
 *
 * @package   Okaruto\Space\Mail
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractMail
{

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var \Swift_Message */
    protected $message;

    /**
     * AbstractMail constructor.
     *
     * @param \Swift_Mailer  $mailer
     * @param \Swift_Message $message
     */
    public function __construct(\Swift_Mailer $mailer, \Swift_Message $message)
    {
        $this->mailer = $mailer;
        $this->message = $message;
    }
}
