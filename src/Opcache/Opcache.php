<?php

declare(strict_types=1);

namespace Okaruto\Space\Opcache;

/**
 * Class Opcache
 *
 * @package   Okaruto\Space\Opcache
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class Opcache implements OpcacheInterface
{

    private const FILE_PREFIX = 'space_';
    private const FILE_PREFIX_BASE64 = 'space_base64_';
    private const FILE_EXTENSION = '.php';
    private const CACHE_FILE_TEMPLATE = <<<'EOT'
<?php
declare(strict_types=1);

/**
 * Generated from #FILE# at #DATE#
 */
    
return <<<'EOV'
#VALUE#
EOV;

EOT;

    /** @var null|string */
    private $cachePath;

    /** @var string */
    private $basePath;

    /** @var bool */
    private $cacheActive;

    /**
     * InlineFileExtension constructor.
     *
     * @param null|string $cachePath
     * @param string      $basePath
     */
    public function __construct(?string $cachePath, string $basePath)
    {
        $this->cachePath = $cachePath;
        $this->basePath = $basePath;
        $this->cacheActive = $this->cachePath !== null;
    }

    /**
     * @param string $relativePath
     * @param bool   $base64
     *
     * @return string
     */
    public function file(string $relativePath, bool $base64 = false): string
    {

        $file = $this->basePath . '/' . $relativePath;

        if ($this->cacheActive) {
            $hash = md5($file);

            $cacheFile = (string) $this->cachePath . '/'
                . ($base64 ? self::FILE_PREFIX_BASE64 : self::FILE_PREFIX)
                . $hash . self::FILE_EXTENSION;

            if (file_exists($cacheFile)) {
                $value = require($cacheFile);
            } else {
                $value = $this->content($file, $base64);

                file_put_contents(
                    $cacheFile,
                    str_replace(
                        [
                            '#FILE#',
                            '#DATE#',
                            '#VALUE#',
                        ],
                        [
                            realpath($file),
                            date(DATE_W3C),
                            $value
                        ],
                        self::CACHE_FILE_TEMPLATE
                    )
                );
            }
        } else {
            $value = $this->content($file, $base64);
        }

        return $value;
    }

    /**
     * @param string $file
     * @param bool   $base64
     *
     * @return string
     */
    private function content(string $file, bool $base64): string
    {
        if (!file_exists($file)) {
            throw new \LogicException(
                sprintf(
                    'File %s does not exist',
                    $file
                )
            );
        }

        return $base64 ? base64_encode(file_get_contents($file)) : file_get_contents($file);
    }
}
