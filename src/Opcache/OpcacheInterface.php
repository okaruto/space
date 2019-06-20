<?php

declare(strict_types=1);

namespace Okaruto\Space\Opcache;

/**
 * Interface FileCacheInterface
 *
 * @package   Okaruto\Space\Opcache
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface OpcacheInterface
{

    /**
     * @param string $relativePath
     * @param bool   $base64
     *
     * @return string
     */
    public function file(string $relativePath, bool $base64 = false): string;
}
