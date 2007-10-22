<?php
/**
 * Debug Addon 
 * @author sven[�t]koalshome[punkt]de Sven Eichler
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.1 2007/10/22 19:48:42 koala_s Exp $
 */

// addon identifier
$mypage = "debug";

// CREATE LANG OBJ FOR THIS ADDON
if (!$REX['GG']) $I18N_DEBUG = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang');


// unique id
// Get Id while register addon in myREDAXO -> http://www.redaxo.de
$REX['ADDON']['rxid'][$mypage] = '81';
// foldername
$REX['ADDON']['page'][$mypage] = $mypage;    
// name shown in the REDAXO main menu
$REX['ADDON']['name'][$mypage] = 'Debug';
// permission needed for accessing the addon
$REX['ADDON']['perm'][$mypage] = 'debug[]';

// add default perm for accessing the addon to user-administration
$REX['PERM'][] = 'debug[]';


include_once ('functions/function_debug.inc.php')

?>