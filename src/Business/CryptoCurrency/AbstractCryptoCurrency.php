<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\CryptoCurrency;

use Okaruto\Space\Image\ImageInterface;

/**
 * Class AbstractCryptoCurrency
 *
 * @package   Okaruto\Space\Business\CryptoCurrency
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
abstract class AbstractCryptoCurrency implements CryptoCurrencyInterface
{

    /** @var string */
    const NAME = null;

    /** @var string */
    const SHORTCODE = null;

    /** @var string */
    const CODE = null;

    /** @var string */
    private $name;

    /** @var ImageInterface */
    private $image;

    /**
     * AbstractCurrency constructor.
     *
     * @param ImageInterface $image
     * @throws \LogicException
     */
    public function __construct(ImageInterface $image)
    {
        if (static::NAME === null || static::SHORTCODE === null || static::CODE === null) {
            throw new \LogicException(sprintf('%s has not all needed constants set!', static::class));
        }

        $this->name = sprintf('(%s) %s', strtoupper($this->shortCode()), static::NAME);
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function shortCode(): string
    {
        return static::SHORTCODE;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return static::CODE;
    }

    /**
     * @return string
     */
    public function image(): string
    {
        return $this->image->inline($this->shortCode());
    }
}
