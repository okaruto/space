<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use League\Plates\Engine;
use Okaruto\Space\Opcache\Opcache;
use Okaruto\Space\Renderer\Extensions\InlineFileExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class InlineFileExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class InlineFileExtensionTest extends TestCase
{

    private const UNITBASEPATH = APPLICATION . '/data/unit';
    private const BASEPATH = self::UNITBASEPATH . '/base';
    private const BASEFILENAME = 'unittestfile';
    private const BASEFILEPATH = self::BASEPATH . '/' . self::BASEFILENAME;
    private const BASEFILECONTENT = '#unit test content#';

    public function setUp(): void
    {
        mkdir(self::BASEPATH, 0777, true);
        file_put_contents(self::BASEFILEPATH, self::BASEFILECONTENT);
    }

    public function tearDown()
    {
        unlink(self::BASEFILEPATH);
        rmdir(self::BASEPATH);
        rmdir(self::UNITBASEPATH);
    }

    public function testExtensionRegister(): void
    {
        $extension = new InlineFileExtension(new Opcache(null, self::BASEPATH));
        $function = (new Engine())->loadExtension($extension)->getFunction('inlineFile');

        $this->assertSame(
            [$extension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testInlineFileOutput(): void
    {
        $extension = new InlineFileExtension(new Opcache(null, self::BASEPATH));

        $this->assertSame(
            true,
            !empty($extension->__invoke(self::BASEFILENAME))
        );
    }
}
