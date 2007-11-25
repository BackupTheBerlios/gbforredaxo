<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: config.inc.php,v 1.13 2007/11/25 13:51:03 koala_s Exp $
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
  // oder wie wäre es mit dem files-Ordner, welcher auch über den Medienpool erreichbar ist
  //define('GBOOK_TEMPLATEPATH', $REX['MEDIAFOLDER'].'/');
}



// CREATE LANG OBJ FOR THIS ADDON
$I18N_GBOOK = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang');

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = "63";
$REX['ADDON']['name'][$mypage] = $I18N_GBOOK->msg('menu_title');
$REX['ADDON']['perm'][$mypage] = 'guestbook_63[]';
$REX['ADDON']['version'][$mypage] = "2.1 RC4";
$REX['ADDON']['author'][$mypage] = "Sven (Koala) Eichler";

$REX['PERM'][] = 'guestbook_63[]';

// CSS includen
rex_register_extension('PAGE_HEADER', 'rex_a63_gbook_insert_css');
function rex_a63_gbook_insert_css($params)
{
  return $params['subject'] .'  <link rel="stylesheet" type="text/css" href="../files/tmp_/guestbook_63/guestbook.css" />'. "\n";
}

// CSS einfügen
//rex_register_extension('OUTPUT_FILTER', 'rex_a63_gbook_insert_css');

//function rex_a63_gbook_insert_css($params) {
//  return rex_a63_insertCss($params['subject'], 'guestbook/css/guestbook.css');
//}
?>