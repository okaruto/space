<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\CryptoCurrency;

/**
 * Interface CryptoCurrencyInterface
 *
 * @package   Okaruto\Space\Business\CryptoCurrency
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface CryptoCurrencyInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function shortCode(): string;

    /**
     * @return string
     */
    public function code(): string;

    /**
     * @return string
     */
    public function image(): string;
}
