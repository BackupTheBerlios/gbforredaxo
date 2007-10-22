<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: uninstall.inc.php,v 1.2 2007/10/22 14:33:58 koala_s Exp $
 */

$error = '';
if ($error == '')
{
  $error .= rex_uninstallAddon(dirname(__FILE__).'/uninstall.sql');
}

if ($error != '')
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
}
else
{
  $REX['ADDON']['install']['guestbook'] = 0;
}

?>