<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

/**
 * Class MailConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class MailConfig extends AbstractConfig
{

    public const KEY_TRANSPORT = 'transport';
    public const KEY_FROM = 'from';
    public const KEY_TO = 'to';
    public const KEY_SERVER = 'server';
    public const KEY_PORT = 'port';
    public const KEY_USERNAME = 'username';
    public const KEY_PASSWORD = 'password';

    protected const MANDATORY_KEYS = [
        self::KEY_TRANSPORT,
        self::KEY_FROM,
        self::KEY_TO,
    ];

    public function __construct(array $config)
    {
        parent::__construct($config);

        if (!is_a($this->transport(), \Swift_Transport::class, true)) {
            throw new \LogicException(
                sprintf(
                    'MailConfig array key "%s" class "%s" does not implement interface %s',
                    self::KEY_TRANSPORT,
                    $this->transport(),
                    \Swift_Transport::class
                )
            );
        }
    }

    /**
     * @return string
     */
    public function transport(): string
    {
        return (string) $this->config[self::KEY_TRANSPORT];
    }

    /**
     * @param \Swift_Message $message
     *
     * @return \Swift_Message
     */
    public function from(\Swift_Message $message): \Swift_Message
    {
        $from = new MailIdentityConfig($this->config[self::KEY_FROM]);

        return $message->addFrom($from->email(), empty($from->name()) ? null : $from->name());
    }

    /**
     * @param \Swift_Message $message
     *
     * @return \Swift_Message
     */
    public function to(\Swift_Message $message): \Swift_Message
    {
        $to = new MailIdentityConfig($this->config[self::KEY_TO]);

        return $message->addTo($to->email(), empty($to->name()) ? null : $to->name());
    }

    /**
     * @return string
     */
    public function server(): string
    {
        return $this->config[self::KEY_SERVER];
    }

    /**
     * @return int
     */
    public function port(): int
    {
        return (int) ($this->config[self::KEY_PORT] ?? 25);
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function username(): string
    {
        if (!$this->usernameAndPasswordAvailable()) {
            throw new \LogicException('Trying to access unavailable mail username');
        }

        return $this->config[self::KEY_USERNAME];
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function password(): string
    {
        if (!$this->usernameAndPasswordAvailable()) {
            throw new \LogicException('Trying to access unavailable mail password');
        }

        return $this->config[self::KEY_PASSWORD];
    }

    /**
     * @return bool
     */
    public function usernameAndPasswordAvailable(): bool
    {
        return isset($this->config[self::KEY_USERNAME])
            && is_string($this->config[self::KEY_USERNAME])
            && isset($this->config[self::KEY_PASSWORD])
            && is_string($this->config[self::KEY_PASSWORD]);
    }
}
