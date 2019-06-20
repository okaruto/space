<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\Opcache\OpcacheInterface;

/**
 * Class InlineFileExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class InlineFileExtension implements ExtensionInterface
{

    /** @var OpcacheInterface */
    private $opcache;

    /**
     * InlineFileExtension constructor.
     *
     * @param OpcacheInterface $opcache
     */
    public function __construct(OpcacheInterface $opcache)
    {
        $this->opcache = $opcache;
    }

    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('inlineFile', [$this, '__invoke']);
    }

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function __invoke(string $relativePath): string
    {
        return $this->opcache->file($relativePath);
    }
}
