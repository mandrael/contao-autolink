<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Mandrael\ContaoAutolinkBundle\MandraelContaoAutolinkBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(MandraelContaoAutolinkBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
