<?php

declare(strict_types=1);

namespace Okaruto\Space\Image;

use Okaruto\Space\Opcache\OpcacheInterface;

/**
 * Class Svg
 *
 * @package   Okaruto\Space\Image
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class Svg implements ImageInterface
{

    const EXTENSION = '.svg';

    /** @var string */
    private $path;

    /** @var OpcacheInterface */
    private $opcache;

    /**
     * Svg constructor.
     *
     * @param string           $path
     * @param OpcacheInterface $opcache
     */
    public function __construct(string $path, OpcacheInterface $opcache)
    {
        $this->path = $path;
        $this->opcache = $opcache;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function inline(string $filename): string
    {
        return 'data:image/svg+xml;base64,' . $this->load($this->link($filename));
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    private function link(string $filename): string
    {
        return $this->path . '/' . $filename . self::EXTENSION;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    private function load(string $file)
    {
        return $this->opcache->file($file, true);
    }
}
