<?php

declare(strict_types=1);

namespace Mandrael\ContaoAutolinkBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LanguageFilesTest extends TestCase
{
    private const LANGUAGES = ['de', 'en', 'fr'];
    private const FILES = ['modules.php', 'tl_autolink.php'];

    /**
     * @return array<array{string, string}>
     */
    public static function languageFileProvider(): array
    {
        $cases = [];

        foreach (self::LANGUAGES as $lang) {
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

    public function testGermanAndEnglishExposeTheSameTlAutolinkKeys(): void
    {
        $de = $this->loadTlAutolink('de');
        $en = $this->loadTlAutolink('en');

        $this->assertSame(
            array_keys($de),
            array_keys($en),
            'The tl_autolink language keys differ between de and en.',
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
