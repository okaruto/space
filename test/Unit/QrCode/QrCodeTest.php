<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\QrCode;

use Okaruto\Space\QrCode\QrCode;
use PHPUnit\Framework\TestCase;

/**
 * Class QrCodeTest
 *
 * @package   Okaruto\Space\Tests\Unit\QrCode
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class QrCodeTest extends TestCase
{

    public function testQrCodeDataUri(): void
    {
        $qrCode = new QrCode(new \BaconQrCode\Writer(new \BaconQrCode\Renderer\Image\Svg()));

        $this->assertStringContainsString('data:image/svg+xml;base64,', $qrCode->inlineData('example'));
    }
}
