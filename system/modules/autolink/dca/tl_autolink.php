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
 * @copyright  Carolina Koehn 2014-2015
 * @author     Carolina Koehn <ck@kikmedia.de>
 */


/**
 * Table tl_autolink
 */
$GLOBALS['TL_DCA']['tl_autolink'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => false,
		'label'						  => &$GLOBALS['TL_LANG']['MOD']['autolink'][0],
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'fields'                  => array('sorting'),
			'flag'                    => 1,
			'panelLayout'             => 'search,limit',
			'paste_button_callback'   => array('tl_autolink', 'pasteTag'),
            'icon'                    => 'system/modules/autolink/html/wand.svg',
		),
		'label' => array
		(
			'fields'                  => array('tag', 'description'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback'          => array('tl_autolink', 'addImage'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_autolink']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_autolink']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_autolink']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_autolink']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_autolink']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				  => array('type', 'cssFilter', 'addLanguage', 'addTip', 'limitPages'),
		'default'                     => '{tag_legend},tag,description;{target_legend},type',
		'internal'					  => '{tag_legend},tag,description;{target_legend},type,page,selflink,popup;{limit_legend:hide},cssFilter,limitPages;{language_legend:hide},addLanguage;{tips_legend:hide},addTip;{expert_legend:hide},casesensitive,words,cssID,regex;{published_legend},published,start,stop',
		'external'					  => '{tag_legend},tag,description;{target_legend},type,url,popup;{limit_legend:hide},cssFilter,limitPages;{language_legend:hide},addLanguage;{tips_legend:hide},addTip;{expert_legend:hide},casesensitive,words,cssID,regex;{published_legend},published,start,stop',
		'none'						  => '{tag_legend},tag,description;{target_legend},type;{limit_legend:hide},cssFilter,limitPages;{language_legend:hide},addLanguage;{tips_legend:hide},addTip;{expert_legend:hide},casesensitive,words,cssID,regex;{published_legend},published,start,stop',
	),
	
	// Subpalettes
	'subpalettes' => array
	(
		'cssFilter'					  => 'cssSelector',
		'addLanguage'				  => 'language',
		'addTip'					  => 'title,text',
		'limitPages'                  => 'pages,includeSubpages',
	),

	// Fields
	'fields' => array
	(
		'tag' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['tag'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'preserveTags'=>true, 'tl_class'=>'long'),
		),
		'description' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_autolink']['description'],
			'inputType'             => 'text',
			'exclude'				=> true,
			'search'				=> true,
			'eval'                  => array('maxlength'=>255, 'tl_class'=>'long'),
		),
		'type' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_autolink']['type'],
			'default'				  => 'internal',
			'exclude'				  => true,
			'filter'                  => true,
			'inputType'				  => 'radio',
			'options'				  => array('internal', 'external', 'none'),
			'reference'				  => &$GLOBALS['TL_LANG']['tl_autolink'],
			'eval'					  => array('submitOnChange'=>true),
		),
		'page' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['page'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'					  => array('fieldType'=>'radio', 'mandatory'=>true),
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('rgxp'=>'url', 'maxlength'=>255, 'mandatory'=>true, 'tl_class'=>'long'),
		),
		'addLanguage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['addLanguage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('submitOnChange'=>true),
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['language'],
			'inputType'               => 'select',
			'exclude'                 => true,
			'default'				  => 'en',
			'options'				  => $this->getLanguages(),
			'eval'					  => array('mandatory'=>true),
		),
		'addTip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['addTip'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('submitOnChange'=>true),
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('maxlength'=>255, 'tl_class'=>'long'),
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['text'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'					  => array('style'=>'height: 80px', 'mandatory'=>true),
		),
		'popup' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['popup'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
		),
		'selflink' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['selflink'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
		),
		'casesensitive' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['casesensitive'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
		),
		'regex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['regex'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
		),
		'words' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['words'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
		),
		'cssFilter' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['cssFilter'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('submitOnChange'=>true),
		),
		'cssSelector' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['cssSelector'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'					  => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long', 'decodeEntities'=>true),
		),
		'limitPages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['limitPages'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'filter'                  => true,
			'eval'					  => array('submitOnChange'=>true),
		),
		'pages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['pages'],
			'inputType'               => 'pageTree',
			'exclude'                 => true,
			'eval'					  => array('multiple'=>true,'mandatory'=>true, 'fieldType'=>'checkbox'),
		),
		'includeSubpages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['includeSubpages'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'maxlength'=>240, 'tl_class'=>'w50'),
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['published'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'filter'                  => true,
			'eval'                    => array('doNotCopy'=>true),
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['start'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_autolink']['stop'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
		),
	)
);


class tl_autolink extends Backend
{

	/**
	 * Return the paste button.
	 * 
	 * @access public
	 * @param object DataContainer $dc
	 * @param array $row
	 * @param string $table
	 * @param bool $cr
	 * @param mixed $arrClipboard. (default: false)
	 * @return string
	 */
	public function pasteTag(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
	{
		if ($row['id'] == 0)
		{
			return '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&mode=2&pid='.$row['id'].'&id='.$arrClipboard['id']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id'])).'</a> ';
		}

		return '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&mode=1&pid='.$row['id'].'&id='.$arrClipboard['id']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id'])).'</a> ';
	}
	
	
	/**
	 * Add an image to each autolink in the tree.
	 * 
	 * @access public
	 * @param array $row
	 * @param string $label
	 * @param string $imageAttribute
	 * @param object DataContainer $dc
	 * @return string
	 */
	public function addImage($row, $label, $param3, $param4, $blnReturnImage=false)
	{
		// Parameter sorting has been changed in 2.8: http://dev.typolight.org/issues/show/1488
		if (version_compare(VERSION.'.'.BUILD, '2.7.6', '>'))
		{
			$dc = $param3;
			$imageAttribute = $param4;
		}
		else
		{
			$dc = $param4;
			$imageAttribute = $param3;
		}
		
		$sub = 0;
		$image = 'regular'.($row['published'] ? '' : '_1').'.gif';

		// Return image
		return $this->generateImage($image, '', $imageAttribute).' '.$label;
	}
}

