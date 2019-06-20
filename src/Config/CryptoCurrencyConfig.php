<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyInterface;

/**
 * Class CryptoCurrencyConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class CryptoCurrencyConfig extends AbstractConfig
{

    /**
     * CryptoCurrencyConfig constructor.
     *
     * @param array $config
     * @throws \LogicException;
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        if (empty($this->config)) {
            throw new \LogicException('No crypto currencies configured');
        }

        $invalidCurrencies = array_filter($this->config, [$this, 'checkCurrencyInstance']);

        if (count($invalidCurrencies) > 0) {
            throw new \LogicException(
                sprintf(
                    'Some configured crypto currencies ("%s") do not implement the interface %s',
                    join('", "', $invalidCurrencies),
                    CryptoCurrencyInterface::class
                )
            );
        }
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * @param $cryptoCurrencyClass
     *
     * @return bool
     */
    private function checkCurrencyInstance($cryptoCurrencyClass): bool
    {
        return !is_a($cryptoCurrencyClass, CryptoCurrencyInterface::class, true);
    }
}
