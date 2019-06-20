<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Class Lifetime
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class Lifetime extends AbstractTokenType
{
    public const TYPE = 'lifetime';
    public const I18N = 'token:name:lifetime';
    public const CONNECTIONS = 6;
    public const DAYS = 22000;
}
