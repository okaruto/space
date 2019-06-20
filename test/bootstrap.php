<?php

declare(strict_types=1);

define('APPLICATION', __DIR__ . '/..');

if (!file_exists(APPLICATION . '/data/config.php')) {
    copy(APPLICATION . '/data/config.template.php', APPLICATION . '/data/config.php');
}
