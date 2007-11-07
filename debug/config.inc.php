<?php
/**
 * Debug Addon 
 * @author sven[ät]koalshome[punkt]de Sven Eichler
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.3 2007/11/07 22:37:02 koala_s Exp $
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
$REX['ADDON']['version'][$mypage] = "0.0.3";
$REX['ADDON']['author'][$mypage] = "Sven (Koala) Eichler";
// $REX['ADDON']['supportpage'][$mypage] = "";

// add default perm for accessing the addon to user-administration
$REX['PERM'][] = 'debug[]';


include_once ('functions/function_debug.inc.php')

?>