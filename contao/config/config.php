<?php

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

// BE_MOD has no service-based equivalent, so it stays in config.php. The frontend
// hook (outputFrontendTemplate) is registered via the #[AsHook] attribute on
// Mandrael\ContaoAutolinkBundle\EventListener\AutolinkListener.
$GLOBALS['BE_MOD']['content']['autolink'] = [
    'tables' => ['tl_autolink'],
    'icon'   => 'bundles/mandraelcontaoautolink/autolink.svg',
];
