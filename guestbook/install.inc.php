<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: install.inc.php,v 1.9 2007/10/22 14:33:58 koala_s Exp $
 */
 
$error = '';

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



if ($error == '' or strlen($error) < 3)
{
  $error = rex_install_dump(dirname(__FILE__).'/install_.sql', false);
}

// DB-Daten installiert? Dann hiermit weiter.
if ($error == '' or strlen($error) < 3)
{
  include(dirname(__FILE__).'/install.php');
  // installAction2Modul(Name des Modules, Name der Action)
  $error = installAction2Modul_63('Gaestebuch - Eintragsliste', 'Gaestebuch - Eintragsliste StatusPerDatei');
}
// Funktionen dürfen ruhig auch mal ein 'OK' zurückgeben, wenn alles in Ordnung war
// deshalb prüfen der error-Variablen auf != 'OK'
if ($error != '' and strlen($error) > 3)
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
} else {
  $REX['ADDON']['install']['guestbook'] = true;
}
?>