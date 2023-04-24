<?php declare(strict_types=1);

namespace AshleyHardy\Language;

use AshleyHardy\Utilities\Traits\IsASingleton;
use RuntimeException;

/**
 * Language Class
 * 
 * The language class is responsible for loading language files and producing sensible translations.
 * It is intended to be paired with a Javascript library that can be used to cache the results of this service
 * and present translated strings on the screen.
 */
class Language
{
    use IsASingleton;

    private array $languages = [];
    private string $defaultLocale = 'en_GB';

    /**
     * Set's the default locale for the Language service.
     *
     * @param string $defaultLocale
     * @return void
     */
    public static function setDefaultLocale(string $defaultLocale): void
    {
        self::instance()->defaultLocale = $defaultLocale;
    }

    /**
     * Loads a language file from disk, and store it against the $locale.
     * If $locale is null, the language data will be stored against the $defaultLocale value.
     *
     * @param string $filePath
     * @param string|null $locale
     * @throws RuntimeException
     * @return void
     */
    public static function loadFile(string $filePath, ?string $locale = null): void
    {
        if(!file_exists($filePath)) throw new RuntimeException("The language file referenced cannot be found.");

        /** @var self */
        $instance = self::instance();
        if($locale === null) $locale = $instance->defaultLocale;

        $contents = include($filePath);
        if(!is_array($contents)) throw new RuntimeException("A language file must return an array.");

        $instance->languages[$locale] = $contents;
    }

    /**
     * Get's the array of language keys for a given locale.
     * If $locale is null, return the language keys for the default language.
     *
     * @param string|null $locale
     * @return array
     */
    public static function get(?string $locale = null): array
    {
        $instance = self::instance();
        if($locale === null) $locale = $instance->defaultLocale;

        if(!isset($instance->languages[$locale])) throw new RuntimeException("Language not available.");
        return $instance->languages[$locale];
    }

    /**
     * Get all language data from the service.
     *
     * @return array
     */
    public static function getAll(): array
    {
        return self::instance()->languages;
    }

    /**
     * Translate a language key into the relevant text.
     *
     * @param string $key
     * @param string|null $locale
     * @return string
     */
    public static function translate(string $key, ?string $locale = null): string
    {
        $instance = self::instance();
        if($locale === null) $locale = $instance->defaultLocale;
        $translations = self::get($locale);

        $keyParts = explode(".", $key);
        foreach($keyParts as $keyPart) {
            $translations = $translations[$keyPart] ?? null;
        }

        return (is_string($translations) ? $translations : $key);
    }
}