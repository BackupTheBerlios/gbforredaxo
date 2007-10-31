<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: install.inc.php,v 1.10 2007/10/31 17:43:18 koala_s Exp $
 */
 
$error = '';

/**
 * Setze SQL-Comparestatus.
 * Notwendig für SQL-Abfrage die nicht mit REDAXO > 4.0.1 funktionieren.
 */
$REX['a63_sql_compare'] = false;

/**
 * Definiere DB-Tabellennamen.
 */
if (!defined('TBL_GBOOK')) {
  define('TBL_GBOOK', $REX['TABLE_PREFIX'].'63_gbook');
}

$_REX['REDAXO-VERSION'] = $REX['VERSION'].$REX['SUBVERSION'].$REX['MINORVERSION'];


// mind. REDAXO-Version 4.0.0 muss vorhanden sein
if (version_compare($_REX['REDAXO-VERSION'], "4.0.0", "<"))
{
  $error = 'Required REDAXO <= 4.0.0';
}

// wenn REDAXO-Version = 4.0.0, dann spezielle SQL-Abfragen ignorieren
if (version_compare($_REX['REDAXO-VERSION'], "4.0.0", "=="))
{
  $REX['a63_sql_compare'] = true;
}



/**
 * pruefe config/status.txt auf Schreibrechte
 */
$Basedir = dirname(__FILE__);
$error = rex_is_writable($Basedir.'/config/status.txt');

if (($error == '' or strlen($error) < 3 ) and !OOAddon :: isAvailable('addon_framework'))
{
  $error = 'Required addon "addon_framework" is either not installed or not activated!';
}


// erstelle tmp-Ordner
$tmpFolder = $REX['MEDIAFOLDER'].'/'. $REX['TEMP_PREFIX'] .'/';
if($error == '' && !is_dir($tmpFolder) && !mkdir($tmpFolder))
  $error = 'Unable to create folder "'. $tmpFolder .'"';

// erstelle AddOn-Ordner im tmp-Ordner
$mediaFolder = $tmpFolder .'/guestbook_63/';
if($error == '' && !is_dir($mediaFolder) && !mkdir($mediaFolder))
  $error = 'Unable to create folder "'. $mediaFolder .'"';

// kopiere CSS dorthin
$cssPathSource = '/addons/guestbook/css/';
$cssFile = 'guestbook.css';

$cssSrc = $REX['INCLUDE_PATH'] . $cssPathSource . $cssFile;
$cssDst = $mediaFolder . $cssFile;

if($error == '' && !file_exists($cssDst) && !copy($cssSrc, $cssDst))
  $error = 'Unable to copy file to "'. $cssDst .'"';
// fertig

// Gibt es die GB-Tabelle schon?
$_a63_checkTabelle = rex_a63_CheckTabelle();

// SQL wird nur ausgefuehrt, wenn die Tabelle noch nicht in der DB existiert.
if (($error == '' or strlen($error) < 3) and !$_a63_checkTabelle)
{
  $error = rex_install_dump(dirname(__FILE__).'/install_.sql', false);
}

// DB-Daten installiert? Dann hiermit weiter.
if (($error == '' or strlen($error) < 3) and !$_a63_checkTabelle)
{
  include(dirname(__FILE__).'/install.php');
  // installAction2Modul(Name des Modules, Name der Action)
  $error = installAction2Modul_63('Gaestebuch - Eintragsliste', 'Gaestebuch - Eintragsliste StatusPerDatei');
}

if ($error != '' and strlen($error) > 3)
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
} else {
  $REX['ADDON']['install']['guestbook'] = true;
}



/**
 * Pruefe ob Gaestebuchtabelle schon existiert.
 *
 * @return boolean
 */
function rex_a63_CheckTabelle()
{
  $qry = "SHOW TABLES LIKE '".TBL_GBOOK."'";
  $sql = new rex_sql();
  //$sql->debugsql = true;
  $sql->setQuery($qry);

  if ($sql->getRows() == 1)
  {
    return true;
  }

  return false;
}

?>