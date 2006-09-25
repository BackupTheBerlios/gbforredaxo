<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.6 2006/09/25 18:08:59 koala_s Exp $
 */
 
$error = '';
if (!OOAddon :: isAvailable('addon_framework'))
{
  $error .= 'Required addon "addon_framework" is either not installed or not activated!';
}

if ($error == '') {
  $error .= rex_installAddon(dirname(__FILE__).'/install.sql');
}

// DB-Daten installiert? Dann hiermit weiter.
if ($error == '') {
  include(dirname(__FILE__).'/install.php');
  // installAction2Modul(Name des Modules, Name der Action)
  $error .= installAction2Modul('Gaestebuch - Eintragsliste', 'Gaestebuch - Eintragsliste StatusPerDatei');
}

// Funktionen dürfen ruhig auch mal ein 'OK' zurückgeben, wenn alles in Ordnung war
// deshalb prüfen der error-Variablen auf != 'OK'
if ($error != '' and $error != 'OK') {
  $REX['ADDON']['installmsg']['guestbook'] = $error;
} else {
  $REX['ADDON']['install']['guestbook'] = 1;
}
?>