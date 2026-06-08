<?php

declare(strict_types=1);

namespace Mandrael\ContaoAutolinkBundle\Tests\Contrib;

use PHPUnit\Framework\TestCase;

/**
 * Smoke tests for the bundled simplehtmldom parser (1.9.1, MIT). The autolink
 * listener depends on str_get_html(), find('text'), writable innertext and save().
 */
class SimpleHtmlDomTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once \dirname(__DIR__, 2).'/src/Resources/contrib/simple_html_dom.php';
    }

    public function testGlobalApiIsAvailable(): void
    {
        $this->assertTrue(\function_exists('str_get_html'));
        $this->assertTrue(class_exists('simple_html_dom'));
        $this->assertTrue(class_exists('simple_html_dom_node'));
    }

    public function testLinksWordInTextNodesAndLeavesProtectedTagsUntouched(): void
    {
        $dom = str_get_html('<div><h1>Hello World</h1><a>World</a><script>var World=1;</script></div>');

        foreach ($dom->find('text') as $text) {
            if (\in_array($text->parent()->tag, ['script', 'a'], true)) {
                continue;
            }

            $text->innertext = preg_replace('@(World)@u', '<a class="autolink">$1</a>', $text->innertext);
        }

        $out = $dom->save();
        $dom->clear();

        $this->assertStringContainsString('<h1>Hello <a class="autolink">World</a></h1>', $out);
        $this->assertStringContainsString('<a>World</a>', $out);
        $this->assertStringContainsString('<script>var World=1;</script>', $out);
    }

    public function testWholeWordBoundaryDoesNotMatchSubstrings(): void
    {
        $dom = str_get_html('<p>World and worldwide</p>');

        foreach ($dom->find('text') as $text) {
            $text->innertext = preg_replace(
                '@(\A|[^A-Za-z0-9]{1})(World)([^A-Za-z0-9]{1}|\Z)@u',
                '$1<a>$2</a>$3',
                $text->innertext,
            );
        }

        $out = $dom->save();
        $dom->clear();

        $this->assertStringContainsString('<a>World</a>', $out);
        $this->assertStringContainsString('worldwide', $out);
        $this->assertStringNotContainsString('<a>world</a>wide', $out);
    }
}
