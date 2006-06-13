<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: uninstall.inc.php,v 1.1 2006/06/13 20:26:08 koala_s Exp $
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