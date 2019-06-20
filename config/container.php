<?php

declare(strict_types=1);

$container = new \League\Container\Container();

// Register the reflection container as delegate to enable auto wiring
$container->delegate((new \League\Container\ReflectionContainer())->cacheResolutions());

// Register slim delegate container with some defaults we don't want to implement again
$container->delegate(new \Slim\Container(require(__DIR__ . '/settings.php')));

require(__DIR__ . '/dependencies.php');

return $container;
