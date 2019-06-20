<?php

declare(strict_types=1);

return array_replace_recursive(
    require APPLICATION . '/data/config.php',
    file_exists(APPLICATION . '/data/config.dev.php') ? require(APPLICATION . '/data/config.dev.php') : []
);
