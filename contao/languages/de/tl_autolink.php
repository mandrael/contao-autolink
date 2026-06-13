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

$GLOBALS['TL_LANG']['tl_autolink']['tag']				= array('Suchbegriff', 'Geben Sie den Suchbegriff ein, welcher auf die unten ausgewählte Seite verlinkt werden soll.');
$GLOBALS['TL_LANG']['tl_autolink']['description']		= array('Beschreibung', 'Sie können eine Beschreibung zu diesem Suchbegriff eingeben, um ihn von gleichen Suchbegriffen unterscheiden zu können.');
$GLOBALS['TL_LANG']['tl_autolink']['type']				= array('Typ', 'Bitte wählen Sie die gewünschte Link-Art aus.');
$GLOBALS['TL_LANG']['tl_autolink']['page']				= array('Zielseite', 'Bitte wählen Sie die Zielseite.');
$GLOBALS['TL_LANG']['tl_autolink']['url']				= array('URL', 'Bitte geben Sie die vollständige URL inklusive http:// ein.');
$GLOBALS['TL_LANG']['tl_autolink']['popup']				= array('In neuem Fenster öffnen', 'Klicken Sie hier, wenn der Link in einem neuen Fenster geöffnet werden soll.');
$GLOBALS['TL_LANG']['tl_autolink']['casesensitive']		= array('Groß-/Kleinschreibung beachten', 'Klicken Sie hier an, wenn die Groß-/Kleinschreibung beim Suchbegriff beachtet werden soll.');
$GLOBALS['TL_LANG']['tl_autolink']['regex']				= array('Reguläre Ausdrücke verwenden', 'Klicken Sie hier an, wenn für den Suchbegriff Reguläre Ausdrücke zugelassen sind. Weitere Information finden Sie unter <a href="http://php.net/preg_replace" onclick="window.open(this.href); return false">http://php.net/preg_replace</a>.');
$GLOBALS['TL_LANG']['tl_autolink']['selflink']			= array('Auf Zielseite anwenden', 'Klicken Sie hier an, wenn der Suchbegriff auch auf seiner eigenen Zielseite verlinkt werden soll.');
$GLOBALS['TL_LANG']['tl_autolink']['cssFilter']			= array('Auswahl mittels CSS eingrenzen', 'Sie können die Suche auf gewisse Teile der Seite eingrenzen, indem Sie CSS-Argumente benutzen.');
$GLOBALS['TL_LANG']['tl_autolink']['cssSelector']		= array('Selektor', 'Ein Selektor bestimmt, auf welchem HTML Element oder welcher Elementgruppe die Suche angewendet wird. Sie können einen oder mehrere durch Komma getrennte Selektoren erfassen.');
$GLOBALS['TL_LANG']['tl_autolink']['cssID']				= array('Stylesheet-ID und -Klasse', 'Hier können Sie eine Stylesheet-ID (id attribute) sowie eine oder mehrere Stylesheet-Klassen (class attribute) eingeben, um den Link mittels CSS formatieren zu können.');
$GLOBALS['TL_LANG']['tl_autolink']['addLanguage']		= array('Sprache zuordnen', 'Klicken Sie hier, wenn dem Begriff eine Sprache zugeordnet werden soll. Dies kann z. B. für behindertengerechte Seiten hilfreich sein.');
$GLOBALS['TL_LANG']['tl_autolink']['language']			= array('Sprache', 'Bitte wählen Sie eine Sprache.');
$GLOBALS['TL_LANG']['tl_autolink']['addTip']			= array('Tool-Tip anzeigen', 'Klicken Sie hier, wenn dem Begriff ein Tooltip hinterlegt werden soll.');
$GLOBALS['TL_LANG']['tl_autolink']['title']				= array('Titel', 'Geben Sie einen Titel für den Tooltip ein.');
$GLOBALS['TL_LANG']['tl_autolink']['text']				= array('Text', 'Geben Sie einen Text für den Tooltip ein.');
$GLOBALS['TL_LANG']['tl_autolink']['limitPages']		= array('Seiten limitieren', 'Klicken Sie hier, wenn Sie die automatische Verlinkung dieses Suchbegriffs auf gewisse Seiten limitieren wollen.');
$GLOBALS['TL_LANG']['tl_autolink']['pages']				= array('Seiten', '');
$GLOBALS['TL_LANG']['tl_autolink']['includeSubpages']	= array('Auf Unterseiten anwenden', 'Klicken Sie hier, wenn der Suchbegriff auch auf Unterseiten der Auswahl verlinkt werden soll.');
$GLOBALS['TL_LANG']['tl_autolink']['published']			= array('Veröffentlicht', 'Solange Sie diese Option nicht wählen, wird der Suchbegriff auf Ihrer Webseite nicht verlinkt.');
$GLOBALS['TL_LANG']['tl_autolink']['start']				= array('Anzeigen ab', 'Wenn Sie hier ein Datum erfassen, wird der Suchbegriff erst ab diesem Tag verlinkt.');
$GLOBALS['TL_LANG']['tl_autolink']['stop']				= array('Anzeigen bis', 'Wenn Sie hier ein Datum erfassen, wird der Suchbegriff nur bis zu diesem Tag verlinkt.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_autolink']['internal']			= 'Auf eine Contao-Seite verlinken';
$GLOBALS['TL_LANG']['tl_autolink']['external']			= 'Auf eine URL verlinken';
$GLOBALS['TL_LANG']['tl_autolink']['none']				= 'Kein Link erstellen (nur CSS-Klasse)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_autolink']['new']				= array('Neuer Link', 'Einen neuen Link erstellen');
$GLOBALS['TL_LANG']['tl_autolink']['edit']				= array('Link bearbeiten', 'Link ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_autolink']['copy']				= array('Link duplizieren', 'Link ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_autolink']['cut']				= array('Link verschieben', 'Link ID %s verschieben');
$GLOBALS['TL_LANG']['tl_autolink']['delete']			= array('Link löschen', 'Link ID %s löschen');
$GLOBALS['TL_LANG']['tl_autolink']['show']				= array('Linkdetails', 'Die Details des Link ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_autolink']['toggle']			= array('Link ein-/ausblenden', 'Link ID %s ein-/ausblenden');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_autolink']['tag_legend']		= 'Suchbegriff';
$GLOBALS['TL_LANG']['tl_autolink']['target_legend']		= 'Ziel';
$GLOBALS['TL_LANG']['tl_autolink']['limit_legend']		= 'Eingrenzung';
$GLOBALS['TL_LANG']['tl_autolink']['language_legend']	= 'Sprache';
$GLOBALS['TL_LANG']['tl_autolink']['tips_legend']		= 'Tool-Tips';
$GLOBALS['TL_LANG']['tl_autolink']['expert_legend']		= 'Erweiterte Einstellungen';
$GLOBALS['TL_LANG']['tl_autolink']['published_legend']	= 'Veröffentlichung';

