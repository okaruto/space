<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\Container\LayoutVariables;

/**
 * Class LayoutVariablesExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class LayoutVariablesExtension implements ExtensionInterface
{

    /** @var LayoutVariables */
    private $layoutVariables;

    /**
     * LayoutVariablesExtension constructor.
     *
     * @param LayoutVariables $layoutVariables
     */
    public function __construct(LayoutVariables $layoutVariables)
    {
        $this->layoutVariables = $layoutVariables;
    }

    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('lv', [$this, '__invoke']);
    }

    /**
     * @return LayoutVariables
     */
    public function __invoke(): LayoutVariables
    {
        return $this->layoutVariables;
    }
}
