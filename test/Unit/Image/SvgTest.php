<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Image;

use Okaruto\Space\Image\Svg;
use Okaruto\Space\Opcache\Opcache;
use PHPUnit\Framework\TestCase;

/**
 * Class SvgTest
 *
 * @package   Okaruto\Space\Tests\Unit\Image
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class SvgTest extends TestCase
{

    private const IMAGE_DIR = APPLICATION . '/data/unit_images';
    private const IMAGE_NAME = 'test';
    private const IMAGE_FILE = self::IMAGE_DIR . '/' . self::IMAGE_NAME . '.svg';

    public function setUp()
    {
        mkdir(self::IMAGE_DIR, 0777, true);
        file_put_contents(
            self::IMAGE_FILE,
            <<<'EOT'
<svg width="100" height="100"><circle cx="50" cy="50" r="50" fill="black"></circle></svg>
EOT
        );

    }

    public function tearDown()
    {
        unlink(self::IMAGE_FILE);
        rmdir(self::IMAGE_DIR);
    }

    public function testOutput(): void
    {
        $svg = new Svg('', new Opcache(null, self::IMAGE_DIR));
        $this->assertSame(
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9ImJsYWNrIj48L2NpcmNsZT48L3N2Zz4=',
            $svg->inline('test')
        );
    }
}
