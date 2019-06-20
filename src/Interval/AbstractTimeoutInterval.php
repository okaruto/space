<?php

declare(strict_types=1);

namespace Okaruto\Space\Interval;

/**
 * Class AbstractTimeoutInterval
 *
 * @package   Okaruto\Space\Interval
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractTimeoutInterval extends \DateInterval
{

    public function minutes(): int
    {
        return $this->i;
    }
}
