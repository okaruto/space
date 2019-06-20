<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer\Extension;

use League\Plates\Engine;
use Okaruto\Space\Renderer\Extensions\TranslatorExtension;
use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;

/**
 * Class TranslatorExtensionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer\Extension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TranslatorExtensionTest extends TestCase
{

    private const LANGUAGE_DIR = APPLICATION . '/data/unit_languages';
    private const LANGUAGE_FILE = self::LANGUAGE_DIR . '/en.php';

    /** @var TranslatorExtension */
    private $translatorExtension;

    public function setUp()
    {
        mkdir(self::LANGUAGE_DIR, 0777, true);
        file_put_contents(
            self::LANGUAGE_FILE,
            <<<'EOT'
<?php 
return [
    'test:basic:translation' => 'ExampleTranslation',
    'test:replacer:translation' => [
        'text' => 'Replace test with %s, that\'s %s',
        'replacers' => ['value', 'affirmation'],
    ],
];
EOT
        );

        $availableLocales = new AvailableLocales(self::LANGUAGE_DIR, 'en');
        $translationContainer = new TranslationContainer($availableLocales);
        $translationContainer->setTranslation('en');

        $this->translatorExtension = new TranslatorExtension($translationContainer);
    }

    public function tearDown()
    {
        unlink(self::LANGUAGE_FILE);
        rmdir(self::LANGUAGE_DIR);
    }

    public function testExtensionRegister(): void
    {
        $function = (new Engine())->loadExtension($this->translatorExtension)->getFunction('t');

        $this->assertSame(
            [$this->translatorExtension, '__invoke'],
            $function->getCallback()
        );
    }

    public function testBasicTranslation(): void
    {
        $translatorExtension = $this->translatorExtension;

        $this->assertSame(
            'ExampleTranslation',
            $translatorExtension('test:basic:translation')
        );
    }

    public function testReplacerTranslation(): void
    {
        $translatorExtension = $this->translatorExtension;

        $this->assertSame(
            "Replace test with example, that's nice",
            $translatorExtension('test:replacer:translation', ['value' => 'example', 'affirmation' => 'nice'])
        );
    }

    public function testMissingReplacerTranslation(): void
    {
        $translatorExtension = $this->translatorExtension;

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Translation for "test:replacer:translation" needs replacers: "value, affirmation"');
        $translatorExtension('test:replacer:translation');
    }
}
