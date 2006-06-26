<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.4 2006/06/26 19:47:17 koala_s Exp $
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
  $error .= installAction2Modul('Gästebuch - Eintragsliste', 'Gästebuch - Eintragsliste StatusPerDatei');
}

// Funktionen dürfen ruhig auch mal ein TRUE zurückgeben
// deshalb prüfen der error-Variablen auf !== TRUE
if ($error != '' and $error !== TRUE)
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
}
else
{
  $REX['ADDON']['install']['guestbook'] = 1;
}
?>