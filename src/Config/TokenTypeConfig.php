<?php

declare(strict_types=1);

namespace Okaruto\Space\Config;

use Okaruto\Space\Business\Token\Type\TokenTypeInterface;

/**
 * Class TokenTypeConfig
 *
 * @package   Okaruto\Space\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TokenTypeConfig extends AbstractConfig
{

    public function __construct(array $config)
    {
        parent::__construct($config);

        if (empty($this->config)) {
            throw new \LogicException('No token types configured');
        }

        $invalidTokenTypes = array_filter(array_keys($this->config), [$this, 'checkTokenTypeInstance']);

        if (count($invalidTokenTypes) > 0) {
            throw new \LogicException(
                sprintf(
                    'Some configured tokens ("%s") do not implement the interface %s',
                    join('", "', $invalidTokenTypes),
                    TokenTypeInterface::class
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
     * @param $currencyClass
     *
     * @return bool
     */
    private function checkTokenTypeInstance($tokenTypeClass): bool
    {
        return !is_a($tokenTypeClass, TokenTypeInterface::class, true);
    }
}
