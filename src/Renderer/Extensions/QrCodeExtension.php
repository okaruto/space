<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\QrCode\QrCode;

/**
 * Class QrCodeExtension
 *
 * @package   Okaruto\Space\Renderer\Extensions
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class QrCodeExtension implements ExtensionInterface
{

    /** @var QrCode */
    private $qrCode;

    /**
     * QrCodeExtension constructor.
     *
     * @param QrCode $qrCode
     */
    public function __construct(QrCode $qrCode)
    {
        $this->qrCode = $qrCode;
    }

    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('qrcode', [$this, '__invoke']);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function __invoke(string $content): string
    {
        return $this->qrCode->inlineData($content);
    }
}
