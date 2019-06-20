<?php

declare(strict_types=1);

define('APPLICATION', __DIR__ . '/..');

require(APPLICATION . '/vendor/autoload.php');

(function () {

    // Instantiate the app
    $app = new \Slim\App(require(APPLICATION . '/config/container.php'));

    // Register middleware
    require APPLICATION . '/config/middleware.php';

    // Register routes
    require APPLICATION . '/config/routes.php';

    // Run app
    $app->run();

})();
