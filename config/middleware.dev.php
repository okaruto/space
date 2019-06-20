<?php

declare(strict_types=1);

/** @var $app \Slim\App */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$app->add(
    function (ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface {
        return $next($request, $response)->withHeader(
            'X-Memory-Usage',
            number_format((float)(memory_get_peak_usage() / 1024)) . ' kb'
        );
    }
);
