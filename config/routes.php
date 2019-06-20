<?php

declare(strict_types=1);

/** @var $app \Slim\App */

// Routes
use Fig\Http\Message\RequestMethodInterface;
use Okaruto\Space\Router\RouteArguments;

$app->map(
    [
        RequestMethodInterface::METHOD_GET,
    ],
    '/',
    \Okaruto\Space\Handler\LanguageRedirectHandler::class
)->setName('language/redirect');

$app->group('/{' . RouteArguments::ARGUMENT_LANGUAGE . ':' . RouteArguments::REGEX_LANGUAGE . '}', function () {

    /** @var $this \Slim\App */

    $this->map(
        [
            RequestMethodInterface::METHOD_GET,
            RequestMethodInterface::METHOD_POST,
        ],
        '/',
        \Okaruto\Space\Handler\HomepageHandler::class
    )->setName('index');

    $this->map(
        [
            RequestMethodInterface::METHOD_POST,
        ],
        '/contact',
        \Okaruto\Space\Handler\ContactHandler::class
    )->setName('contact');

    $this->map(
        [
            RequestMethodInterface::METHOD_GET,
            RequestMethodInterface::METHOD_POST,
        ],
        '/order/create/{' . RouteArguments::ARGUMENT_TOKEN_TYPE . ':' . RouteArguments::REGEX_TOKEN_TYPE . '}',
        \Okaruto\Space\Handler\Order\OrderCreateHandler::class
    )->setName('order/create');

    $this->map(
        [
            RequestMethodInterface::METHOD_GET,
            RequestMethodInterface::METHOD_POST,
        ],
        '/order/view/{' . RouteArguments::ARGUMENT_ORDER_ID . ':' . RouteArguments::REGEX_ORDER_ID . '}',
        \Okaruto\Space\Handler\Order\OrderViewHandler::class
    )->setName('order/view');

})->add(\Okaruto\Space\Middleware\LanguageMandatoryMiddleware::class);

$app->map(
    [
        RequestMethodInterface::METHOD_POST,
    ],
    '/api/notification', \Okaruto\Space\Handler\Api\ApiNotificationHandler::class
)->setName('api/notification');

$app->map(
    [
        RequestMethodInterface::METHOD_GET,
        RequestMethodInterface::METHOD_POST,
    ],
    '/admin/token/add',
    \Okaruto\Space\Handler\Admin\AdminTokenAddHandler::class
)->setName('admin/token/add');
