<?php

declare(strict_types=1);

namespace Okaruto\Space\Translation;

/**
 * Class AvailableLocales
 */
final class AvailableLocales
{

    /** @var string */
    private $path;

    /** @var int */
    private $pathLength;

    /** @var string */
    private $fallback;

    /** @var ?array */
    private $availableLocales;

    /**
     * AvailableLocales constructor.
     *
     * @param string $path
     * @param string $fallback
     */
    public function __construct(string $path, string $fallback)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->pathLength = strlen($this->path);
        $this->fallback = $fallback;
    }

    /**
     *
     * @return array
     */
    public function locales(): array
    {
        if ($this->availableLocales === null) {
            $this->availableLocales = $this->populate();
        }

        return array_keys($this->availableLocales);
    }

    /**
     *
     * @return string
     */
    public function fallbackLocale(): string
    {
        return $this->fallback;
    }

    /**
     *
     * @param string $locale
     *
     * @return array
     */
    public function translation(string $locale): array
    {
        $locale = strtolower($locale);

        if (!$this->available($locale)) {
            throw new \LogicException(sprintf('Trying to use locale "%s" without existing translation files', $locale));
        }

        /** @var string[] $this->availableLocales */

        return require($this->availableLocales[$locale]);
    }

    /**
     *
     * @return array
     */
    public function fallbackTranslation(): array
    {
        return $this->translation($this->fallback);
    }

    /**
     *
     * @param string|null $language
     *
     * @return bool
     */
    public function available(?string $language): bool
    {
        if ($this->availableLocales === null) {
            $this->availableLocales = $this->populate();
        }

        return $language !== null && array_key_exists(strtolower($language), $this->availableLocales);
    }

    /**
     * @return array
     */
    private function populate(): array
    {
        $locales = [];

        foreach (glob($this->path . '*.php') as $languageFile) {
            $locales[$this->extractLanguage($languageFile)] = $languageFile;
        }

        return $locales;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    private function extractLanguage(string $filePath): string
    {
        return substr(
            strtolower(trim($filePath)),
            $this->pathLength,
            strlen($filePath) - $this->pathLength - 4
        );
    }
}
