<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\Image\ImageInterface;

/**
 * Class IconExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class IconExtension implements ExtensionInterface
{

    private const CSS_CLASS = 'icon';

    /** @var ImageInterface */
    private $image;

    /**
     * SvgExtension constructor.
     *
     * @param ImageInterface $image
     */
    public function __construct(ImageInterface $image)
    {
        $this->image = $image;
    }

    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('icon', [$this, '__invoke']);
    }

    /**
     * @param string $name
     *
     * @param string $classes
     *
     * @return string
     */
    public function __invoke(string $name, string $classes = '')
    {
        return sprintf(
            '<img class="%s" alt="%s Icon" src="%s">',
            rtrim(self::CSS_CLASS . ' ' . $classes),
            ucfirst($name),
            $this->image->inline($name)
        );
    }
}
