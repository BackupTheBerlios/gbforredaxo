<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: index.inc.php,v 1.3 2008/03/16 20:13:33 koala_s Exp $
 */
 
//------------------------------> Parameter

$Basedir = dirname(__FILE__);

if (!isset ($func))
{
  $func = '';
}

if (!isset ($subpage))
{
  $subpage = '';
}

//------------------------------> Main

require $REX['INCLUDE_PATH']."/layout/top.php";

rex_title($I18N_A63->msg('menu_title'), '');


switch($subpage){
    
//    case "lang":
//        require $Basedir .'/languages.inc.php';
//    break;
    default:
        require $Basedir .'/entries.inc.php';
}

require $REX['INCLUDE_PATH']."/layout/bottom.php";

?>