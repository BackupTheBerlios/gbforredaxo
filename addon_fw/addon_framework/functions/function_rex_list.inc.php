<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_list.inc.php,v 1.1 2006/06/24 11:04:17 koala_s Exp $
 */

/**
 * Formatiert einen übergebenen Wert als Link mit den entsprechenden Parametern
 * @param $value Wert der Link formatiert werden soll
 * @param $global_params Globale Parameter (können überschrieben werden)
 * @param $local_params Lokale Parameter (Überschreiben die globalen Parameter)
 * @param $tags Tags, die in den Link direkt eingefügt werden sollen
 */
function rex_listlink($value, $global_params = array (), $local_params = array (), $tags = '')
{
  if (count($local_params) == 0 || $value == '')
  {
    return $value;
  }

  $_params = array_merge($global_params, $local_params);
  $params = '';
  $first = true;
  foreach ($_params as $_name => $_value)
  {
    if ($_value == '')
    {
      continue;
    }

    if ($first)
    {
      $first = false;
      $params .= '?'.$_name.'='.$_value;
    }
    else
    {
      $params .= '&amp;'.$_name.'='.$_value;
    }
  }
  
  if (!empty($tags))
  {
    $tags = ' '. $tags;
  }

  return sprintf('<a href="%s"%s>%s</a>', $params, $tags, $value);
}

/**
 * Rex Extension zum einbinden des Stylesheets
 */
function rex_a22_insertRexlistCss($params)
{
  return rex_a22_insertCss( $params['subject'], 'addon_framework/css/common.css');
}

?>