<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @copyright  Carolina Koehn 2014
 * @author     Carolina Koehn <ck@kikmedia.de>
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_autolink']['tag']				= array('Search tag', 'Enter the search tag you want to be linked with the page below.');
$GLOBALS['TL_LANG']['tl_autolink']['description']		= array('Description', 'You can enter a description for this tag to be able to differentiate same tags from each other.');
$GLOBALS['TL_LANG']['tl_autolink']['type']				= array('Type', 'Please select the type of link you want to add.');
$GLOBALS['TL_LANG']['tl_autolink']['page']				= array('Page', 'Please select the target page.');
$GLOBALS['TL_LANG']['tl_autolink']['url']				= array('URL', 'Please enter the complete URL starting with http://.');
$GLOBALS['TL_LANG']['tl_autolink']['popup']				= array('Open in new window', 'Please check here if the link should open in a new window.');
$GLOBALS['TL_LANG']['tl_autolink']['casesensitive']		= array('Case sensitive', 'Please check here if your search tag is case sensitive.');
$GLOBALS['TL_LANG']['tl_autolink']['words']				= array('Match words', 'Please check here if you want to match only full words and not parts of a word.');
$GLOBALS['TL_LANG']['tl_autolink']['regex']				= array('Use regular expressions', 'Please check here if you want to enable regular expressions for the search tag. For more information about regular expressions, please see <a href="http://php.net/preg_replace" onclick="window.open(this.href); return false">http://php.net/preg_replace</a>.');
$GLOBALS['TL_LANG']['tl_autolink']['selflink']			= array('Apply on target page', 'Please check here if the search tag should also be replaced on its target page.');
$GLOBALS['TL_LANG']['tl_autolink']['cssFilter']			= array('Limit selection using CSS', 'You can limit the search to certain parts of the page by using CSS selectors.');
$GLOBALS['TL_LANG']['tl_autolink']['cssSelector']		= array('Selector', 'A selector defines to which HTML element or element group a search applies. You can enter one or more comma separated selectors.');
$GLOBALS['TL_LANG']['tl_autolink']['cssID']				= array('Style sheet ID and class', 'Here you can enter a style sheet ID (id attribute) and one or more style sheet classes (class attributes) to format the module element using CSS.');
$GLOBALS['TL_LANG']['tl_autolink']['addLanguage']		= array('Add language', 'Please check here if you want to add a language to this tag. This can be useful for accessible web pages.');
$GLOBALS['TL_LANG']['tl_autolink']['language']			= array('Language', 'Please choose a language.');
$GLOBALS['TL_LANG']['tl_autolink']['addTip']			= array('Show tool tip', 'Please check her if you want to display a tooltip for this tag.');
$GLOBALS['TL_LANG']['tl_autolink']['title']				= array('Title', 'Please enter a title for the tool tip.');
$GLOBALS['TL_LANG']['tl_autolink']['text']				= array('Text', 'Please enter a text for this tool tip.');
$GLOBALS['TL_LANG']['tl_autolink']['limitPages']		= array('Limit pages', 'Please check here if you want to limit pages on which this tag is linked.');
$GLOBALS['TL_LANG']['tl_autolink']['pages']				= array('Pages', '');
$GLOBALS['TL_LANG']['tl_autolink']['includeSubpages']	= array('Include subpages', 'If you check here, all subpages of the checked pages are also taken in account.');
$GLOBALS['TL_LANG']['tl_autolink']['published']			= array('Published', 'Unless you choose this option the tag will not be linked on your website.');
$GLOBALS['TL_LANG']['tl_autolink']['start']				= array('Show from', 'If you enter a date here the current tag will not be linked on the website before this day.');
$GLOBALS['TL_LANG']['tl_autolink']['stop']				= array('Show until', 'If you enter a date here the current tag will not be linked on the website after this day.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_autolink']['internal']			= 'Link to a Contao page';
$GLOBALS['TL_LANG']['tl_autolink']['external']			= 'Link to an URL';
$GLOBALS['TL_LANG']['tl_autolink']['none']				= 'Don\'t link (CSS class only)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_autolink']['new']				= array('New link', 'Create a new link');
$GLOBALS['TL_LANG']['tl_autolink']['edit']				= array('Edit link', 'Edit link ID %s');
$GLOBALS['TL_LANG']['tl_autolink']['copy']				= array('Copy link', 'Copy link ID %s');
$GLOBALS['TL_LANG']['tl_autolink']['cut']				= array('Move link', 'Move link ID %s');
$GLOBALS['TL_LANG']['tl_autolink']['delete']			= array('Delete link', 'Delete link ID %s');
$GLOBALS['TL_LANG']['tl_autolink']['show']				= array('Link details', 'Show details of link ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_autolink']['tag_legend']		= 'Search tag';
$GLOBALS['TL_LANG']['tl_autolink']['target_legend']		= 'Target';
$GLOBALS['TL_LANG']['tl_autolink']['limit_legend']		= 'Limitations';
$GLOBALS['TL_LANG']['tl_autolink']['language_legend']	= 'Language';
$GLOBALS['TL_LANG']['tl_autolink']['tips_legend']		= 'Tool-Tips';
$GLOBALS['TL_LANG']['tl_autolink']['expert_legend']		= 'Expert settings';
$GLOBALS['TL_LANG']['tl_autolink']['published_legend']	= 'Publish settings';

