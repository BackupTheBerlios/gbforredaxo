<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: uninstall.inc.php,v 1.4 2009/01/28 14:42:38 koala_s Exp $
 */


$error = rex_install_dump(dirname(__FILE__).'/uninstall_tabelle.sql', false);

if ($error != '' and strlen($error) > 3)
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
} else {
  $REX['ADDON']['install']['guestbook'] = 0;
}

?>