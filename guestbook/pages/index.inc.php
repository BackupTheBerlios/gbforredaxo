<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: index.inc.php,v 1.4 2009/05/28 22:13:19 koala_s Exp $
 */
 
//------------------------------> Parameter

$Basedir = dirname(__FILE__);
$func = rex_request('func', 'string');
$subpage = rex_request('subpage', 'string');

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

// Build Subnavigation
$subpages = array (
    array ('','Einrt&auml;ge'),
    array ('settings',$I18N_A63->msg('Konfiguration'))
  );

rex_title($I18N_A63->msg('menu_title'), $subpages);


switch($subpage){
    
    case 'settings':
        require $Basedir .'/settings.inc.php';
    break;
    default:
        require $Basedir .'/entries.inc.php';
}

require $REX['INCLUDE_PATH']."/layout/bottom.php";

