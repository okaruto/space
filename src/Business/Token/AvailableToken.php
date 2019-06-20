<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token;

use Okaruto\Space\Business\Token\Type\TokenTypeInterface;

/**
 * Class AvailableToken
 *
 * @package   Okaruto\Space\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class AvailableToken implements AvailableTokenInterface, TokenTypeInterface
{

    /** @var int */
    private $amount;

    /** @var TokenTypeInterface */
    private $type;

    /**
     * AvailableToken constructor.
     *
     * @param int                $amount
     * @param TokenTypeInterface $type
     */
    public function __construct(int $amount, TokenTypeInterface $type)
    {
        $this->amount = $amount;
        $this->type = $type;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type->type();
    }

    /**
     * @return TokenTypeInterface
     */
    public function typeObject(): TokenTypeInterface
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->type->name();
    }

    /**
     * @return int
     */
    public function connections(): int
    {
        return $this->type->connections();
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->type->currency();
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->type->price();
    }
}
