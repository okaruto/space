<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token;

use Okaruto\Space\Business\Token;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TokenValidationResult
 *
 * @package   Okaruto\Space\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TokenValidationResult
{

    public const REASON_VALID = 0;
    public const REASON_UNKNOWN = 1;
    public const REASON_FORMAT_INVALID = 2;
    public const REASON_TOKEN_INVALID = 3;
    public const REASON_TOKEN_SPENT = 4;

    private const REGEX_FORMAT = '/^(?<token>[a-zA-z0-9]{5}-[a-zA-z0-9]{5}-[a-zA-z0-9]{5}-[a-zA-z0-9]{5})$/';
    private const CSS_SELECTOR = 'div[style="text-align: center;"] + div[style="text-align: center;"]';

    private const TEXT_VALID = 'That token is VALID and has not yet been used.';
    private const TEXT_INVALID = 'Invalid token.';
    private const TEXT_SPENT = 'token is VALID and will expire in';

    private const REGEX_EXPIRES = '/expire\ (?<expires>[0-9]{1,5})\ days/';

    private const EXPIRES_VALUE_TOKEN_MAPPING = [
        Token\Type\OneWeek::DAYS => Token\Type\OneWeek::TYPE,
        Token\Type\OneMonth::DAYS => Token\Type\OneMonth::TYPE,
        Token\Type\ThreeMonths::DAYS => Token\Type\ThreeMonths::TYPE,
        Token\Type\SixMonths::DAYS => Token\Type\SixMonths::TYPE,
        Token\Type\OneYear::DAYS => Token\Type\OneYear::TYPE,
        Token\Type\TwoYears::DAYS => Token\Type\TwoYears::TYPE,
        Token\Type\Lifetime::DAYS => Token\Type\Lifetime::TYPE,
    ];

    /** @var bool */
    private $verify;

    /** @var string */
    private $token;

    /** @var bool|null */
    private $valid;

    /** @var string */
    private $html;

    /** string */
    private $type;

    /** @var null|int */
    private $reason;

    /**
     * TokenValidationResult constructor.
     *
     * @param bool   $verify
     * @param string $token
     * @param string $html
     */
    public function __construct(bool $verify, string $token, string $html)
    {
        $this->token = $token;
        $this->html = $html;
        $this->verify = $verify;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        if ($this->valid === null) {
            $this->valid = $this->format() && (!$this->verify || $this->validity());
        }

        return $this->valid;
    }

    /**
     * @return bool
     */
    public function reasonUnkown(): bool
    {
        return $this->reasonCode() === self::REASON_UNKNOWN;
    }

    /**
     * @return bool
     */
    public function reasonFormatInvalid(): bool
    {
        return $this->reasonCode() === self::REASON_FORMAT_INVALID;
    }

    /**
     * @return bool
     */
    public function reasonTokenInvalid(): bool
    {
        return $this->reasonCode() === self::REASON_TOKEN_INVALID;
    }

    /**
     * @return bool
     */
    public function reasonTokenSpent(): bool
    {
        return $this->reasonCode() === self::REASON_TOKEN_SPENT;
    }

    /**
     * @return int
     */
    public function reasonCode(): int
    {
        if ($this->reason === null) {
            $this->valid();
        }

        return (int)$this->reason;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function type(): string
    {
        if (!$this->valid()) {
            throw new \LogicException('Trying to access invalid validation result');
        }

        if (!$this->verified()) {
            throw new \LogicException('Trying to read token type from unverified validation result');
        }

        return $this->type;
    }

    /**
     * @return bool
     */
    public function verified(): bool
    {
        return $this->verify;
    }

    /**
     * @return bool
     */
    private function format(): bool
    {
        $formatValid = (bool)preg_match(self::REGEX_FORMAT, $this->token);
        $this->reason = $formatValid ? self::REASON_VALID : self::REASON_FORMAT_INVALID;

        return $formatValid;
    }

    /**
     * @return bool
     */
    private function validity(): bool
    {
        $dom = new Crawler($this->html);

        try {
            $text = $dom->filter(self::CSS_SELECTOR)->text();
        } catch (\InvalidArgumentException $exception) {
            $text = '';
        }

        $this->reason = strpos($text, self::TEXT_VALID) !== false
            ? self::REASON_VALID
            : (strpos($text, self::TEXT_INVALID) !== false
                ? self::REASON_TOKEN_INVALID
                : (strpos($text, self::TEXT_SPENT) !== false
                    ? self::REASON_TOKEN_SPENT
                    : self::REASON_UNKNOWN
                )
            );

        if ($this->reason === self::REASON_VALID) {
            $matches = [];

            if ((bool)preg_match(self::REGEX_EXPIRES, $text, $matches)) {
                $this->type = self::EXPIRES_VALUE_TOKEN_MAPPING[(int)$matches['expires']] ?? '';
            }

            if (empty($this->type)) {
                $this->reason = self::REASON_UNKNOWN;
            }
        }

        return $this->reason === self::REASON_VALID;
    }
}
