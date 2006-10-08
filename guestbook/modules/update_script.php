<?php
/**
 * Guestbook Update Script 
 *
 * Mit diesem Script ist es möglich, die Daten eines bestimmten Gästebuches 
 * in ein anderes zu kopieren. 
 * 
 * @author koala at koalashome punkt de Koala
 * @version $Id: update_script.php,v 1.2 2006/10/08 17:54:52 koala_s Exp $
 */

/**
Quell-Tabelle:

CREATE TABLE `gaestebuch` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `herkunft` varchar(50) default NULL,
  `homepage` varchar(100) default NULL,
  `text` text,
  `zeit` int(14) default NULL,
  `sichtbar` char(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM
*/

/**
Ziel-Tabelle:

CREATE TABLE `rex_9_gbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) NOT NULL default '1',
  `author` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `created` int(11) default NULL,
  `reply` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM
*/


// binde Guestbook-Einstellungen ein
// das erfordert auch, dass das Script im gleichen Verzeichnis aufgerufen wird ... hmm
//include_once ('../config.inc.php');

// ersteinmal Ziel-Tabelle leeren
$qry = 'TRUNCATE TABLE '.TBL_GBOOK;
    $sql = new sql();
//    $sql->debugsql = true;
    $data = $sql->setQuery($qry);



    //$qry = 'SELECT * FROM '.TBL_GBOOK.' WHERE status="1" OR status = "'. $status .'" ORDER BY id DESC LIMIT '. ($page * $elementsPerPage).', '.$elementsPerPage;
    $qry = 'INSERT INTO '.TBL_GBOOK.'(author, message, url, email, city, created) ';
    $qry .= 'SELECT name AS author, text AS message, homepage AS url, email, herkunft AS city, zeit AS created FROM gaestebuch';
    $sql = new sql();

    //$sql->debugsql = true;
    $data = $sql->setQuery($qry);



// Beispiel
// Daten von Gästebuch 9 (Original von Markus) nach 63 (Gästebuch V2) übernehmen

/*
$ziel_tabelle = 'rex_63_gbook';
$quell_tabelle = 'rex_9_gbook';

// ersteinmal Zieltabelle leeren
$qry = 'TRUNCATE TABLE '.$ziel_tabelle;
$sql = new sql();
//    $sql->debugsql = true;
$data = $sql->setQuery($qry);

// lese Daten der Quelltabelle und schreibe sie in die Zieltabelle
$qry = 'INSERT INTO '.$ziel_tabelle.'(id, status, author, message, url, email, city, created, replay) ';
$qry .= 'SELECT id, status, author, message, url, email, city, created, replay FROM '.$quell_tabelle;
$sql = new sql();

//$sql->debugsql = true;
$data = $sql->setQuery($qry);

// reine SQL-Anweisung:
//INSERT INTO rex_63_gbook (id, status, author, message, url, email, city, created, replay) SELECT id, status, author, message, url, email, city, created, replay FROM rex_9_gbook

*/


?>