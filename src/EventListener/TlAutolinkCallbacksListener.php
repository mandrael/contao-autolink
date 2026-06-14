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

class TlAutolinkCallbacksListener
{
    public function __construct(private readonly Locales $locales)
    {
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
}
