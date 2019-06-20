<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

/**
 * Class AdminConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class AdminConfig extends AbstractConfig
{

    public const KEY_KEY = 'key';
    public const KEY_VERIFY_TOKENS = 'verifyTokens';

    protected const MANDATORY_KEYS = [
        self::KEY_KEY,
        self::KEY_VERIFY_TOKENS,
    ];

    public function __construct(array $config)
    {
        parent::__construct($config);

        if (empty($this->key())) {
            throw new \LogicException(
                sprintf(
                    'AdminConfig array key "%s" is empty',
                    AdminConfig::KEY_KEY
                )
            );
        }
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return (string)$this->config[self::KEY_KEY];
    }

    /**
     * @return bool
     */
    public function verifyTokens(): bool
    {
        return (bool)$this->config[self::KEY_VERIFY_TOKENS];
    }
}
