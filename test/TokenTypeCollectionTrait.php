<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests;

use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;
use Okaruto\Space\Business\Token;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;

/**
 * Trait TokenTypeCollectionTrait
 *
 * @package   Okaruto\Space\Tests
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
trait TokenTypeCollectionTrait
{

    /** @var TokenTypeCollection */
    protected $tokenTypeCollection;

    /**
     * @return TokenTypeCollection
     */
    protected function tokenTypeCollection(): TokenTypeCollection
    {
        if ($this->tokenTypeCollection === null) {

            $this->tokenTypeCollection = new TokenTypeCollection(
                new TranslationContainer(new AvailableLocales(APPLICATION . '/languages', 'en')),
                [
                    Token\Type\OneWeek::class => 1.86,
                    Token\Type\OneMonth::class => 6.00,
                    Token\Type\ThreeMonths::class => 16.00,
                    Token\Type\SixMonths::class => 28.00,
                    Token\Type\OneYear::class => 52.00,
                    Token\Type\TwoYears::class => 94.00,
                    Token\Type\Lifetime::class => 256.00,
                ],
                InvoiceCurrencyValue::INVOICE_CURRENCY_USD
            );

        }

        return $this->tokenTypeCollection;
    }
}
