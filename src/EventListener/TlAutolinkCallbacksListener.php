<?php

declare(strict_types=1);

namespace Mandrael\ContaoAutolinkBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Intl\Locales;

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
}
