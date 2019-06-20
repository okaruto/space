<?php

declare(strict_types=1);

/** @var $container \League\Container\Container */

$container->share(\Swift_SpoolTransport::class, function(): \Swift_SpoolTransport {
    new \Swift_SendmailTransport();
    return new \Swift_SpoolTransport(new Swift_FileSpool(APPLICATION . '/data/spool'));
});
