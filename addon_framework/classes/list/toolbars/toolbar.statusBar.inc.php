<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbar.statusBar.inc.php,v 1.1 2006/06/13 20:31:08 koala_s Exp $
 */

/**
 * Statusleiste zur Anzeige der Pagination
 */
class statusBar extends rexListToolbar
{
  function show()
  {
    $steps = $this->rexlist->getSteps();
    $stepping = $this->rexlist->getStepping();
    $found = $this->rexlist->numRows();

    $first = $steps['curr'];
    $last = $steps['curr'] + $stepping;
    
    if ($last > $found)
    {
      $last = $found;
    }

    // First beginnt bei 1
    if ($first +1 <= $found)
    {
      $first++;
    }

    return $this->format($first, $last, $found);
  }

  /**
   * Formatiert die Statusbar Komponenten
   * @param $first Nr. des ersten Angezeigten Elements
   * @param $last Nr. des letzten Angezeigten Elements
   * @param $max Anzahl an Elementen in der Liste
   */
  function format($first, $last, $max)
  {
    if ($max != 0)
    {
      return '<b title="Datensätze '. $first .' bis '. $last .' von insgesamt '. $max .'">'. $first .' - '. $last .' / '. $max .'</b>'."\n";
    }
    return '';
  }
}
?>