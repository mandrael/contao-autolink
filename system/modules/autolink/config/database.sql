-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_autolink`
-- 

CREATE TABLE `tl_autolink` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tag` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `type` varchar(10) NOT NULL default '',
  `page` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `addLanguage` char(1) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `addTip` char(1) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` text NULL,
  `selflink` char(1) NOT NULL default '',
  `popup` char(1) NOT NULL default '',
  `casesensitive` char(1) NOT NULL default '',
  `regex` char(1) NOT NULL default '',
  `words` char(1) NOT NULL default '',
  `limitPages` char(1) NOT NULL default '',
  `pages` blob NULL,
  `includeSubpages` char(1) NOT NULL default '',
  `cssFilter` char(1) NOT NULL default '',
  `cssSelector` varchar(255) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
