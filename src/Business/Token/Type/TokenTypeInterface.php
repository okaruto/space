<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Interface TokenTypeInterface
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface TokenTypeInterface
{

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
