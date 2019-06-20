<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Class TwoYears
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TwoYears extends AbstractTokenType
{
    public const TYPE = '2years';
    public const I18N = 'token:name:twoyears';
    public const CONNECTIONS = 5;
    public const DAYS = 730;
}
