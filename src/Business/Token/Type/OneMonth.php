<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token\Type;

/**
 * Class OneMonth
 *
 * @package   Okaruto\Space\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class OneMonth extends AbstractTokenType
{
    public const TYPE = '1month';
    public const I18N = 'token:name:onemonth';
    public const CONNECTIONS = 1;
    public const DAYS = 31;
}
