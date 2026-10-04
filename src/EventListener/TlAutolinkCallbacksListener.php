<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle\EventListener;

use Contao\Backend;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Intl\Locales;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use FOS\HttpCache\CacheInvalidator;

class TlAutolinkCallbacksListener
{
    public function __construct(
        private readonly Locales $locales,
        private readonly ?CacheInvalidator $cacheInvalidator = null,
    ) {
    }

    /**
     * Provides the option list for the "language" select (used for the lang
     * attribute). Replaces the legacy $this->getLanguages() call that no longer
     * works inside a DCA array; System::getLanguages() was removed in Contao 5,
     * so we use the contao.intl.locales service, which exists in 4.13 and 5.x.
     *
     * @return array<string, string>
     */
    #[AsCallback(table: 'tl_autolink', target: 'fields.language.options')]
    public function getLanguageOptions(): array
    {
        return $this->locales->getLanguages();
    }

    /**
     * Rejects an invalid regular expression on save; at runtime it would be
     * skipped. Checked on "tag" because Contao 4.13 writes each field on its
     * own, so a later check could no longer stop the term from being stored.
     * The regex checkbox renders a hidden empty input, so its POST key exists
     * exactly when it is part of the form (edit and editAll); otherwise the
     * stored flag applies.
     */
    #[AsCallback(table: 'tl_autolink', target: 'fields.tag.save')]
    public function validateTagAsRegex(mixed $value, DataContainer $dc): mixed
    {
        $regex = Input::postRaw('regex'.substr((string) $dc->inputName, \strlen('tag'))) ?? self::activeRecordValue($dc, 'regex');

        if ($regex) {
            self::assertValidRegex((string) $value);
        }

        return $value;
    }

    /**
     * Covers switching on "regex" without editing "tag" (e.g. editAll with
     * only this field). The submitted term wins over the stored one, so that
     * Contao 4.13 does not store the flag when the term was just rejected.
     */
    #[AsCallback(table: 'tl_autolink', target: 'fields.regex.save')]
    public function validateRegex(mixed $value, DataContainer $dc): mixed
    {
        if ($value) {
            $tag = Input::postRaw('tag'.substr((string) $dc->inputName, \strlen('regex'))) ?? self::activeRecordValue($dc, 'tag');
            self::assertValidRegex((string) $tag);
        }

        return $value;
    }

    /**
     * Create, cut, copy and undo do not trigger Contao's cache invalidation.
     */
    #[AsCallback(table: 'tl_autolink', target: 'config.oncreate')]
    #[AsCallback(table: 'tl_autolink', target: 'config.oncut')]
    #[AsCallback(table: 'tl_autolink', target: 'config.oncopy')]
    #[AsCallback(table: 'tl_autolink', target: 'config.onundo')]
    public function invalidatePages(): void
    {
        $this->cacheInvalidator?->invalidateTags(['contao.db.tl_autolink']);
    }

    /**
     * Contao only invalidates the tag of the saved entry (or of its parent in
     * the tree), but the front end tags every page with the table tag.
     *
     * @param array<string> $tags
     *
     * @return array<string>
     */
    #[AsCallback(table: 'tl_autolink', target: 'config.oninvalidate_cache_tags')]
    public function invalidateCacheTags(DataContainer $dc, array $tags): array
    {
        $tags[] = 'contao.db.tl_autolink';

        return $tags;
    }

    /**
     * Renders the list entry (icon + label) as a link to its editing page, so a
     * single click opens the edit view instead of having to reach for the edit
     * operation on the far right. In tree mode (mode 5) Contao only prepends the
     * leaf icon when no label_callback is set, so the icon is added here; the
     * callback signature is identical in Contao 4.13 and 5.x.
     *
     * @param array<string, mixed> $row
     */
    #[AsCallback(table: 'tl_autolink', target: 'list.label.label')]
    public function renderLabelAsEditLink(array $row, string $label, DataContainer $dc, string $imageAttribute = '', bool $returnImage = false, bool $protected = false, bool $isVisibleRootTrailPage = false): string
    {
        $icon = Image::getHtml('iconPLAIN.svg', '', $imageAttribute);

        if ($returnImage) {
            return $icon;
        }

        return sprintf(
            '<a href="%s">%s %s</a>',
            Backend::addToUrl('act=edit&amp;id='.(int) $row['id']),
            $icon,
            $label,
        );
    }

    /**
     * The record including values already submitted in this request.
     * getActiveRecord() exists since Contao 5.3; the activeRecord property it
     * replaces is deprecated there and the only way in 4.13.
     */
    private static function activeRecordValue(DataContainer $dc, string $field): mixed
    {
        if (method_exists($dc, 'getActiveRecord')) {
            return $dc->getActiveRecord()[$field] ?? null;
        }

        // @phpstan-ignore property.notFound (magic property in Contao 4.13)
        return $dc->activeRecord?->{$field};
    }

    private static function assertValidRegex(string $tag): void
    {
        if (false === @preg_match(AutolinkListener::buildPattern(['regex' => '1', 'tag' => $tag], 'u'), '')) {
            throw new \RuntimeException(\sprintf($GLOBALS['TL_LANG']['tl_autolink']['invalidRegex'], preg_last_error_msg()));
        }
    }
}
