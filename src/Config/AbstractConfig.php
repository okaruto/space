<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

use Symfony\Component\Console\Exception\LogicException;

/**
 * Class AbstractConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractConfig implements ConfigInterface
{

    protected const MANDATORY_KEYS = [];

    /** @var array */
    protected $config;

    /**
     * AbstractConfig constructor.
     *
     * @param array $config
     *
     * @throws \LogicException
     */
    public function __construct(array $config)
    {
        if (count(array_diff(static::MANDATORY_KEYS, array_keys($config))) > 0) {
            throw new LogicException(sprintf(
                'Config array for %s missing mandatory keys: %s',
                static::class,
                join(', ', array_diff(static::MANDATORY_KEYS, array_keys($config)))
            ));
        }

        $this->config = $config;
    }
}
