<?php
/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_listComponent.inc.php,v 1.1 2006/06/13 19:13:42 koala_s Exp $
 */
 
/**
 * Basisklasse für alle rexList Komponenten
 */
class rexListComponent
{
  // Referenz zur dazugehörigen Liste
  var $rexlist;

  // Parameter für Links
  var $params;

  function rexListComponent($rexlist = null)
  {
    $this->rexlist = $rexlist;
  }

  function link($value, $params = array (), $tags = '')
  {
    if (count($params) == 0)
    {
      $params = $this->params;
    }
    return rex_listLink($value, $this->getGlobalParams(), $params, $tags);
  }

  function addGlobalParam($name, $value)
  {
    $this->rexlist->addGlobalParam($name, $value);
  }

  function addGlobalParams($params)
  {
    $this->rexlist->addGlobalParams($params);
  }

  function getGlobalParams()
  {
    return $this->rexlist->getGlobalParams();
  }

  function setParams($params)
  {
    if (is_array($params))
    {
      $this->params = array_merge($this->params, $params);
    }
    else
    {
      $this->params = $params;
    }
  }
} 
?>