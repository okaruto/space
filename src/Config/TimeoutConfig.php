<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

use Okaruto\Space\Interval\CancelledTimeoutInterval;
use Okaruto\Space\Interval\NewTimeoutInterval;
use Okaruto\Space\Interval\PaidTimeOutInterval;

/**
 * Class TimeoutConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TimeoutConfig extends AbstractConfig
{

    public const KEY_NEW = 'new';
    public const KEY_CANCELLED = 'cancelled';
    public const KEY_PAID = 'paid';

    protected const MANDATORY_KEYS = [
        self::KEY_NEW,
        self::KEY_CANCELLED,
        self::KEY_PAID,
    ];

    /**
     * @return NewTimeoutInterval
     */
    public function new(): NewTimeoutInterval
    {
        return new NewTimeoutInterval($this->config[self::KEY_NEW]);
    }

    /**
     * @return CancelledTimeoutInterval
     */
    public function cancelled(): CancelledTimeoutInterval
    {
        return new CancelledTimeoutInterval($this->config[self::KEY_CANCELLED]);
    }

    /**
     * @return PaidTimeOutInterval
     */
    public function paid(): PaidTimeOutInterval
    {
        return new PaidTimeOutInterval($this->config[self::KEY_PAID]);
    }
}
