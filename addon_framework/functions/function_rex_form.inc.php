<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_form.inc.php,v 1.1 2006/06/13 20:31:08 koala_s Exp $
 */

function array_delete_key(& $array, $keyname)
{
  unset ($array[$keyname]);
  $array = array_values($array);
}

function array_resort_keys(& $array)
{
  return array_values($array);
}

/**
 * Rex Extension zum einbinden des Stylesheets
 */
function rex_a22_insertRexformCss($params)
{
  return rex_a22_insertCss( $params['subject'], 'addon_framework/css/common.css');
}

?>