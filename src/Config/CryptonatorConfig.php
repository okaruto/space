<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

/**
 * Class CryptonatorConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class CryptonatorConfig extends AbstractConfig
{

    public const KEY_MERCHANT_ID = 'merchantId';
    public const KEY_MERCHANT_SECRET = 'merchantSecret';

    protected const MANDATORY_KEYS = [
        self::KEY_MERCHANT_ID,
        self::KEY_MERCHANT_SECRET,
    ];

    /**
     * CryptonatorConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        if (empty($this->merchantId()) || empty($this->merchantSecret())) {
            throw new \LogicException(
                sprintf(
                    'CryptonatorConfig array key "%s" or "%s" is empty',
                    self::KEY_MERCHANT_ID,
                    self::KEY_MERCHANT_SECRET
                )
            );
        }
    }

    /**
     * @return string
     */
    public function merchantId(): string
    {
        return (string)$this->config[self::KEY_MERCHANT_ID];
    }

    /**
     * @return string
     */
    public function merchantSecret(): string
    {
        return (string)$this->config[self::KEY_MERCHANT_SECRET];
    }
}
