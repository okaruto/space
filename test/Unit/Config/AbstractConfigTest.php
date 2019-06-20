<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Config;

use Okaruto\Space\Config\AbstractConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractConfigTest
 *
 * @package   Okaruto\Space\Tests\Unit\Config
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class AbstractConfigTest extends TestCase
{

    public function testAbstractConfigMandatoryKeys(): void
    {
        $class = new Class(['mandatory_1' => 'one', 'mandatory_2' => 'two']) extends AbstractConfig {
            protected const MANDATORY_KEYS = ['mandatory_1', 'mandatory_2'];
        };

        $this->assertInstanceOf(AbstractConfig::class, $class);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageRegExp('/missing mandatory keys: mandatory_2$/');

        new Class(['mandatory_1' => 'one']) extends AbstractConfig {
            protected const MANDATORY_KEYS = ['mandatory_1', 'mandatory_2'];
        };
    }

    public function testAbstractConfigNoMandatoryKeys(): void
    {
        $class = new Class([]) extends AbstractConfig {};

        $this->assertInstanceOf(AbstractConfig::class, $class);
    }
}
