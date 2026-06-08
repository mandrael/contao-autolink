<?php

declare(strict_types=1);

/*
 * This file is part of mandrael/contao-autolink.
 *
 * (c) Michael Gasperl
 *
 * @license LGPL-3.0-or-later
 */

namespace Mandrael\ContaoAutolinkBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MandraelContaoAutolinkExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(\dirname(__DIR__, 2).'/config'),
        );

        $loader->load('services.yaml');
    }
}
