<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: config.inc.php,v 1.16 2009/05/28 22:13:19 koala_s Exp $
 */
 
$mypage = 'guestbook'; // only for this file

if (!defined('TBL_GBOOK')) {
  define('TBL_GBOOK', $REX['TABLE_PREFIX'].'63_gbook');
}

/**
 * Der Pfad zu den Templatedateien wird hier festegelegt.
 */
if (!defined('GBOOK_TEMPLATEPATH')) {
  // ein Ordner unterhalb des Guestbook-Addon
  define('GBOOK_TEMPLATEPATH', $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/templates/');
  // oder wie w�re es mit dem files-Ordner, welcher auch �ber den Medienpool erreichbar ist
  //define('GBOOK_TEMPLATEPATH', $REX['MEDIAFOLDER'].'/');
}



// CREATE LANG OBJ FOR THIS ADDON
$I18N_A63 = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang');

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = '63';
$REX['ADDON']['name'][$mypage] = $I18N_A63->msg('menu_title');
$REX['ADDON']['perm'][$mypage] = 'guestbook_63[]';
$REX['ADDON']['version'][$mypage] = '2.1.5';
$REX['ADDON']['author'][$mypage] = 'Sven (Koala) Eichler';
$REX['ADDON']['supportpage'][$mypage] = 'forum.redaxo.de';

$REX['PERM'][] = 'guestbook_63[]';


if ($REX['REDAXO']) {
  $cssLink = '  <link rel="stylesheet" type="text/css" href="../files/tmp_/guestbook_63/guestbook.css" />'."\n";
  $extpoint = 'PAGE_HEADER';
} else {
  $cssLink = '  <link rel="stylesheet" type="text/css" href="./files/tmp_/guestbook_63/guestbook.css" />'."\n";
  $extpoint = 'PAGE_HEADER_FRONTEND';
}

rex_register_extension($extpoint, create_function('$params', 'return $params[\'subject\'].\''. $cssLink .'\';'));
