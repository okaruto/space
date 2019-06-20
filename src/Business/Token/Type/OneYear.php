<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Class OneYear
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class OneYear extends AbstractTokenType
{
    public const TYPE = '1year';
    public const I18N = 'token:name:oneyear';
    public const CONNECTIONS = 4;
    public const DAYS = 365;
}
