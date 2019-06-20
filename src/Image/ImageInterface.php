<?php

declare(strict_types=1);

namespace Okaruto\Space\Image;

/**
 * Interface ImageInterface
 *
 * @package   Okaruto\Space\Image
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface ImageInterface
{

    /**
     * @param string $filename
     *
     * @return string
     */
    public function inline(string $filename): string;
}
