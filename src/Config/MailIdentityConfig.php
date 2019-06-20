<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

/**
 * Class MailIdentityConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class MailIdentityConfig extends AbstractConfig
{

    public const KEY_EMAIL = 'email';
    public const KEY_NAME = 'name';

    protected const MANDATORY_KEYS = [
        self::KEY_EMAIL,
    ];

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->config[self::KEY_EMAIL];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return (string) $this->config[self::KEY_NAME];
    }
}
