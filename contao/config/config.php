<?php

/*
 * Back end module registration.
 *
 * BE_MOD has no service-based equivalent, so it stays in the legacy config.php.
 * The frontend hook (outputFrontendTemplate) is registered via the #[AsHook]
 * attribute on Mandrael\ContaoAutolinkBundle\EventListener\AutolinkListener.
 */
$GLOBALS['BE_MOD']['content']['autolink'] = [
    'tables' => ['tl_autolink'],
    'icon'   => 'bundles/contaoautolink/wand.svg',
];
