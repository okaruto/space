<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use BaconQrCode\Renderer\Image\Svg;
use BaconQrCode\Writer;
use League\Plates\Engine;
use Okaruto\Space\QrCode\QrCode;
use Okaruto\Space\Renderer\Extensions\QrCodeExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class QrCodeExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class QrCodeExtensionTest extends TestCase
{

    public function testExtensionRegister(): void
    {
        $extension = new QrCodeExtension(new QrCode(new Writer(new Svg())));
        $function = (new Engine())->loadExtension($extension)->getFunction('qrcode');

        $this->assertSame(
            [$extension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testQrCodeOutput(): void
    {
        $this->assertStringStartsWith(
            'data:image/svg+xml;base64,',
            (new QrCodeExtension(new QrCode(new Writer(new Svg()))))('teststring')
        );
    }
}
