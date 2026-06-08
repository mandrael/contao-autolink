<?php

declare(strict_types=1);

namespace Mandrael\ContaoAutolinkBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LanguageFilesTest extends TestCase
{
    private const REFERENCE_LANGUAGE = 'de';
    private const FILES = ['modules.php', 'tl_autolink.php'];

    /**
     * @return list<string>
     */
    private static function availableLanguages(): array
    {
        $languages = [];

        foreach (glob(\dirname(__DIR__).'/contao/languages/*', GLOB_ONLYDIR) ?: [] as $dir) {
            $languages[] = basename($dir);
        }

        sort($languages);

        return $languages;
    }

    /**
     * @return array<array{string, string}>
     */
    public static function languageFileProvider(): array
    {
        $cases = [];

        foreach (self::availableLanguages() as $lang) {
            foreach (self::FILES as $file) {
                $cases[] = [$lang, $file];
            }
        }

        return $cases;
    }

    #[DataProvider('languageFileProvider')]
    public function testLanguageFileIsLoadable(string $lang, string $file): void
    {
        $path = $this->languagePath($lang, $file);

        $this->assertFileExists($path);

        $GLOBALS['TL_LANG'] = [];
        require $path;

        $this->assertIsArray($GLOBALS['TL_LANG']);
        $this->assertNotEmpty($GLOBALS['TL_LANG'], "$lang/$file did not populate \$GLOBALS['TL_LANG']");
    }

    /**
     * @return array<array{string}>
     */
    public static function translationLanguageProvider(): array
    {
        $cases = [];

        foreach (self::availableLanguages() as $lang) {
            if (self::REFERENCE_LANGUAGE !== $lang) {
                $cases[] = [$lang];
            }
        }

        return $cases;
    }

    #[DataProvider('translationLanguageProvider')]
    public function testTranslationExposesTheSameTlAutolinkKeysAsReference(string $lang): void
    {
        $reference = $this->loadTlAutolink(self::REFERENCE_LANGUAGE);
        $translated = $this->loadTlAutolink($lang);

        $this->assertEqualsCanonicalizing(
            array_keys($reference),
            array_keys($translated),
            'The tl_autolink language keys differ between '.self::REFERENCE_LANGUAGE." and {$lang}.",
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function loadTlAutolink(string $lang): array
    {
        $GLOBALS['TL_LANG'] = [];
        require $this->languagePath($lang, 'tl_autolink.php');

        return $GLOBALS['TL_LANG']['tl_autolink'] ?? [];
    }

    private function languagePath(string $lang, string $file): string
    {
        return \dirname(__DIR__).'/contao/languages/'.$lang.'/'.$file;
    }
}
