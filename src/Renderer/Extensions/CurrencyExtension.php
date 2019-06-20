<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;

/**
 * Class CurrencyExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class CurrencyExtension implements ExtensionInterface
{

    const CURRENCYMAP = [
        InvoiceCurrencyValue::INVOICE_CURRENCY_USD => '$',
        InvoiceCurrencyValue::INVOICE_CURRENCY_RUR => '₽',
        InvoiceCurrencyValue::INVOICE_CURRENCY_EUR => '€'
    ];

    public function register(Engine $engine): void
    {
        $engine->registerFunction('currency', [$this, '__invoke']);
    }

    /**
     * @param string $currency
     * @param float  $value
     *
     * @return string
     * @throws \LogicException
     */
    public function __invoke(string $currency, float $value): string
    {

        if (!isset(self::CURRENCYMAP[$currency])) {
            throw new \LogicException(sprintf('Currency mapping not set for %s', $currency));
        }

        return sprintf(
            '%s %s',
            self::CURRENCYMAP[$currency],
            number_format($value, 2)
        );
    }
}
