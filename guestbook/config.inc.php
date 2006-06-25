<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.3 2006/06/25 13:14:09 koala_s Exp $
 */
 
$mypage = 'guestbook'; // only for this file

if (!defined('TBL_GBOOK'))
{
  define('TBL_GBOOK', $REX['TABLE_PREFIX'].'63_gbook');
}

// CREATE LANG OBJ FOR THIS ADDON
$I18N_GBOOK = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang');

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = "63";
$REX['ADDON']['name'][$mypage] = $I18N_GBOOK->msg('menu_title');
$REX['ADDON']['perm'][$mypage] = 'guestbook[]';

$REX['PERM'][] = 'guestbook[]';

// CSS einf�gen
rex_register_extension('OUTPUT_FILTER', 'rex_a9_gbook_insert_css');

function rex_a9_gbook_insert_css($params) {
  return rex_a22_insertCss($params['subject'], 'guestbook/css/guestbook.css');
}
?>