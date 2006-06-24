<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_compat.inc.php,v 1.1 2006/06/24 11:04:17 koala_s Exp $
 */
 
/**
 * Für Installationen mit PHP < 4.3.0
 */
if (!function_exists('file_get_contents'))
{
  function file_get_contents($filename)
  {
    $fd = fopen($filename, 'rb');
    $content = fread($fd, filesize($filename));
    fclose($fd);
    return $content;
  }
}
?>