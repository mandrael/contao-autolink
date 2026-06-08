<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * Based on the original "Autolink" extension for Contao by Andreas Schempp,
 * originally distributed as part of Contao Open Source CMS (c) Leo Feyer.
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\PageModel;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

/**
 * Searches the rendered front end output for configured keywords and turns them
 * into links (internal, external or a plain span).
 *
 * This is the modern, service-based replacement for the legacy AutoLink class
 * that hooked into outputFrontendTemplate. The buffer-rewrite approach and the
 * simple_html_dom based matching are kept as-is; only the framework APIs were
 * migrated (Doctrine connection, ContaoFramework adapters, PageModel URLs).
 */
#[AsHook('outputFrontendTemplate')]
class AutolinkListener
{
    /**
     * HTML tags whose text content must never be touched
     * (existing links would be re-parsed, head/script/style content is not text).
     */
    private const PROTECTED_TAGS = ['title', 'meta', 'style', 'script', 'textarea'];

    /**
     * Tags inside which we do not want to create a link (but may set a language).
     */
    private const NOLINK_TAGS = ['a', 'label'];

    /**
     * CSS classes that must be skipped (e.g. the accordion toggler).
     */
    private const PROTECTED_CLASSES = ['toggler'];

    /**
     * simple_html_dom caps str_get_html() input at MAX_FILE_SIZE and silently
     * returns false above it (600 KB upstream) — which would drop autolinking on
     * large pages. We raise the cap; defining the constant before the require
     * keeps the vendored parser byte-identical to upstream.
     */
    private const PARSER_MAX_FILE_SIZE = 4 * 1024 * 1024;

    public function __construct(
        private readonly Connection $connection,
        private readonly ContaoFramework $framework,
    ) {
    }

    public function __invoke(string $buffer, string $template): string
    {
        $keywords = $this->connection->fetchAllAssociative(
            "SELECT * FROM tl_autolink WHERE published = '1' ORDER BY sorting",
        );

        if (!$keywords) {
            return $buffer;
        }

        // Lazily load the bundled simple_html_dom parser (global functions/classes).
        if (!\function_exists('str_get_html')) {
            \defined('MAX_FILE_SIZE') || \define('MAX_FILE_SIZE', self::PARSER_MAX_FILE_SIZE);
            require_once __DIR__.'/../Resources/contrib/simple_html_dom.php';
        }

        $stringUtil = $this->framework->getAdapter(StringUtil::class);
        $pageModel = $this->framework->getAdapter(PageModel::class);
        $objPage = $GLOBALS['objPage'] ?? null;

        foreach ($keywords as $keyword) {
            // Start or stop date not reached.
            if (
                ($keyword['start'] && (int) $keyword['start'] > time())
                || ($keyword['stop'] && (int) $keyword['stop'] < time())
            ) {
                continue;
            }

            // Skip internal links on their own page (unless self-linking is allowed).
            if (
                'internal' === $keyword['type']
                && null !== $objPage
                && (int) $objPage->id === (int) $keyword['page']
                && !$keyword['selflink']
            ) {
                continue;
            }

            // Restrict to certain pages (and optionally their subpages).
            if ($keyword['limitPages']) {
                $ids = $stringUtil->deserialize($keyword['pages'], true);

                if ($keyword['includeSubpages']) {
                    $ids = array_merge($ids, $this->getChildPageIds($ids));
                }

                if (null === $objPage || !\in_array((int) $objPage->id, array_map('intval', $ids), true)) {
                    continue;
                }
            }

            $html = str_get_html($buffer);

            if (false === $html) {
                continue;
            }

            $cssID = $stringUtil->deserialize($keyword['cssID']) ?: [];
            $id = !empty($cssID[0]) ? ' id="'.$cssID[0].'"' : '';
            $class = !empty($cssID[1]) ? ' '.$cssID[1] : '';
            $lang = ($keyword['addLanguage'] && $keyword['language'])
                ? ' lang="'.$keyword['language'].'" xml:lang="'.$keyword['language'].'"'
                : '';

            // Tooltips: the legacy MooTools "Tips" are gone in modern Contao, so we
            // render the tooltip text as a native title attribute instead.
            $tip = '';

            if ($keyword['addTip']) {
                $tipTitle = $keyword['title'] ?: strip_tags((string) $keyword['text']);

                if ('' !== $tipTitle) {
                    $tip = ' title="'.$stringUtil->specialchars($tipTitle).'"';
                }
            }

            // Build the replacement string. With "words" enabled, group 1 and 3 are
            // the surrounding word boundaries that must be preserved, group 2 is the
            // matched term; otherwise group 1 is the matched term.
            $replacement = '';
            $ref = '$1';

            if ($keyword['words']) {
                $replacement .= '$1';
                $ref = '$2';
            }

            switch ($keyword['type']) {
                default:
                case 'internal':
                    $url = '';
                    $title = '';
                    $targetPage = $pageModel->findWithDetails((int) $keyword['page']);

                    if (null !== $targetPage) {
                        // Relative URL on the same domain, absolute URL across domains
                        // (mirrors the legacy generateFrontendUrl + domain handling).
                        $url = $targetPage->domain ? $targetPage->getAbsoluteUrl() : $targetPage->getFrontendUrl();
                        $title = $targetPage->title;
                    }

                    $replacement .= '<a href="'.$url.'"'
                        .($keyword['popup'] ? ' onclick="window.open(this.href); return false"' : '')
                        .$id.$lang.' class="autolink'.$class.'"'
                        .($keyword['addTip'] ? $tip : ' title="'.$stringUtil->specialchars($title).'"')
                        .'>'.$ref.'</a>';
                    break;

                case 'external':
                    $replacement .= '<a href="'.$keyword['url'].'"'
                        .($keyword['popup'] ? ' onclick="window.open(this.href); return false"' : '')
                        .$id.$lang.$tip.' class="autolink'.$class.'">'.$ref.'</a>';
                    break;

                case 'none':
                    $replacement .= '<span'.$id.$lang.$tip.' class="autolink'.$class.'">'.$ref.'</span>';
                    break;
            }

            if ($keyword['words']) {
                $replacement .= '$3';
            }

            if ($keyword['cssFilter']) {
                $modifier = 'su'.($keyword['casesensitive'] ? '' : 'i');

                foreach ($html->find($keyword['cssSelector']) as $match) {
                    foreach ($match->find('text') as $text) {
                        $this->processTextNode($text, $keyword, $replacement, $modifier);
                    }
                }
            } else {
                $modifier = 'u'.($keyword['casesensitive'] ? '' : 'i');

                foreach ($html->find('text') as $text) {
                    $this->processTextNode($text, $keyword, $replacement, $modifier);
                }
            }

            $buffer = $html->save();
            $html->clear();
            unset($html);
        }

        return $buffer;
    }

    /**
     * Replace the configured term inside a single text node, honouring the
     * protected tags/classes and the no-link tags.
     *
     * @param mixed $text A simple_html_dom text node
     */
    private function processTextNode($text, array $keyword, string $replacement, string $modifier): void
    {
        $parent = $text->parent();

        if (\in_array($parent->tag, self::PROTECTED_TAGS, true)) {
            return;
        }

        if (
            \in_array($parent->class, self::PROTECTED_CLASSES, true)
            || \in_array($parent->parent()->class, self::PROTECTED_CLASSES, true)
        ) {
            return;
        }

        if (
            (\in_array($parent->tag, self::NOLINK_TAGS, true) || \in_array($parent->parent()->tag, self::NOLINK_TAGS, true))
            && ($keyword['addTip'] || 'none' !== $keyword['type'])
        ) {
            return;
        }

        $query = $keyword['regex'] ? $keyword['tag'] : preg_quote((string) $keyword['tag']);

        $pattern = '@'
            .($keyword['words'] ? '(\A|[^A-Za-z0-9]{1})' : '')
            .'('.str_replace('@', '\@', $query).')'
            .($keyword['words'] ? '([^A-Za-z0-9]{1}|\Z)' : '')
            .'@'.$modifier;

        $text->innertext = preg_replace($pattern, $replacement, $text->innertext);
    }

    /**
     * Recursively collect all descendant page IDs (replacement for the legacy
     * Database::getChildRecords helper).
     *
     * @param array<int|string> $pids
     *
     * @return array<int>
     */
    private function getChildPageIds(array $pids): array
    {
        $all = [];

        foreach ($pids as $pid) {
            $children = $this->connection->fetchFirstColumn(
                'SELECT id FROM tl_page WHERE pid = ?',
                [(int) $pid],
            );

            if ($children) {
                $children = array_map('intval', $children);
                $all = array_merge($all, $children, $this->getChildPageIds($children));
            }
        }

        return $all;
    }
}
