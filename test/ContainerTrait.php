<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests;

use Psr\Container\ContainerInterface;

/**
 * Trait ContainerTrait
 *
 * @package   Okaruto\Space\Tests
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
trait ContainerTrait
{

    /**
     * @return ContainerInterface
     */
    public function createContainer(): ContainerInterface
    {
        return require(APPLICATION . '/config/container.php');
    }
}
