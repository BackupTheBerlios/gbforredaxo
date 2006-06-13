<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbar.browseBar.inc.php,v 1.1 2006/06/13 19:13:42 koala_s Exp $
 */

/**
 * Datensatz Navigation
 */
class browseBar extends rexListToolbar
{
  var $first_params;
  var $add_params;
  var $last_params;
  
  var $addButtonStatus;
  
  function browseBar() 
  {
    $this->setFirstParams(array ('next' => 'first'));
    $this->setAddParams(array ('func' => 'add'));
    $this->setLastParams(array ('next' => 'last'));
    $this->setAddButtonStatus(true);
  }
  
  function show()
  {
    $steps = $this->rexlist->getSteps();

    $first = $this->link('<img src="pics/first.gif" width="24" height="13" title="Zur ersten Seite" alt="Zur ersten Seite" />', $this->first_params);
    $prev = $this->link('<img src="pics/back.gif" width="24" height="13" title="Zur vorherigen Seite" alt="Zur vorherigen Seite" />', array ('next' => $steps['prev']));
    $add = $this->getAddButtonStatus() ? $this->link('<img src="pics/add.gif" width="24" height="13" title="Neuen Eintrag hinzufügen" alt="Neuen Eintrag hinzufügen" />', $this->add_params) : '';
    $next = $this->link('<img src="pics/forward.gif" width="24" height="13" title="Zur nächsten Seite" alt="Zur nächsten Seite" />', array ('next' => $steps['next']));
    $last = $this->link('<img src="pics/last.gif" width="24" height="13" title="Zur letzten Seite" alt="Zur letzten Seite" />', $this->last_params);

    return $first.' '.$prev.' '.$add.' '.$next.' '.$last."\n";
  }

  function prepareQuery(& $listsql)
  {
    $steps = $this->rexlist->getSteps();
    $listsql->addLimit($steps['curr'], $this->rexlist->getStepping());
  }
  
  function setAddParams($params) 
  {
    $this->add_params = $params;
  }

  function setFirstParams($params) 
  {
    $this->first_params = $params;
  }

  function setLastParams($params) 
  {
    $this->last_params = $params;
  }
  
  function setAddButtonStatus($status)
  {
    $this->addButtonStatus = $status;
  }
  
  function getAddButtonStatus()
  {
    return $this->addButtonStatus;
  }
}
?>