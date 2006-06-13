<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: index.inc.php,v 1.1 2006/06/13 20:26:09 koala_s Exp $
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

rex_title($I18N_GBOOK->msg('menu_title'), '');


switch($subpage){
    
//    case "lang":
//        require $Basedir .'/languages.inc.php';
//    break;
    default:
        require $Basedir .'/entries.inc.php';
}

require $REX['INCLUDE_PATH']."/layout/bottom.php";

?>