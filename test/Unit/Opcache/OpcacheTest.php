<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Opcache;

use Okaruto\Space\Opcache\Opcache;
use PHPUnit\Framework\TestCase;

/**
 * Class OpcacheTest
 *
 * @package   Okaruto\Space\Tests\Opcache
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class OpcacheTest extends TestCase
{

    private const UNITBASEPATH = APPLICATION . '/data/unit';
    private const CACHEPATH = self::UNITBASEPATH . '/cache';
    private const BASEPATH = self::UNITBASEPATH . '/base';
    private const BASEFILENAME = 'unittestfile';
    private const BASEFILEPATH = self::BASEPATH . '/' . self::BASEFILENAME;
    private const BASEFILECONTENT = '#unit test content#';

    public function setUp(): void
    {
        mkdir(self::CACHEPATH, 0777, true);
        mkdir(self::BASEPATH, 0777, true);

        file_put_contents(self::BASEFILEPATH, self::BASEFILECONTENT);
    }

    public function tearDown()
    {
        foreach(glob(self::CACHEPATH . '/*') as $file) {
            unlink($file);
        }

        rmdir(self::CACHEPATH);

        unlink(self::BASEFILEPATH);
        rmdir(self::BASEPATH);

        rmdir(self::UNITBASEPATH);
    }

    public function testFileCreation(): void
    {
        $opcache = new Opcache(self::CACHEPATH, self::BASEPATH);

        $this->assertSame(
            self::BASEFILECONTENT,
            $opcache->file(self::BASEFILENAME)
        );

        $this->assertFileExists(self::CACHEPATH . '/space_' . md5(self::BASEFILEPATH) . '.php');
    }

    public function testExistingFile(): void
    {
        $opcache = new Opcache(self::CACHEPATH, self::BASEPATH);
        $opcache->file(self::BASEFILENAME);

        $file = self::CACHEPATH . '/space_' . md5(self::BASEFILEPATH) . '.php';
        $differentContent = '#Different unit test content#';

        file_put_contents(
            $file,
            str_replace(self::BASEFILECONTENT, $differentContent, file_get_contents($file))
        );

        $this->assertSame(
            $differentContent,
            $opcache->file(self::BASEFILENAME)
        );
    }

    public function testMissingFile(): void
    {
        $opcache = new Opcache(self::CACHEPATH, self::BASEPATH);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            sprintf('File %s does not exist', self::BASEFILEPATH . '_missing')
        );

        $opcache->file(self::BASEFILENAME . '_missing');
    }

    public function testFileCreationBase64(): void
    {
        $opcache = new Opcache(self::CACHEPATH, self::BASEPATH);

        $this->assertSame(
            base64_encode(self::BASEFILECONTENT),
            $opcache->file(self::BASEFILENAME, true)
        );

        $this->assertFileExists(self::CACHEPATH . '/space_base64_' . md5(self::BASEFILEPATH) . '.php');
    }

    public function testExistingFileBase64(): void
    {
        $opcache = new Opcache(self::CACHEPATH, self::BASEPATH);
        $opcache->file(self::BASEFILENAME, true);

        $file = self::CACHEPATH . '/space_base64_' . md5(self::BASEFILEPATH) . '.php';
        $differentContent = base64_encode('#Different unit test content#');

        file_put_contents(
            $file,
            str_replace(base64_encode(self::BASEFILECONTENT), $differentContent, file_get_contents($file))
        );

        $this->assertSame(
            $differentContent,
            $opcache->file(self::BASEFILENAME, true)
        );
    }
}
