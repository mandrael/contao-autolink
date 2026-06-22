<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle\Tests\EventListener;

use Mandrael\ContaoAutolinkBundle\EventListener\AutolinkListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AutolinkMatchingTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, bool}>
     */
    public static function wordBoundaryProvider(): iterable
    {
        // Standalone whole words must still match.
        yield 'standalone word' => ['Tor', 'Das Tor ist offen.', true];
        yield 'between punctuation' => ['Tor', 'Ein (Tor) hier.', true];
        yield 'at start of text' => ['Tor', 'Tor offen.', true];

        // A term adjacent to an accented letter or the sharp s must NOT match
        // inside a compound word (the bug fixed in 0.5.1).
        yield 'before umlaut' => ['Tor', 'Die Toröffnung war groß.', false];
        yield 'after sharp s' => ['strasse', 'Eine Großstrasse hier.', false];
        yield 'after sharp s, capitalised' => ['Ofen', 'Ein Großofen steht da.', false];

        // Adjacency to ASCII letters was already correct before the fix.
        yield 'inside ascii word' => ['Salz', 'Der Salzburger kommt.', false];
    }

    #[DataProvider('wordBoundaryProvider')]
    public function testLiteralTermsMatchWholeWordsAcrossUnicodeBoundaries(string $term, string $text, bool $shouldMatch): void
    {
        $pattern = self::buildPattern(['regex' => '', 'tag' => $term], 'ui');

        self::assertSame(
            $shouldMatch,
            1 === preg_match($pattern, $text),
            sprintf('Term "%s" in "%s"', $term, $text),
        );
    }

    public function testRegexTermsAreUsedVerbatimWithoutWordBoundaries(): void
    {
        $pattern = self::buildPattern(['regex' => '1', 'tag' => 'colou?r'], 'ui');

        self::assertSame(1, preg_match($pattern, 'a color here'));
        self::assertSame(1, preg_match($pattern, 'a colour here'));
    }

    public function testTextNodeUnderDomRootDoesNotWarnOnNullGrandparent(): void
    {
        require_once __DIR__.'/../../src/Resources/contrib/simple_html_dom.php';

        // A bare text fragment places the text node directly under the DOM root,
        // so $parent->parent() is null — the case that triggered the WARNING.
        $dom = str_get_html('Tor');

        $listener = (new \ReflectionClass(AutolinkListener::class))->newInstanceWithoutConstructor();
        $method = new \ReflectionMethod(AutolinkListener::class, 'processTextNode');

        set_error_handler(static function (int $errno, string $errstr): bool {
            throw new \RuntimeException($errstr);
        });

        try {
            foreach ($dom->find('text') as $text) {
                $method->invoke(
                    $listener,
                    $text,
                    ['regex' => '', 'tag' => 'Tor', 'addTip' => '', 'type' => 'none'],
                    '$1<span>$2</span>$3',
                    'ui',
                );
            }
        } finally {
            restore_error_handler();
        }

        $this->addToAssertionCount(1);
    }

    /**
     * @param array{regex: mixed, tag: mixed} $keyword
     */
    private static function buildPattern(array $keyword, string $modifier): string
    {
        $method = new \ReflectionMethod(AutolinkListener::class, 'buildPattern');

        return (string) $method->invoke(null, $keyword, $modifier);
    }
}
