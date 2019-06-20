<?php

declare(strict_types=1);

namespace Okaruto\Space\Router;

/**
 * Class RouteArguments
 *
 * @package   Okaruto\Space\Router
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class RouteArguments
{

    public const ARGUMENT_LANGUAGE = 'language';
    public const ARGUMENT_TOKEN_TYPE = 'tokenType';
    public const ARGUMENT_ORDER_ID = 'orderId';

    public const REGEX_LANGUAGE = '[a-z]{2}';
    public const REGEX_TOKEN_TYPE = '1week|1month|3months|6months|1year|2years|lifetime';
    public const REGEX_ORDER_ID = '[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}';
}
