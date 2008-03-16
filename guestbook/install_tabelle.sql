-- Guestbook Addon
--
-- @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
-- @package redaxo4
-- @version $Id:$

CREATE TABLE `%TABLE_PREFIX%63_gbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(4) NOT NULL default '1',
  `author` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  `url` varchar(200) default NULL,
  `email` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `created` int(10) unsigned NOT NULL default '0',
  `reply` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
