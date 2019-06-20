<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;

/**
 * Class Config
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class Config extends AbstractConfig
{

    public const KEY_ADMIN = 'admin';
    public const KEY_MAIL = 'mail';
    public const KEY_TIMEOUTS = 'timeouts';
    public const KEY_CRYPTONATOR = 'cryptonator';
    public const KEY_CURRENCY = 'currency';
    public const KEY_CRYPTO_CURRENCIES = 'cryptoCurrencies';
    public const KEY_TOKEN_TYPES = 'tokenTypes';
    public const KEY_LAYOUT = 'layout';

    protected const MANDATORY_KEYS = [
        self::KEY_ADMIN,
        self::KEY_MAIL,
        self::KEY_TIMEOUTS,
        self::KEY_CRYPTONATOR,
        self::KEY_CURRENCY,
        self::KEY_CRYPTO_CURRENCIES,
        self::KEY_TOKEN_TYPES,
        self::KEY_LAYOUT,
    ];

    /**
     * @return AdminConfig
     */
    public function admin(): AdminConfig
    {
        return new AdminConfig($this->config[self::KEY_ADMIN]);
    }

    /**
     * @return MailConfig
     */
    public function mail(): MailConfig
    {
        return new MailConfig($this->config[self::KEY_MAIL]);
    }

    /**
     * @return TimeoutConfig
     */
    public function timeouts(): TimeoutConfig
    {
        return new TimeoutConfig($this->config[self::KEY_TIMEOUTS]);
    }

    /**
     * @return CryptonatorConfig
     */
    public function cryptonator(): CryptonatorConfig
    {
        return new CryptonatorConfig($this->config[self::KEY_CRYPTONATOR]);
    }

    /**
     * @return InvoiceCurrencyValue
     */
    public function currency(): InvoiceCurrencyValue
    {
        return new InvoiceCurrencyValue($this->config[self::KEY_CURRENCY]);
    }

    /**
     * @return CryptoCurrencyConfig
     */
    public function cryptoCurrencies(): CryptoCurrencyConfig
    {
        return new CryptoCurrencyConfig($this->config[self::KEY_CRYPTO_CURRENCIES]);
    }

    /**
     * @return TokenTypeConfig
     */
    public function tokenTypes(): TokenTypeConfig
    {
        return new TokenTypeConfig($this->config[self::KEY_TOKEN_TYPES]);
    }

    /**
     * @return LayoutConfig
     */
    public function layout(): LayoutConfig
    {
        return new LayoutConfig($this->config[self::KEY_LAYOUT]);
    }
}
