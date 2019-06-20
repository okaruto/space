<?php

declare(strict_types=1);

namespace Okaruto\Space\QrCode;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Writer;

/**
 * Class QrCode
 *
 * @package   Okaruto\Space\QrCode
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class QrCode
{

    /** @var Writer */
    private $generator;

    /**
     * QrCode constructor.
     *
     * @param Writer $generator
     */
    public function __construct(Writer $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function inlineData(string $content): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(
            $this->generator->writeString(
                $content,
                Encoder::DEFAULT_BYTE_MODE_ECODING,
                ErrorCorrectionLevel::L
            )
        );
    }
}
