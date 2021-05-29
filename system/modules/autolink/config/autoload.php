<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Autolink
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'simple_html_dom' => 'system/modules/autolink/simple_html_dom.php',
	'AutoLink'        => 'system/modules/autolink/AutoLink.php',
));
