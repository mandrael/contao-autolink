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

$GLOBALS['TL_DCA']['tl_autolink'] = [

    // Config
    'config' => [
        'dataContainer'    => 'Table',
        'enableVersioning' => false,
        'label'            => &$GLOBALS['TL_LANG']['MOD']['autolink'][0],
        'sql'              => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'        => 5,
            'fields'      => ['sorting'],
            'flag'        => 1,
            'panelLayout' => 'search,limit',
            'icon'        => 'bundles/mandraelcontaoautolink/wand.svg',
        ],
        'label' => [
            'fields' => ['tag', 'description'],
            'format' => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_autolink']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.svg',
            ],
            'copy' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_autolink']['copy'],
                'href'       => 'act=paste&amp;mode=copy',
                'icon'       => 'copy.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'cut' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_autolink']['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_autolink']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '').'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_autolink']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        '__selector__' => ['type', 'cssFilter', 'addLanguage', 'addTip', 'limitPages'],
        'default'      => '{tag_legend},tag,description;{target_legend},type',
        'internal'     => '{tag_legend},tag,description;{target_legend},type,page,selflink,popup;{limit_legend:hide},cssFilter,limitPages;{language_legend:hide},addLanguage;{tips_legend:hide},addTip;{expert_legend:hide},casesensitive,words,cssID,regex;{published_legend},published,start,stop',
        'external'     => '{tag_legend},tag,description;{target_legend},type,url,popup;{limit_legend:hide},cssFilter,limitPages;{language_legend:hide},addLanguage;{tips_legend:hide},addTip;{expert_legend:hide},casesensitive,words,cssID,regex;{published_legend},published,start,stop',
        'none'         => '{tag_legend},tag,description;{target_legend},type;{limit_legend:hide},cssFilter,limitPages;{language_legend:hide},addLanguage;{tips_legend:hide},addTip;{expert_legend:hide},casesensitive,words,cssID,regex;{published_legend},published,start,stop',
    ],

    // Subpalettes
    'subpalettes' => [
        'cssFilter'   => 'cssSelector',
        'addLanguage' => 'language',
        'addTip'      => 'title,text',
        'limitPages'  => 'pages,includeSubpages',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tag' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['tag'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'preserveTags' => true, 'tl_class' => 'long'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'description' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['description'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'long'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'type' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['type'],
            'default'   => 'internal',
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'radio',
            'options'   => ['internal', 'external', 'none'],
            'reference' => &$GLOBALS['TL_LANG']['tl_autolink'],
            'eval'      => ['submitOnChange' => true],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'page' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['page'],
            'exclude'   => true,
            'inputType' => 'pageTree',
            'eval'      => ['fieldType' => 'radio', 'mandatory' => true],
            'sql'       => "int(10) unsigned NOT NULL default '0'",
        ],
        'url' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['url'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'url', 'maxlength' => 255, 'mandatory' => true, 'tl_class' => 'long'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'addLanguage' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['addLanguage'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'language' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['language'],
            'exclude'   => true,
            'inputType' => 'select',
            'default'   => 'en',
            'eval'      => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
            'sql'       => "varchar(2) NOT NULL default ''",
        ],
        'addTip' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['addTip'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'title' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['title'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'long'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'text' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['text'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => ['style' => 'height: 80px', 'mandatory' => true],
            'sql'       => "text NULL",
        ],
        'popup' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['popup'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'selflink' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['selflink'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'casesensitive' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['casesensitive'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'regex' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['regex'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50 m12'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'words' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['words'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'cssFilter' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['cssFilter'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'cssSelector' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['cssSelector'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'long', 'decodeEntities' => true],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'limitPages' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['limitPages'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'pages' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['pages'],
            'exclude'   => true,
            'inputType' => 'pageTree',
            'eval'      => ['multiple' => true, 'mandatory' => true, 'fieldType' => 'checkbox'],
            'sql'       => "blob NULL",
        ],
        'includeSubpages' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['includeSubpages'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'cssID' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['cssID'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['multiple' => true, 'size' => 2, 'maxlength' => 240, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'published' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['published'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'eval'      => ['doNotCopy' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'start' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 10, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_autolink']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 10, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
    ],
];
