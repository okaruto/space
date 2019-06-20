<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

/**
 * Trait PrepareClientTrait
 *
 * @package   Okaruto\Cryptonator\Tests
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
trait PrepareClientTrait
{

    /**
     * @param Response[] $responses
     * @param array $container
     *
     * @return Client
     */
    protected function client(array $responses, array &$container): Client
    {
        $historyMiddleware = Middleware::history($container);
        $mockHandler = new MockHandler($responses);
        $stack = HandlerStack::create($mockHandler);
        $stack->push($historyMiddleware);

        return new Client(['handler' => $stack]);
    }
}
