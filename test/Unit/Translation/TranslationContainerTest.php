<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Translation;

use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;

/**
 * Class TranslationContainerTest
 *
 * @package   Okaruto\Space\Tests\Unit\Translation
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TranslationContainerTest extends TestCase
{

    private const LANGUAGE_DIR = APPLICATION . '/data/unit_languages';
    private const LANGUAGE_FILE = self::LANGUAGE_DIR . '/en.php';

    /** @var AvailableLocales */
    private $availableLocales;

    public function setUp()
    {
        mkdir(self::LANGUAGE_DIR, 0777, true);
        file_put_contents(self::LANGUAGE_FILE, <<<'EOT'
<?php
return [
    'test:basic:translation' => 'ExampleTranslation',
    'test:replacer:translation' => [
        'text' => 'Replace test with %s, that\'s %s',
        'replacers' => ['value', 'affirmation'],
    ],
    'test:replacer:translation:missingkey' => [
        'text' => 'Replace %s with %s',
    ],
];
EOT
        );

        $this->availableLocales = new AvailableLocales(self::LANGUAGE_DIR, 'en');
    }

    public function tearDown()
    {
        unlink(self::LANGUAGE_FILE);
        rmdir(self::LANGUAGE_DIR);
    }

    public function testTranslationKeyAvailable(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation('en');

        $this->assertSame(true, $translationContainer->has('test:basic:translation'));
        $this->assertSame(false, $translationContainer->has('missing'));
    }

    public function testBasicTranslation(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation('en');

        $this->assertSame(
            'ExampleTranslation',
            $translationContainer->translate('test:basic:translation')
        );
    }

    public function testReplacedTranslation(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation('en');

        $this->assertSame(
            "Replace test with example, that's nice",
            $translationContainer->translate(
                'test:replacer:translation',
                ['value' => 'example', 'affirmation' => 'nice']
            )
        );
    }

    public function testReplacedTranslationMissingKeys(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation('en');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Translation for "test:replacer:translation" needs replacers: "affirmation"');
        $translationContainer->translate(
            'test:replacer:translation',
            ['value' => 'example']
        );
    }

    public function testReplacedTranslationMissingReplacersKey(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation('en');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Please check translation key "test:replacer:translation:missingkey"');
        $translationContainer->translate(
            'test:replacer:translation:missingkey',
            ['value' => 'example']
        );
    }

    public function testTranslationMising(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation('en');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to use non-existing translation key missing:key');
        $translationContainer->translate('missing:key');
    }

    public function testFallback(): void
    {
        $translationContainer = new TranslationContainer($this->availableLocales);
        $translationContainer->setTranslation(null);

        $this->assertSame(
            'ExampleTranslation',
            $translationContainer->translate('test:basic:translation')
        );
    }
}
