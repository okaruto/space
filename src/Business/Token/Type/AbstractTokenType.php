<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;
use Okaruto\Space\Translation\TranslationContainer;

/**
 * Class AbstractTokenType
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractTokenType implements TokenTypeInterface
{

    /** @var string */
    const TYPE = null;

    /** @var string */
    const I18N = null;

    /** @var int */
    const CONNECTIONS = null;

    /** @var TranslationContainer */
    private $translator;

    /** @var float */
    private $price;

    /** @var InvoiceCurrencyValue */
    private $currency;

    /**
     * AbstractTokenType constructor.
     *
     * @param TranslationContainer $translator
     * @param float                $price
     * @param string               $currency
     *
     * @throws \LogicException
     */
    public function __construct(
        TranslationContainer $translator,
        float $price,
        string $currency
    ) {
        if (static::TYPE === null
            || static::I18N === null
            || static::CONNECTIONS === null
        ) {
            throw new \LogicException(
                sprintf(
                    'TokenType %s has not all mandatory constants set!',
                    static::class
                )
            );
        }

        $currency = new InvoiceCurrencyValue($currency);
        $currency->valid(true);

        $this->translator = $translator;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return static::TYPE;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->translator->translate(static::I18N);
    }

    /**
     * @return int
     */
    public function connections(): int
    {
        return static::CONNECTIONS;
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->currency->value();
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->price;
    }
}
