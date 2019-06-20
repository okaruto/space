<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Class ExternalLinkExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class ExternalLinkExtension implements ExtensionInterface
{
    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('externalLink', [$this, '__invoke']);
    }

    /**
     * @param string $url
     * @param string $text
     *
     * @return string
     */
    public function __invoke(string $url, string $text): string
    {
        return sprintf(
            '<a href="%s" rel="noopener nofollow noreferrer" target="_blank">%s</a>',
            $url,
            $text
        );
    }
}
