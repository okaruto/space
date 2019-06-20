<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer\Extensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Okaruto\Space\Translation\TranslationContainer;

/**
 * Class TranslatorExtension
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TranslatorExtension implements ExtensionInterface
{

    /** @var TranslationContainer */
    private $translations;

    /**
     * TranslatorExtension constructor.
     *
     * @param TranslationContainer $translations
     */
    public function __construct(TranslationContainer $translations)
    {
        $this->translations = $translations;
    }

    /**
     * @param Engine $engine
     *
     * @return void
     */
    public function register(Engine $engine): void
    {
        $engine->registerFunction('t', [$this, '__invoke']);
    }

    /**
     * @param string $key
     * @param array  $replacers
     *
     * @return string
     */
    public function __invoke(string $key, $replacers = []): string
    {
        return $this->translations->translate($key, $replacers);
    }
}
