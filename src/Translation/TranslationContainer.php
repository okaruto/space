<?php

declare(strict_types=1);

namespace Okaruto\Space\Translation;

/**
 * Class TranslationContainer
 *
 * @package   Okaruto\Space\Translation
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TranslationContainer
{

    /** @var AvailableLocales */
    private $availableLocales;

    /** @var array */
    private $translation = [];

    /**
     * TranslationContainer constructor.
     *
     * @param AvailableLocales $availableLocales
     */
    public function __construct(AvailableLocales $availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }

    /**
     * Sets the language for translations
     *
     * @param string $locale
     *
     * @return void
     */
    public function setTranslation(?string $locale): void
    {
        $this->translation =
            $locale === null ? $this->availableLocales->fallbackTranslation()
                : $this->availableLocales->translation($locale);
    }

    /**
     * @param string $key
     * @param array  $replacers
     *
     * @return string
     * @throws \LogicException
     */
    public function translate(string $key, $replacers = []): string
    {
        if (!$this->has($key)) {
            throw new \LogicException(sprintf('Trying to use non-existing translation key %s', $key));
        }

        return is_string($this->translation[$key]) ? $this->translation[$key]
            : $this->replacedTranslation($key, $replacers);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->translation[$key]);
    }

    /**
     * @param string $key
     * @param array  $replacers
     *
     * @return string
     */
    private function replacedTranslation(string $key, array $replacers): string
    {
        $translation = $this->translation[$key];

        if (!is_array($translation) || !isset($translation['text'], $translation['replacers'])) {
            throw new \LogicException(sprintf('Please check translation key "%s"', $key));
        }

        if (count($replacers) !== count($translation['replacers'])) {
            throw new \LogicException(sprintf(
                'Translation for "%s" needs replacers: "%s"',
                $key,
                join(', ', array_diff($translation['replacers'], array_keys($replacers)))
            ));
        }

        return sprintf($translation['text'], ...$this->sortReplacers($translation['replacers'], $replacers));
    }

    /**
     * @param array $defined
     * @param array $given
     *
     * @return array
     */
    private function sortReplacers(array $defined, array $given): array
    {
        $sorted = [];

        foreach ($defined as $needed) {
            $sorted[] = $given[$needed];
        }

        return $sorted;
    }
}
