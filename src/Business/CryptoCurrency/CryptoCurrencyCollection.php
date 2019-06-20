<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\CryptoCurrency;

use Okaruto\Space\Config\CryptoCurrencyConfig;
use Okaruto\Space\Image\ImageInterface;

/**
 * Class CryptoCurrencyCollection
 *
 * @package   Okaruto\Space\Business\CryptoCurrency
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class CryptoCurrencyCollection
{

    /** @var ImageInterface */
    private $image;

    /** @var CryptoCurrencyConfig */
    private $cryptoCurrencyConfig;

    /** @var null|CryptoCurrencyInterface[] */
    private $instances;

    /** @var null|CryptoCurrencyInterface[] */
    private $instanceList;

    /**
     * CryptoCurrencyCollection constructor.
     *
     * @param ImageInterface       $image
     * @param CryptoCurrencyConfig $cryptoCurrencyConfig
     */
    public function __construct(ImageInterface $image, CryptoCurrencyConfig $cryptoCurrencyConfig)
    {
        $this->image = $image;
        $this->cryptoCurrencyConfig = $cryptoCurrencyConfig;
    }

    /**
     * @return array
     */
    public function available(): array
    {
        if ($this->instanceList === null) {
            $this->initialize();
        }

        return $this->instanceList ?? [];
    }

    /**
     * @param string $currency
     *
     * @return bool
     */
    public function has(string $currency): bool
    {
        if ($this->instances === null) {
            $this->initialize();
        }

        return isset($this->instances[$currency]);
    }

    /**
     * @param string $currency
     *
     * @return CryptoCurrencyInterface
     * @throws \LogicException
     */
    public function get(string $currency): CryptoCurrencyInterface
    {
        if (!$this->has($currency)) {
            throw new \LogicException(sprintf('Cryptocurrency %s not set up', $currency));
        }

        /** @var CryptoCurrencyInterface[] $this->instances */
        return $this->instances[$currency];
    }

    /**
     * @return void
     */
    private function initialize(): void
    {
        $this->instances = array_reduce(
            array_map(
                [$this, 'instance'],
                $this->cryptoCurrencyConfig->all()
            ),
            [$this, 'reduce'],
            []
        );

        $this->instanceList = array_values($this->instances);
    }

    /**
     * @param string $class
     *
     * @return CryptoCurrencyInterface
     */
    private function instance(string $class): CryptoCurrencyInterface
    {
        return new $class($this->image);
    }

    /**
     * @param array                   $carry
     * @param CryptoCurrencyInterface $currency
     *
     * @return array
     */
    private function reduce(array $carry, CryptoCurrencyInterface $currency): array
    {
        $carry[$currency->code()] = $currency;

        return $carry;
    }
}
