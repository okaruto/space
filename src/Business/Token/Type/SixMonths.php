<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Class SixMonths
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class SixMonths extends AbstractTokenType
{
    public const TYPE = '6months';
    public const I18N = 'token:name:sixmonths';
    public const CONNECTIONS = 3;
    public const DAYS = 183;
}
