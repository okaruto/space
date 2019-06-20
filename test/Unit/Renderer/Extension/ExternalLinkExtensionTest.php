<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use League\Plates\Engine;
use Okaruto\Space\Renderer\Extensions\ExternalLinkExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class ExternalLinkExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class ExternalLinkExtensionTest extends TestCase
{

    public function testExtensionRegister(): void
    {
        $extension = new ExternalLinkExtension();
        $function = (new Engine())->loadExtension($extension)->getFunction('externalLink');

        $this->assertSame(
            [$extension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testGeneratedExternalLink(): void
    {
        $this->assertSame(
            '<a href="https://unit.test/link" rel="noopener nofollow noreferrer" target="_blank">Unit Test Link</a>',
            (new ExternalLinkExtension())('https://unit.test/link', 'Unit Test Link')
        );
    }
}
