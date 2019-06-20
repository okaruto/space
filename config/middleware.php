<?php

declare(strict_types=1);

use Okaruto\Space\Middleware;

// Application middleware
$app->add(Middleware\XhrMiddleware::class);
$app->add(Middleware\RouteNameMiddleware::class);
$app->add(Middleware\RouteArgumentsMiddleware::class);

!file_exists(__DIR__ . '/middleware.dev.php') ?: require(__DIR__ . '/middleware.dev.php');
