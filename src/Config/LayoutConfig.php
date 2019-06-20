<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

/**
 * Class LayoutConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class LayoutConfig extends AbstractConfig
{

    public const KEY_COMPANY = 'company';
    public const KEY_TOR_DOMAIN = 'tor';
    public const KEY_EMAIL = 'email';
    public const KEY_SLOGAN = 'slogan';
    public const KEY_YEAR = 'year';
    public const KEY_PUBLIC_KEY_ID = 'publicKeyId';
    public const KEY_PUBLIC_KEY = 'publicKey';

    protected const MANDATORY_KEYS = [
        self::KEY_COMPANY,
        self::KEY_TOR_DOMAIN,
        self::KEY_EMAIL,
        self::KEY_SLOGAN,
        self::KEY_YEAR,
        self::KEY_PUBLIC_KEY_ID,
        self::KEY_PUBLIC_KEY,
    ];

    /**
     * @return string
     */
    public function company(): string
    {
        return $this->config[self::KEY_COMPANY];
    }

    /**
     * @return bool
     */
    public function torDomainAvailable(): bool
    {
        return !empty($this->config[self::KEY_TOR_DOMAIN])
            && is_string($this->config[self::KEY_TOR_DOMAIN]);
    }

    /**
     * @return string
     */
    public function torDomain(): string
    {
        if (!$this->torDomainAvailable()) {
            throw new \LogicException('Trying to access unavailable tor domain');
        }

        return (string)$this->config[self::KEY_TOR_DOMAIN];
    }

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
    public function slogan(): string
    {
        return $this->config[self::KEY_SLOGAN];
    }

    /**
     * @return int
     */
    public function year(): int
    {
        return (int)$this->config[self::KEY_YEAR];
    }

    /**
     * @return bool
     */
    public function publicKeyAvailable(): bool
    {
        return !empty($this->config[self::KEY_PUBLIC_KEY_ID])
            && !empty($this->config[self::KEY_PUBLIC_KEY])
            && is_string($this->config[self::KEY_PUBLIC_KEY_ID])
            && is_string($this->config[self::KEY_PUBLIC_KEY]);
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function publicKeyId(): string
    {
        if (!$this->publicKeyAvailable()) {
            throw new \LogicException('Trying to access unavailable public key id');
        }

        return $this->config[self::KEY_PUBLIC_KEY_ID];
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function publicKey(): string
    {
        if (!$this->publicKeyAvailable()) {
            throw new \LogicException('Trying to access unavailable public key');
        }

        return $this->config[self::KEY_PUBLIC_KEY];
    }
}
