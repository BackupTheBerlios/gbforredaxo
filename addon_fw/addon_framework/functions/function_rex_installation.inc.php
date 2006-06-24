<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_installation.inc.php,v 1.1 2006/06/24 11:04:17 koala_s Exp $
 */

function rex_installAddon($file, $debug = false)
{
  rex_valid_type($file, 'file', __FILE__, __LINE__);
  rex_valid_type($debug, 'boolean', __FILE__, __LINE__);

  return _rex_installDump($file, $debug);
}

function rex_uninstallAddon($file, $debug = false)
{
  rex_valid_type($file, 'file', __FILE__, __LINE__);
  rex_valid_type($debug, 'boolean', __FILE__, __LINE__);

  return _rex_installDump($file, $debug);
}

function _rex_installDump($file, $debug = false)
{
  $sql = new sql();
  $sql->debugsql = $debug;
  $error = '';

  foreach (readSqlDump($file) as $query)
  {
    $sql->setQuery($query);

    if (($sqlerr = $sql->getError()) != '')
    {
      $error .= $sqlerr."\n<br/>";
    }
  }

  return $error;
}
?>