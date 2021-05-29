<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Carolina Koehn 2014-2015
 * @author     Carolina Koehn <ck@kikmedia.de>
 */


class AutoLink extends Frontend
{

	/**
	 * HTML Tags we don't want to find
	 * - existing links (a)
	 * - head-elements (title, meta)
	 * - scripts & style-sheets (script, style)
	 * - input forms (textarea, label)
	 */
	private $arrProtectedTags = array('title', 'meta', 'style', 'script', 'textarea');
	
	/**
	 * HTML Tags we don't want to link
	 * but these can be uses for lang-Settings
	 */
	private $arrNolinkTags = array('a', 'label');
	
	/**
	 * Protected CSS classes
	 * - Accordion toggler
	 */
	private $arrProtectedClasses = array('toggler');
	
	
	/**
	 * Hook into outputFrontendTemplate to add autolink tags.
	 * 
	 * @access public
	 * @param string $strBuffer
	 * @param string $strTemplate
	 * @return string
	 */
	public function searchTemplate($strBuffer, $strTemplate)
	{
		global $objPage;
		$blnTips = false;
		
		$objKeywords = $this->Database->execute("SELECT tl_autolink.*, tl_page.alias AS page_alias, tl_page.id AS page_id FROM tl_autolink LEFT OUTER JOIN tl_page ON tl_autolink.page=tl_page.id WHERE tl_autolink.published=1 ORDER BY sorting");
		
		if (!$objKeywords->numRows)
			return $strBuffer;
			
			
		// Include SimpleHtmlDom
		if (!function_exists('file_get_html'))
			require_once(TL_ROOT . '/system/modules/autolink/simple_html_dom.php');
					   
		while( $objKeywords->next() )
		{
			// Start or Stop date not reached
			if (($objKeywords->start > 0 && $objKeywords->start > time()) || ($objKeywords->stop > 0 && $objKeywords->stop < time()))
				continue;
				
			// Skip internal links on their own page (if enabled)
			if ($objKeywords->type == 'internal' && $objPage->id == $objKeywords->page_id && !$objKeywords->selflink)
				continue;
				
			// Check if page is limited (and allowed)
			if ($objKeywords->limitPages)
			{
				// Reset to blank arrays
				$arrIds = array();
				$arrSub = array();
				
				$arrIds = deserialize($objKeywords->pages, true);
				
				if ($objKeywords->includeSubpages)
				{
					$arrSub = array();
					foreach( $arrIds as $pageId )
					{
						$arrSub = array_merge($arrSub, $this->getChildRecords($pageId, 'tl_page'));
					}
					
					$arrIds = array_merge($arrIds, $arrSub);
				}
				
				if (!in_array($objPage->id, $arrIds))
					continue;
			}
			
				
			// Load DOM
			$html = str_get_html($strBuffer);

		
			$cssID = deserialize($objKeywords->cssID);
			$id = strlen($cssID[0]) ? ' id="'.$cssID[0].'"' : '';
			$class = strlen($cssID[1]) ? ' '.$cssID[1] : '';
			$lang = ($objKeywords->addLanguage && strlen($objKeywords->language)) ? ' lang="' . $objKeywords->language . '" xml:lang="' . $objKeywords->language . '"' : '';
			$tip = '';
			
			if ($objKeywords->addTip)
			{
				$blnTips = true;
				$class .= ' autotip';
				$tip = (strlen($objKeywords->title) ? ' title="'.$objKeywords->title . '"' : '') . ' rel="' . $objKeywords->text . '"';
			}
			
			// init
			$strReplacement = '';
			$ref = '$1';
			
			if ($objKeywords->words)
			{
				$strReplacement .= '$1';
				$ref = '$2';
			}
			
			switch( $objKeywords->type )
			{
				default:
				case 'internal':
				
					$strUrl = $this->generateFrontendUrl(array('id'=>$objKeywords->page_id, 'alias'=>$objKeywords->page_alias));
					$objTargetPage = $this->getPageDetails($objKeywords->page_id);
			
					if (strlen($objTargetPage->domain))
						$strUrl = 'http://'.$objTargetPage->domain.'/'.$strUrl;
						
					$strReplacement .= '<a href="'.$strUrl.'"'.($objKeywords->popup ? ' onclick="window.open(this.href); return false"' : '').$id.$lang.' class="autolink'.$class.'"' . ($objKeywords->addTip ? $tip : ' title="'.$objTargetPage->title.'"') . '>'.$ref.'</a>';
				
					break;
					
				case 'external':
					$strReplacement .= '<a href="'.$objKeywords->url.'"'.($objKeywords->popup ? ' onclick="window.open(this.href); return false"' : '').$id.$lang.$tip.' class="autolink'.$class.'">'.$ref.'</a>';
					break;
					
				case 'none':
					$strReplacement .= '<span'.$id.$lang.$tip.' class="autolink'.$class.'">'.$ref.'</span>';
					break;
			}
			
			if ($objKeywords->words)
			{
				$strReplacement .= '$3';
			}
			
			
			if ($objKeywords->cssFilter)
			{
				foreach( $html->find($objKeywords->cssSelector) as $match)
				{
					foreach( $match->find('text') as $text )
					{
						if (in_array($text->parent()->tag, $this->arrProtectedTags))
						{
							$text->parent()->nextSibling();
							continue;
						}
						elseif (in_array($text->parent()->class, $this->arrProtectedClasses) || in_array($text->parent()->parent()->class, $this->arrProtectedClasses))
						{
							$text->parent()->nextSibling();
							continue;
						}
						elseif ((in_array($text->parent()->tag, $this->arrNolinkTags) || in_array($text->parent()->parent()->tag, $this->arrNolinkTags)) && ($objKeywords->addTip || $objKeywords->type != 'none'))
						{
							$text->parent()->nextSibling();
							continue;
						}
						
						
						$strQuery = $objKeywords->regex ? $objKeywords->tag : preg_quote($objKeywords->tag);
						
						$text->innertext = preg_replace('@' . ($objKeywords->words ? '(\A|[^A-Za-z0-9]{1})' : '') . '('.str_replace('@', '\@', $strQuery).')' . ($objKeywords->words ? '([^A-Za-z0-9]{1}|\Z)' : '') . '@su'.($objKeywords->casesensitive ? '' : 'i'), $strReplacement, $text->innertext);
					}
				}
			}
			else
			{
				foreach( $html->find('text') as $text )
				{
					if (in_array($text->parent()->tag, $this->arrProtectedTags))
					{
						$text->parent()->nextSibling();
						continue;
					}
					elseif (in_array($text->parent()->class, $this->arrProtectedClasses) || in_array($text->parent()->parent()->class, $this->arrProtectedClasses))
					{
						$text->parent()->nextSibling();
						continue;
					}
					elseif ((in_array($text->parent()->tag, $this->arrNolinkTags) || in_array($text->parent()->parent()->tag, $this->arrNolinkTags)) && ($objKeywords->addTip || $objKeywords->type != 'none'))
					{
						$text->parent()->nextSibling();
						continue;
					}
				
					$strQuery = $objKeywords->regex ? $objKeywords->tag : preg_quote($objKeywords->tag);
					
					$text->innertext = preg_replace('@' . ($objKeywords->words ? '(\A|[^A-Za-z0-9]{1})' : '') . '('.str_replace('@', '\@', $strQuery).')' . ($objKeywords->words ? '([^A-Za-z0-9]{1}|\Z)' : '') . '@u'.($objKeywords->casesensitive ? '' : 'i'), $strReplacement, $text->innertext);
				}
			}
			
			$strBuffer = $html->save();
			$html->clear();
			unset($html);
		}
		
		if ($blnTips)
		{
			// include Tips css
			$strBuffer = str_replace('<link rel="stylesheet" href="system/typolight.css" type="text/css" media="screen" />', '<link rel="stylesheet" href="system/typolight.css" type="text/css" media="screen" />'."\n".'<link rel="stylesheet" href="system/modules/autolink/html/tips.css" type="text/css" media="screen" />', $strBuffer);
		
			// inject Tips javascript	
			$strBuffer = str_replace('</body>', '
<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent(\'domready\', function()
{
	new Tips($$(\'.autotip\'), {
		showDelay: 900,
		maxTitleChars: 50,
		maxOpacity: 0.9
	});
});
//--><!]]>
</script>
'."\n</body>", $strBuffer);
		}
		
		return $strBuffer;
	}
	
}

