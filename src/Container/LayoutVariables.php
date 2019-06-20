<?php

declare(strict_types=1);

namespace Okaruto\Space\Container;

use Okaruto\Space\Config\LayoutConfig;

/**
 * Class LayoutVariables
 *
 * @package   Okaruto\Space\Container
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class LayoutVariables
{

    /** @var LayoutConfig */
    private $config;

    /** @var string */
    private $language = 'en';

    /** @var string|null */
    private $routeName;

    /** @var array|null */
    private $routeArguments;

    /**
     * LayoutVariables constructor.
     *
     * @param LayoutConfig $layoutConfig
     */
    public function __construct(LayoutConfig $layoutConfig)
    {
        $this->config = $layoutConfig;
    }

    /**
     * @param string $language
     *
     * @return void
     */
    public function updateLanguage(string $language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function language(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function company(): string
    {
        return $this->config->company();
    }

    /**
     * @return bool
     */
    public function torDomainAvailable(): bool
    {
        return $this->config->torDomainAvailable();
    }

    /**
     * @return string
     */
    public function torDomain(): string
    {
        return $this->config->torDomain();
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->config->email();
    }

    /**
     * @return string
     */
    public function slogan(): string
    {
        return $this->config->slogan();
    }

    /**
     * @return string
     */
    public function year(): string
    {
        $year = $this->config->year();
        $now = (int)date('Y');

        return $year < $now ? sprintf('%s - %s', $year, $now) : (string)$year;
    }

    /**
     * @return bool
     */
    public function publicKeyAvailable(): bool
    {
        return $this->config->publicKeyAvailable();
    }

    /**
     * @return string
     */
    public function publicKeyId(): string
    {
        return $this->config->publicKeyId();
    }

    /**
     * @return string
     */
    public function publicKey(): string
    {
        return $this->config->publicKey();
    }

    /**
     * @param null|string $routeName
     *
     * @return void
     */
    public function setRouteName(?string $routeName): void
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function routeName(): string
    {
        if (!$this->hasRouteName()) {
            throw new \LogicException('Trying to access unavailable route name');
        }

        return (string) $this->routeName;
    }

    /**
     * @return bool
     */
    public function hasRouteName(): bool
    {
        return $this->routeName !== null;
    }

    /**
     * @param null|array $routeArguments
     *
     * @return void
     */
    public function setRouteArguments(?array $routeArguments): void
    {
        $this->routeArguments = $routeArguments;
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function routeArguments(): array
    {
        if (!$this->hasRouteArguments()) {
            throw new \LogicException('Trying to access unavailable route arguments');
        }

        return $this->routeArguments ?? [];
    }

    /**
     * @return bool
     */
    public function hasRouteArguments(): bool
    {
        return !empty($this->routeArguments);
    }
}
