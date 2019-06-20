<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token;

use Okaruto\Space\Business\Token\Type\TokenTypeInterface;

/**
 * Class Token
 *
 * @package   Okaruto\Space\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class Token
{

    /** @var int */
    private $id;

    /** @var string */
    private $value;

    /** @var null|TokenTypeInterface */
    private $type;

    /** @var null|bool */
    private $valid;

    /**
     * Token constructor.
     *
     * @param int                     $id
     * @param string                  $value
     * @param null|TokenTypeInterface $type
     */
    public function __construct(int $id, string $value, ?TokenTypeInterface $type)
    {
        $this->id = $id;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @param bool $throw Should exception be thrown?
     *
     * @return bool
     */
    public function valid($throw = false): bool
    {
        if ($this->valid === null) {
            $this->valid = $this->id > 0 && !empty($this->value) && $this->type !== null;
        }

        if (!$this->valid && $throw) {
            throw new \LogicException('Token not valid');
        }

        return $this->valid;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        $this->valid(true);

        return $this->id;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $this->valid(true);

        return $this->value;
    }

    /**
     * @return TokenTypeInterface
     */
    public function type(): TokenTypeInterface
    {
        $this->valid(true);

        /** @var TokenTypeInterface $this->type */
        return $this->type;
    }
}
