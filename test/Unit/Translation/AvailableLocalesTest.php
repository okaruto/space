<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Translation;

use Okaruto\Space\Translation\AvailableLocales;
use PHPUnit\Framework\TestCase;

/**
 * Class AvailableLocalesTest
 *
 * @package   Okaruto\Space\Tests\Unit\Translation
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class AvailableLocalesTest extends TestCase
{

    private const LANGUAGE_DIR = APPLICATION . '/data/unit_languages';
    private const LANGUAGE_FILE_EN = self::LANGUAGE_DIR . '/en.php';
    private const LANGUAGE_FILE_RU = self::LANGUAGE_DIR . '/ru.php';

    public function setUp()
    {
        mkdir(self::LANGUAGE_DIR, 0777, true);
        file_put_contents(self::LANGUAGE_FILE_EN, "<?php return ['test' => 'en'];" . PHP_EOL);
        file_put_contents(self::LANGUAGE_FILE_RU, "<?php return ['test' => 'ru'];" . PHP_EOL);
    }

    public function tearDown()
    {
        unlink(self::LANGUAGE_FILE_EN);
        unlink(self::LANGUAGE_FILE_RU);
        rmdir(self::LANGUAGE_DIR);
    }

    public function testLocaleAvailable(): void
    {
        $this->assertSame(true, $this->availableLocalesInstance()->available('en'));
    }

    private function availableLocalesInstance(): AvailableLocales
    {
        return new AvailableLocales(self::LANGUAGE_DIR, 'en');
    }

    public function testLocaleUnavailable(): void
    {
        $this->assertSame(false, $this->availableLocalesInstance()->available('zh'));
    }

    public function testFallbackLocale(): void
    {
        $this->assertSame('en', $this->availableLocalesInstance()->fallbackLocale());
    }

    public function testTranslation(): void
    {
        $this->assertSame(['test' => 'en'], $this->availableLocalesInstance()->translation('en'));
    }

    public function testTranslationMissing(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to use locale "zh" without existing translation files');
        $this->availableLocalesInstance()->translation('zh');
    }

    public function testFallbackTranslation(): void
    {
        $this->assertSame(['test' => 'en'], $this->availableLocalesInstance()->fallbackTranslation());
    }

    public function testLocales(): void
    {
        $this->assertSame(['en', 'ru'], $this->availableLocalesInstance()->locales());
    }
}
