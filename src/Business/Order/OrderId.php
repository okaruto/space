<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Order;

/**
 * Class OrderIdGenerator
 *
 * @package   Okaruto\Space\Business\Order
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class OrderId
{

    public const REGEX_ID = '/^[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}$/i';

    private const ALPHABET = '0123456789aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';

    /** @var int */
    private $alphabetLength;

    /**
     * OrderId constructor.
     */
    public function __construct()
    {
        $this->alphabetLength = strlen(self::ALPHABET) - 1;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return sprintf(
            '%s-%s-%s-%s-%s',
            $this->randomCharacters(8),
            $this->randomCharacters(4),
            $this->randomCharacters(4),
            $this->randomCharacters(4),
            $this->randomCharacters(12)
        );
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function valid(string $id): bool
    {
        return (bool)preg_match(self::REGEX_ID, $id);
    }

    /**
     * @param int $count
     *
     * @return string
     */
    private function randomCharacters(int $count): string
    {
        $chars = '';

        for ($i = 0; $i < $count; $i++) {
            $chars .= self::ALPHABET[random_int(0, $this->alphabetLength)];
        }

        return $chars;
    }
}
