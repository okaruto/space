<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token;

use Okaruto\Space\Business\Token\Type\TokenTypeInterface;

/**
 * Interface AvailableTokenInterface
 *
 * @package   Okaruto\Space\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface AvailableTokenInterface
{

    /**
     * @return int
     */
    public function amount(): int;

    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return int
     */
    public function connections(): int;

    /**
     * @return string
     */
    public function currency(): string;

    /**
     * @return float
     */
    public function price(): float;
}
