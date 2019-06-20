<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Class ThreeMonths
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class ThreeMonths extends AbstractTokenType
{
    public const TYPE = '3months';
    public const I18N = 'token:name:threemonths';
    public const CONNECTIONS = 2;
    public const DAYS = 90;
}
