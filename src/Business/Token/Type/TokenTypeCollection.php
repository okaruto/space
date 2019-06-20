<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

use Okaruto\Space\Translation\TranslationContainer;

/**
 * Class TokenTypeCollection
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TokenTypeCollection
{

    /** @var TranslationContainer */
    private $translator;

    /** @var array */
    private $types;

    /** @var string */
    private $currency;

    /** @var array */
    private $prices;

    /** @var array|null */
    private $instances;

    /** @var array */
    private $instanceList;

    /**
     * TokenTypeCollection constructor.
     *
     * @param TranslationContainer $translator
     * @param array                $types
     * @param string               $currency
     */
    public function __construct(
        TranslationContainer $translator,
        array $types,
        string $currency
    ) {
        $this->translator = $translator;
        $this->types = array_keys($types);
        $this->prices = $types;
        $this->currency = $currency;
    }

    /**
     * @return TokenTypeInterface[]
     */
    public function available(): array
    {
        $this->initialize();

        return $this->instanceList;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function has(string $type): bool
    {
        $this->initialize();

        return isset($this->instances[$type]);
    }

    /**
     * @param string $type
     *
     * @return TokenTypeInterface
     * @throws \Exception
     */
    public function get(string $type): TokenTypeInterface
    {
        $this->initialize();

        if (!$this->has($type)) {
            throw new \LogicException(sprintf('Trying to retrieve undefiend token type: %s', $type));
        }

        /** @var TokenTypeInterface[] $this->instances */
        return $this->instances[$type];
    }

    /**
     * @return void
     */
    private function initialize(): void
    {
        if ($this->instances === null) {
            $this->instances = array_reduce(
                array_map(
                    [$this, 'instance'],
                    $this->types
                ),
                [$this, 'reduce'],
                []
            );

            $this->instanceList = array_values($this->instances);
        }
    }

    /**
     * @param array              $carry
     * @param TokenTypeInterface $type
     *
     * @return array
     */
    private function reduce(array $carry, TokenTypeInterface $type): array
    {
        $carry[$type->type()] = $type;

        return $carry;
    }

    /**
     * @param string $class
     *
     * @return TokenTypeInterface
     * @throws \Exception
     */
    private function instance(string $class): TokenTypeInterface
    {

        if (!isset($this->prices[$class])) {
            throw new \LogicException(
                sprintf(
                    'Price for token type %s not set',
                    $class
                )
            );
        }

        return new $class($this->translator, $this->prices[$class], $this->currency);
    }
}
